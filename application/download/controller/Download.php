<?php


namespace app\download\controller;


use app\common\controller\Base;
use app\image\model\Img;
use think\Db;
use think\Exception;

class Download extends Base
{
	public function addDownload(){
		if (!request()->isPost()){
			return show(0,'请求错误',[],403);
		}
		$data = input('post.');
		$data['id'] = uniqid(true);
		try{
			$img = new Img();
			$count = $img->where('id',$data['img_id'])->column('download');
			++$count[0];
			$img->where('id',$data['img_id'])->update(['download'   =>  $count[0]]);
			model('Download')->save($data);
//			$res = model('Download')->where('id',$data['id'])->find();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		return show(1,'下载成功',$count[0]);
	}

	public function downloadRecord(){
		if (!request()->isGet()){
			return show(0,'请求错误',[],403);
		}
		$data = input('get.');
		try{
			$list = Db::name('download')->alias('d')
				->where('d.user_id',$data['id'])
				->join('img i','d.img_id = i.id')
				->field('d.id,i.name,i.id,d.create_time,i.tags,i.imgUrl,i.zipUrl')
				->order('d.create_time desc')
				->select();

		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		for ($i = 0 ; $i < count($list) ; $i++){
			$list[$i]['create_time'] = date('Y-m-d H:i:s',$list[$i]['create_time']);
			$list[$i]['tags'] = explode(',',$list[$i]['tags']);
		}
		return show(1,'返回成功',$list);
	}

}
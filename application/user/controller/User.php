<?php


namespace app\user\controller;


use app\collect\model\Collect;
use app\common\controller\Base;
use app\download\model\Download;
use app\image\model\Img;
use app\like\model\Like;
use think\Exception;

class User extends Base
{
	public function getInfo(){
		if (!request()->isGet())
		{
			return show(0,'请求错误',[],403);
		}
		$data = input('get.');
		try{
			$res = model('User')
				->where('id',$data['id'])
				->field('id,username,create_time,gender,birthday,avatar')
				->find();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		//获取收藏数
		try{
			$collect = new Collect();
			$res['collectNum'] = $collect->where('user_id',$data['id'])->count();
			$like = new Like();
			$res['likeNum'] = $like->where('user_id',$data['id'])->count();
			$download = new Download();
			$res['downloadNum'] = $download->where('user_id',$data['id'])->count();
			$res['likedNum'] = $like->where('ori_user_id',$data['id'])->count();
			$img = new Img();
			$res['uploadNum'] = $img->where('user_id',$data['id'])->count();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		return show(1,'返回成功',$res);
	}

	public function updateAvatar(){
		if (!request()->isPost()){
			return show(0,'请求错误',[],403);
		}
		$data = input('post.');
		try{
			model('User')->where('id',$data['id'])->update(['avatar'    =>  $data['avatar']]);
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}

		return show(1,'修改成功',[]);
	}



}
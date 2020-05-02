<?php


namespace app\classify\controller;


use app\common\controller\Base;
use app\image\model\Img;
use think\Exception;

class Classify extends Base
{
	public function addClassify(){
		if (!request()->isPost()){
			return show(0,'请求错误',[],403);
		}
		$data = input('post.');
		$data['id'] = uniqid();
		$data['imgNum'] = 1;
		$data['front_cover'] = $data['front_cover'] . '?imageslim';

		$imgObj = [
			'id'    =>  uniqid(),
			'name'  =>  $data['name'],
			'width' =>  $data['width'],
			'height'=>  $data['height'],
			'tags'  =>  $data['name'],
			'like'  =>  0,
			'collect'=> 0,
			'download'=>0,
			'user_id'=> $data['user_id'],
			'imgUrl'    =>  $data['front_cover'],
			'zipUrl'    =>  $data['front_cover'] . '&imageView2/1/w/60',
			'homeShow'  =>  $data['homeShow']
		];
		unset($data['user_id']);
		unset($data['homeShow']);
		unset($data['width']);
		unset($data['height']);
		try{
			$img = new Img();
			$img->save($imgObj);
			$classify = model('Classify')->save($data);
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		if($classify){
			try{
				$res = model('Classify')
					->field('id,name,front_cover,create_time,imgNum')
					->where('id',$data['id'])
					->find();
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			return show(1,'分类上传成功',$res,200);
		}
		return show(0,'上传失败',[],403);
	}

	public function getClassifyList(){
		if (!request()->isGet()){
			return show(0,'请求错误',[],403);
		}
		try{
			$res = model('Classify')->where('name','<>','')->select();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		return show(1,'查询成功',$res,200);
	}

	public function getItem(){
		if(!request()->isGet()){
			return show(0,'请求错误',[],403);
		}
		$data = input('get.');
		try{
			$img = new Img();
			$list = $img
				->where('homeShow','1')
				->where('tags','like','%'.$data['name'].'%')
				->column('id,name,imgUrl,like,collect,download');
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		$res = array_values($list);
		return show(1,'查询成功',$res,200);
	}

}
<?php


namespace app\image\controller;


use app\collect\model\Collect;
use app\common\controller\Base;
use app\download\model\Download;
use app\like\model\Like;
use think\Exception;

class Img extends Base
{
	public function getNew(){
		if (!request()->isGet()){
			return show(0,'请求错误',[],403);
		}
		$data = input('get.');
		try{
			$res = model('Img')
				->order('create_time desc')
				->field('id,imgUrl,create_time,name,download,collect,like,user_id,tags')
				->where('homeShow',1)
				->select();
			$like = new Like();
			$likeList = $like->where('user_id',$data['id'])->column('img_id');
			$res = isLike($res,$likeList);

			$collect = new Collect();
			$collectList = $collect->where('user_id',$data['id'])->column('img_id');
			$res = isCollect($res,$collectList);

			$download = new Download();
			$downloadList = $download->where('user_id',$data['id'])->column('img_id');
			$res = isDownload($res,$downloadList);
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		for($i = 0 ; $i < count($res) ; $i ++){
			if (!preg_match("/([\x81-\xfe][\x40-\xfe])/", $res[$i]['name'], $match)) {
				//不含有汉字
				$res[$i]['name'] = '# '.$res[$i]['tags'].' #';
			}
		}
		return show(1,'返回成功',$res,200);
	}

	public function getBanner(){
		if (!request()->isGet()){
			return show(0,'请求错误',[],403);
		}
		$data = input('get.');
		try{
			$res = model('Img')
				->order('create_time desc')
				->limit(10)
				->field('id,imgUrl,create_time,name,download,collect,like,user_id')
				->where('homeShow',1)
				->select();

			$like = new Like();
			$likeList = $like->where('user_id',$data['id'])->column('img_id');
			$res = isLike($res,$likeList);

			$collect = new Collect();
			$collectList = $collect->where('user_id',$data['id'])->column('img_id');
			$res = isCollect($res,$collectList);

			$download = new Download();
			$downloadList = $download->where('user_id',$data['id'])->column('img_id');
			$res = isDownload($res,$downloadList);
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		return show(1,'返回成功',$res,200);
	}

	public function getTopTen(){
		if (!request()->isGet()){
			return show(0,'请求错误',[],403);
		}
		$data = input('get.');
		try{
			$res = model('Img')
				->order('like desc')
				->limit(10)
				->field('id,imgUrl,create_time,name,download,collect,like,user_id,tags')
				->select();

			//查询是否已经点过赞
			$like = new Like();
			$likeList = $like->where('user_id',$data['id'])->column('img_id');

//			var_dump($likeList);
			$res = isLike($res,$likeList);
			//isCollect isDownload...

//			var_dump($res);
			$collect = new Collect();
			$collectList = $collect->where('user_id',$data['id'])->column('img_id');
			$res = isCollect($res,$collectList);

			$download = new Download();
			$downloadList = $download->where('user_id',$data['id'])->column('img_id');
			$res = isDownload($res,$downloadList);
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		for($i = 0 ; $i < count($res) ; $i ++){
			if (!preg_match("/([\x81-\xfe][\x40-\xfe])/", $res[$i]['name'], $match)) {
				//不含有汉字
				$res[$i]['name'] = '# '.$res[$i]['tags'].' #';
			}
		}
		return show(1,'返回成功',$res,200);
	}

	public function uploadRecord(){
		if (!request()->isGet()){
			return show(0,'请求错误',[],403);
		}
		$data = input('get.');
		try{
			$res = model('Img')
				->where('user_id',$data['id'])
				->field('id,name,create_time,zipUrl,download,like,collect,homeShow,tags,imgUrl')
				->select();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		foreach ($res as $key){
			$key['tags'] = explode(',',$key['tags']);
		}
		return show(1,'返回成功',$res);
	}

	public function updateImgInfo(){
		if (!request()->isPost()){
			return show(0,'请求错误',[],403);
		}
		$data = input('post.');
		try{
			model('Img')->where('id',$data['id'])->update($data);
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		try{
			$res = model('Img')
				->where('id',$data['id'])
				->field('id,name,create_time,zipUrl,download,like,collect,homeShow,tags,imgUrl')
				->select();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		foreach ($res as $key){
			$key['tags'] = explode(',',$key['tags']);
		}
		return show(1,'更新成功',$res);
	}
	
}
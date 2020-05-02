<?php


namespace app\collect\controller;


use app\common\controller\Base;
use app\download\model\Download;
use app\image\model\Img;
use app\like\model\Like;
use think\Db;
use think\Exception;

class Collect extends Base
{
	public function addCollect(){
		if (!request()->isPost()){
			return show(0,'请求错误',[],403);
		}
		$data = input('post.');
		$data['id'] = uniqid(true);//收藏记录id
		//判断是否已经收藏过了
		try{
			$hasCollect = model('Collect')
				->where('img_id',$data['img_id'])
				->where('user_id',$data['user_id'])
				->find();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		$img = new Img();
		//收藏表不存在记录，用户没有收藏过,添加收藏
		if ($hasCollect == null){
			try{
				$count = $img->where('id',$data['img_id'])->column('collect');
				++$count[0];
				$img->where('id',$data['img_id'])->update(['collect'    =>  $count[0]]);
				model('Collect')->save($data);
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			return show(1,'收藏成功',$count[0],200);
		}
		//记录不为空，返回现有收藏数量
		else{
			try{
				$count = $img->where('id',$data['img_id'])->column('collect');
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			return show(1,'收藏成功',$count[0],200);
		}
	}

	public function cancelCollect(){
		if (!request()->isPost()){
			return show(0,'请求错误',[],403);
		}
		$data = input('post.');
		//判断用户是否已经收藏
		try{
			$hasCollect = model('Collect')
				->where('img_id',$data['img_id'])
				->where('user_id',$data['user_id'])
				->find();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		$img = new Img();
		//用户没有收藏或者已经取消收藏，返回当前收藏个数
		if ($hasCollect == null){
			try{
				$count = $img->where('id',$data['img_id'])->column('collect');
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			return show(1,'取消收藏',$count[0],200);
		}
		//用户已经收藏，取消收藏
		else{
			try{
				$count = $img->where('id',$data['img_id'])->column('collect');
				--$count[0];
				$img->where('id',$data['img_id'])->update(['collect'    =>  $count[0]]);
				$id = model('Collect')
					->where('img_id',$data['img_id'])
					->where('user_id',$data['user_id'])
					->column('id');
				\app\collect\model\Collect::destroy($id);
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			return show(1,'取消收藏',$count[0],200);
		}
	}

	public function collectRecord(){
		if (!request()->isGet()){
			return show(0,'请求错误',[],403);
		}
		$data = input('get.');
		try{
			$res = Db::name('collect')
				->alias('c')
				->where('c.user_id',$data['id'])
				->join('img i','i.id = c.img_id')
				->field('i.id,i.name,c.create_time,i.imgUrl,i.width,i.height,i.collect,i.download,i.like')
				->order('c.create_time desc')
				->select();

			$like = new Like();
			$likeList = $like->where('user_id',$data['id'])->column('img_id');

			for($i = 0 ; $i < count($res) ; $i++){
				if (empty($likeList)){
					$res[$i]['isLike'] = false;
				}
				for($j = 0 ; $j < count($likeList) ; $j++){
					if($res[$i]['id'] == $likeList[$j]){
						$res[$i]['isLike'] = true;
						break;
					}else{
						$res[$i]['isLike'] = false;
					}
				}
			}

			$collect = new \app\collect\model\Collect();
			$collectList = $collect->where('user_id',$data['id'])->column('img_id');

			for($i = 0 ; $i < count($res) ; $i++){
				if (empty($collectList)){
					$res[$i]['isCollect'] = false;
				}
				for($j = 0 ; $j < count($collectList) ; $j++){
					if($res[$i]['id'] == $collectList[$j]){
						$res[$i]['isCollect'] = true;
						break;
					}else{
						$res[$i]['isCollect'] = false;
					}
				}
			}

			$download = new Download();
			$downloadList = $download->where('user_id',$data['id'])->column('img_id');
			for($i = 0 ; $i < count($res) ; $i++){
				if (empty($downloadList)){
					$res[$i]['isDownload'] = false;
				}
				for($j = 0 ; $j < count($downloadList) ; $j++){
					if($res[$i]['id'] == $downloadList[$j]){
						$res[$i]['isDownload'] = true;
						break;
					}else{
						$res[$i]['isDownload'] = false;
					}
				}
			}
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		for ($i = 0 ; $i < count($res) ; $i++){
			$res[$i]['create_time'] = date('Y-m-d H:i:s',$res[$i]['create_time']);
		}
		return show(1,'返回成功',$res);
	}

}
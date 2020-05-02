<?php


namespace app\like\controller;


use app\collect\model\Collect;
use app\common\controller\Base;
use app\download\model\Download;
use app\image\model\Img;
use think\Db;
use think\Exception;

class Like extends Base
{
	public function addLike(){
		if (!request()->isPost()){
			return show(0,'请求错误',[],403);
		}
		$data = input('post.');
		$data['img_id'] = $data['id'];
		$data['id'] = uniqid(true);

		//先判断用户是否已经对该图片点过赞
		try{
			$hasLiked = model('Like')
				->where('img_id',$data['img_id'])
				->where('user_id',$data['user_id'])
				->find();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		$img = new Img();
		//点赞列表中没有记录，用户没有点过赞
		if ($hasLiked == null){
			try{
				$count = $img->where('id',$data['img_id'])->column('like');
				++$count[0];
				$img->where('id',$data['img_id'])->update(['like'   =>  $count[0]]);
				$res = $img->where('id',$data['img_id'])->column('like');
				model('Like')->save($data);
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			return show(1,'点赞成功',$res[0],200);
		}
		//用户列表中有记录，用户点过赞，查询现有点赞个数并返回
		else{
			try{
				$res = $img->where('id',$data['img_id'])->column('like');
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			return show(1,'点赞成功',$res[0],200);
		}

	}

	public function cancelLike(){
		if (!request()->isPost()){
			return show(0,'请求错误',[],403);
		}
		$data = input('post.');
		$data['img_id'] = $data['id'];
		$data['id'] = uniqid(true);

		//判断是否已经取消点赞
		try{
			$hasCancel = model('Like')
							->where('img_id',$data['img_id'])
							->where('user_id',$data['user_id'])
							->find();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		$img = new Img();
		//用户没有点赞记录，因此已经取消了点赞或者没有点赞，返回现有点赞数
		if ($hasCancel == null){
			try{
				$res = $img->where('id',$data['img_id'])->column('like');
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			return show(1,'取消点赞',$res[0],200);
		}
		//点赞表中有记录，用户为点赞状态，正常取消点赞
		else{
			try{
				$count = $img->where('id',$data['img_id'])->column('like');
				--$count[0];
				$img->where('id',$data['img_id'])->update(['like'   =>  $count[0]]);
				$res = $img->where('id',$data['img_id'])->column('like');
				$id = model('Like')
					->where('img_id',$data['img_id'])
					->where('user_id',$data['user_id'])
					->column('id');
//				var_dump($id);
				\app\like\model\Like::destroy($id);
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			return show(1,'取消点赞',$res[0],200);
		}

	}

	public function likeRecord(){
		if (!request()->isGet()){
			return show(0,'请求错误',[],403);
		}
		$data = input('get.');
		try{
			$res = Db::name('like')
				->alias('l')
				->where('l.user_id',$data['id'])
				->join('img i','l.img_id = i.id')
				->field('i.id,i.name,i.imgUrl,l.create_time,i.collect,i.like,i.download,i.width,i.height')
				->order('l.create_time desc')
				->select();
			$like = new \app\like\model\Like();
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

			$collect = new Collect();
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

	public function likedRecord(){
		if (!request()->isGet()){
			return show(0,'请求失败',[],403);
		}
		$data = input('get.');
		try{
			$res = Db::name('like')
				->alias('l')
				->where('ori_user_id',$data['id'])
				->join('img i','i.id = l.img_id')
				->join('user u','u.id = l.user_id')
				->field('i.id,u.avatar,u.username,l.create_time,i.zipUrl')
				->select();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		for ($i = 0 ; $i < count($res) ; $i++){
			$res[$i]['create_time'] = date('Y-m-d H:i:s',$res[$i]['create_time']);
			$res[$i]['zipUrl'] = $res[$i]['zipUrl'] . '&imageView2/1/w/60';
		}
		return show(1,'返回成功',$res);
	}

}
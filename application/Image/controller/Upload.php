<?php


namespace app\image\controller;


use app\common\controller\Base;
use Qiniu\Auth;
use think\Cache;
use think\Exception;
use app\classify\model\Classify;

class Upload extends Base
{
	public function getToken(){
		$data = input('post.');
		$accessKey  = 'X6KlJ0aACNyzYNeiuZcVAYIIjVO8yS8T-f8_CWLX';
		$secretKey = 'oKtIafO9xkY0iMZ9ofR50Gwr4JyqJydzyRESwtvj';
		$auth = new Auth($accessKey,$secretKey);

		$bucket = 'turtle-qdu';

		$token = $auth->uploadToken($bucket);
		$msg = $this->checkCache($data['id']);
		if ($msg != null){
			return show(0,$msg,[],403);
		}
		return $token;
	}

	public function getAvatarToken(){
		$accessKey  = 'X6KlJ0aACNyzYNeiuZcVAYIIjVO8yS8T-f8_CWLX';
		$secretKey = 'oKtIafO9xkY0iMZ9ofR50Gwr4JyqJydzyRESwtvj';
		$auth = new Auth($accessKey,$secretKey);

		$bucket = 'turtle-qdu';

		$token = $auth->uploadToken($bucket);
		return $token;
	}

//	检查缓存，用户今日是否上传数据，总上传数量是否超过20
	public function checkCache($id){
		if (Cache::get('count') >= 20){
			return '今日上传数量已达最大限制20张';
		}
		if (Cache::get($id) >= 1){
			return '您今天已经上传过照片啦，无法上传更多';
		}
		return null;
	}

	/**
	 * @return \think\response\Json
	 * @throws \Exception
	 */
	public function save(){
		if(!request()->isPost()){
			return show(0,'请求错误',[],403);
		}

		$data = input('post.');


		$data['id'] = uniqid(true);
		$data['like'] = 0;
		$data['collect'] = 0;
		$data['download'] = 0;
		$data['zipUrl'] = $data['imgUrl'];
//		 . '&imageView2/1/w/60'
		if($data['tags'] != '' || !empty($data['tags'])){
			$classifyArr = explode(',',$data['tags']);
			try{
				$classify = new Classify();
				$createdClassifyArr = $classify->column('name');

			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
			//需要添加的标签数组
			$addClassifyArr = array_diff($classifyArr,$createdClassifyArr);

			//需要更新的标签数组
			$classifyIntersect = array_intersect($classifyArr,$createdClassifyArr);
			if (!empty($classifyIntersect)){
				try{
					foreach ($classifyIntersect as $key){

						$count = $classify->where('name',$key)->column('imgNum');
						++$count[0];
						$classify->where('name',$key)->update(['imgNum'  =>  $count[0]]);
					}
				}catch (Exception $e){
					return show(0,$e->getMessage(),[],403);
				}
			}

			$updateArr = [];//插入数据库的数组
			if (!empty($addClassifyArr)){
				foreach ($addClassifyArr as $key){
					array_push($updateArr,[
						'id'    =>  uniqid(),
						'name'  =>  $key,
						'front_cover'   =>  $data['zipUrl'],
						'imgNum'    =>  1
					]);
				}
			}
			try{
				$count = $classify->saveAll($updateArr,false);
//			model('Classify')->saveAll($updateArr);
			}catch (Exception $e){
				return show(0,$e->getMessage(),[],403);
			}
		}

		try{
			$img = model('Img')->save($data);
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		try{
			$imgItem = model('Img')->where('id',$data['id'])->find();
		}catch (Exception $e){
			return show(0,'图片错误',[],403);
		}
		if ($img){

			//设置缓存，
			$userCacheCount = Cache::pull($data['user_id']);
			if ($userCacheCount == null){
				$userCacheCount = 1;
				Cache::set($data['user_id'],$userCacheCount,new \DateTime(date("Y-m-d",strtotime('+1 days'))));
			}else{
				Cache::set($data['user_id'],++$userCacheCount,new \DateTime(date("Y-m-d",strtotime('+1 days'))));
			}
			$cacheCount = Cache::pull('count');
			if ($cacheCount == null){
				Cache::set('count',1,new \DateTime(date("Y-m-d",strtotime('+1 days'))));
			}else{
				Cache::set('count',++$cacheCount,new \DateTime(date("Y-m-d",strtotime('+1 days'))));
			}

			return show(1,'图片上传成功',$imgItem,200);
		}
		return show(0,'上传失败',[],403);
	}

}
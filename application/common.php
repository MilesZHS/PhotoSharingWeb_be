<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function show($status,$message,$data = [],$httpCode = 200){
	$data = [
		'status'    =>  $status,
		'message'   =>  $message,
		'data'      => $data
	];
	return json($data,$httpCode);
}

//检查点赞、收藏、下载状态，返回是否激活按钮
function isLike($imgList,$likeList){
	foreach ($imgList as $key){
		if (empty($likeList)){
			$key['isLike'] = false;
		}
		foreach ($likeList as $key1){
			if($key['id'] == $key1){
				$key['isLike'] = true;
				break;
			}else{
				$key['isLike'] = false;
			}
		}
	}
	return $imgList;
}

//collect创建完毕后需要删除一层循环的两行
function isCollect($imgList,$collectList){
	foreach ($imgList as $key){
		if (empty($collectList)){
			$key['isCollect'] = false;
		}
		foreach ($collectList as $key1){
			if($key['id'] == $key1){
				$key['isCollect'] = true;
				break;
			}else{
				$key['isCollect'] = false;
			}
		}
	}
	return $imgList;
}
function isDownload($imgList,$downloadList){
	foreach ($imgList as $key){
		if (empty($downloadList)){
			$key['isDownload'] = false;
		}
		foreach ($downloadList as $key1){
			if($key['id'] == $key1){
				$key['isDownload'] = true;
				break;
			}else{
				$key['isDownload'] = false;
			}
		}
	}
	return $imgList;
}
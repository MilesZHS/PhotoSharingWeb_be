<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use \think\Route;

//用户鉴权接口
Route::post('/login','user/identity/login');
Route::post('/register','user/identity/register');
Route::post('/test','test/test/index');

//用户信息
Route::get('/userinfo','user/user/getinfo');
Route::post('/updateavatar','user/user/updateavatar');
Route::get('/uploadrecord','image/img/uploadrecord');
Route::get('/downloadrecord','download/download/downloadrecord');
Route::get('/likerecord','like/like/likerecord');
Route::get('/collectrecord','collect/collect/collectrecord');
Route::get('/likedrecord','like/like/likedrecord');
Route::post('/avatartoken','image/upload/getavatartoken');
Route::post('/updateuserinfo','user/user/update');
Route::post('/updateimginfo','image/img/updateimginfo');

//图片上传接口
Route::post('/uploadtoken','image/upload/gettoken');
Route::post('/upload','image/upload/save');

//分类相关接口
Route::post('/addclassify','classify/classify/addclassify');
Route::get('/classifylist','classify/classify/getclassifylist');
Route::get('/getclassifyitem','classify/classify/getitem');

//图片获取接口
Route::get('/newupload','image/img/getnew');
Route::get('/topten','image/img/gettopten');
Route::get('/banner','image/img/getbanner');

//点赞
Route::post('/addlike','like/like/addlike');
Route::post('/cancellike','like/like/cancellike');

//收藏
Route::post('/addcollect','collect/collect/addcollect');
Route::post('/cancelcollect','collect/collect/cancelcollect');

//下载
Route::post('/adddownload','download/download/adddownload');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];

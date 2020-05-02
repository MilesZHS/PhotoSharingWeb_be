<?php


namespace app\user\controller;


use think\Controller;
use think\Exception;

class Identity extends Controller
{
	public function login(){
		if (!request()->isPost()){
			return show(0,'请求不合法',[],403);
		}
		$data = input('post.');
		try{
			$userInfo = model('User')
				->field('id,username,password,create_time,gender,birthday,avatar')
				->where('username',$data['username'])
				->find();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],400);
		}
		if (!$userInfo){
			return show(0,'用户不存在',[],403);
		}
		if (md5($data['password']) != $userInfo['password']){
			return show(0,'密码错误！',[],403);
		}
		unset($userInfo['password']);
		$userInfo['token'] = IAuth::createToken($userInfo['id'],$data['username']);
		return show(1,'登录成功',$userInfo);

	}

	public function register(){
		if (!request()->isPost()){
			return show(0,'请求不合法',[],403);
		}
		$data = input('post.');
		try{
			$user = model('User')->where('username',$data['username'])->find();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		if($user){
			return show(0,'用户已存在，请直接登录',[],403);
		}
		$data['password'] = md5($data['password']);
		$data['id'] = uniqid();
		$data['avatar'] = 'http://qdu17zs.com/logo.png';
		try{
			$res = model('User')->save($data);
		}catch (Exception $e){
			return show(0,$e->getMessage(),[]);
		}

		if ($res !== 1){
			return show(0,'注册失败',[],400);
		}
		try{
			$userInfo = model('User')
				->field('id,username,create_time,gender,birthday,avatar')
				->where('id',$data['id'])
				->find();
		}catch (Exception $e){
			return show(0,$e->getMessage(),[],403);
		}
		$userInfo['token'] = IAuth::createToken($data['id'],$data['username']);
		return show(1,'注册成功',$userInfo,200);
	}

}
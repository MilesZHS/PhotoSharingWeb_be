<?php


namespace app\user\controller;


use app\common\lib\execption\ApiExecption;
use Firebase\JWT\JWT;
use think\Controller;

class IAuth extends Controller
{

	public static function createToken($id,$username){
		$key = config('keys.key');
		$payload = array(
			'id'    =>  $id,
			'username'  =>  $username,
			'create_time'   =>  time(),
			'expired_time'   =>  strtotime('+1 week')
		);
		$jwt = JWT::encode($payload,$key);
		return $jwt;
	}

	public static function checkToken($token){
		if (empty($token) || $token == ''){
			throw new ApiExecption('您还没有登录',403);
		}
		$key = config('keys.key');
		$arr = explode('.',$token);
		if (count($arr) != 3){
			throw new ApiExecption('token格式错误',403);
		}
		$decoded = JWT::decode($token, $key, array('HS256'));
		$decoded_array = (array) $decoded;
		if (time() > $decoded_array['expired_time']){
			throw new ApiExecption('签名已过期，请重新登录',403);
		}
	}


}
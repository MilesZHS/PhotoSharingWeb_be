<?php


namespace app\common\controller;


use app\common\lib\execption\ApiExecption;
use app\user\controller\IAuth;
use Firebase\JWT\JWT;
use think\Controller;
use think\Request;

class Base extends Controller
{
	protected function _initialize()
	{
		parent::_initialize(); // TODO: Change the autogenerated stub
		$token = Request::instance()->header('token');
		IAuth::checkToken($token);
	}

}
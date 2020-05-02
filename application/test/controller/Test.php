<?php


namespace app\test\controller;



use app\user\controller\IAuth;
use think\Controller;
use think\Request;

class Test extends Controller
{
	public function index(){
		return '111';
	}

}
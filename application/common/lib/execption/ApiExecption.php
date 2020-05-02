<?php /** @noinspection PhpMissingParentConstructorInspection */


namespace app\common\lib\execption;


use think\Exception;

class ApiExecption extends Exception
{
	public $message = '';
	public $httpCode = 0;
	public $code = 0;

	public function __construct($message = "", $httpCode = 0 ,$code = 0)
	{
		$this->message = $message;
		$this->httpCode = $httpCode;
		$this->code = $code;
	}

}
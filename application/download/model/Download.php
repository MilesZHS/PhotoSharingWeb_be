<?php


namespace app\download\model;


use think\Model;
use traits\model\SoftDelete;

class Download extends Model
{
	protected $autoWriteTimestamp = true;
	use SoftDelete;
	protected $deleteTime = 'delete_time';

}
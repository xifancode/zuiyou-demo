<?php

namespace app\admin\model;

use think\Model;

class AdminRoleAccess extends Model
{
		// 定义时间戳字段名
	    protected $createTime = 'create_time';
	    protected $updateTime =false;
	    // 自动写入时间戳
	    protected $autoWriteTimestamp = true;

}
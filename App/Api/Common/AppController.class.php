<?php
// +----------------------------------------------------------------------
// | 超管基类
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 All rights reserved.
// +----------------------------------------------------------------------
// | Author: 雪地里的松鼠
// +----------------------------------------------------------------------

namespace Api\Common;

use Think\Controller;
class AppController extends Controller {
	protected   $filedomail = '';
	//初始化
	protected function _initialize() {
		header("Access-Control-Allow-Origin: ".$_SERVER['HTTP_ORIGIN']);
		header('Access-Control-Allow-Methods: "OPTIONS, GET, POST"');
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Allow-Headers: "Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"');
		header('Access-Control-Max-Age: "3600"');

		if(!C('FILE_DOMAIN')){
			$this->filedomail = get_domain();
		}else{
			$this->filedomail = C('FILE_DOMAIN');
		}
		//sleep(2);
	}

}

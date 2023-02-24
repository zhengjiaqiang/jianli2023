<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------update user set password=password('wuguanke!@#$888') where user='root';  
// 应用入口文件
if (time()<1644188400) {
    header('Location:https://www.huashan.org.cn/weihu.html');die;
}

date_default_timezone_set('PRC');
// $begintime=strtotime(date('Y-m-d 07:30:00'));
// $endtime=strtotime(date('Y-m-d 17:30:00'));
// $begindate=strtotime(date('Y-10-01'));
// if(time()-$begindate > 0){
// 	if(time()-$begintime < 0  ||  time() - $endtime  > 0)  {
// 		header('location:index.html'); exit();
// 	}
// }

//$redis = new \Redis();
//$res = $redis->connect('localhost',6379);
//$res1 = $redis->set('name','zjq');
//var_dump($res1);die;

//var_dump(ini_get("session.save_handler"));
//var_dump(ini_get("session.save_path"));die;

if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

define('APP_PATH','./App/');
define('BUILD_DIR_SECURE', false);
// 引入ThinkPHP入口文件
define('HTML_PATH', './Html/');

require './vendor/autoload.php';
require './ThinkPHP/ThinkPHP.php';




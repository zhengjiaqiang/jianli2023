<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE'       =>    'Icenter',  // 默认模块
	'MODULE_ALLOW_LIST'    =>    array('systemv','Api','Login','User','Icenter'),
	'URL_CASE_INSENSITIVE'  =>  true,  
	'URL_MODEL'          => '2',
	'URL_HTML_SUFFIX'=>'shtml',
	/* 数据库设置 */
	//'DATA_CACHE_TYPE' => 'Redis',
	//'REDIS_HOST'=>'59.188.255.209',
	//'REDIS_PORT' => '6379',
	//'DATA_CACHE_TIMEOUT'=>'3600',
	//'REDIS_AUTH'=>'mealone365!',
	'DB_TYPE' => 'mysql', // 数据库类型
	'DB_HOST' => '59.188.255.209', // 服务器地址
	'DB_NAME' => 'jl', // 数据库名
	'DB_USER' => 'svn', // 用户名
	'DB_PWD' => 'svn888', // 密码
	'URL_CASE_INSENSITIVE'  =>  true,   // 默认false 表示URL区分大小写 true则表示不区分大小写
	'DB_PORT' => '3306', // 端口
	'DB_PREFIX' => 'hh_', // 数据库表前缀
	'DB_DEBUG' => true,
	'TMPL_TEMPLATE_SUFFIX'  =>  '.php',     // 默认模板文件后缀
	'COOKIE_PREFIX' => 'ViZL_', //Cookie前缀
	'DATA_CACHE_PREFIX' => 'ViZL_', // 缓存前缀
	'DATA_BACKUP_PATH'=>'./backup/',
	'HOME_SKIN_URL'=>'./App/Home/View/',
	'HTML_FILE_SUFFIX' =>'.shtml',
	'STATUS_LIST'=>['新简历'=>[''],'已甄选'=>[],'科室甄选'=>'','通知面试'=>'','面试情况','储备人才']
);



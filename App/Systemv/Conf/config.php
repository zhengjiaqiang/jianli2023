<?php
return array(
	'URL_HTML_SUFFIX'=>'',
	'TMPL_ACTION_ERROR' => dirname(__FILE__). '/Tpl/dispatch_jump.tpl',
	'TMPL_TEMPLATE_SUFFIX'  =>  '.php',
	'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
	'TOKEN_ON'      =>    false,
);

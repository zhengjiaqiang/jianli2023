<?php 
namespace Common\Libs\Util;

class Sms{
	const WEB_URL='http://api.xunyaosoft.com/zc/zhicode/api.php';
	const LOGIN_SET=['code'=>'signIn','uPhoneNo'=>'15618170643','uPassword'=>1111111];
	const GET_USER_PASSWORD=['code'=>'getPhoneNo','projName'=>'搜搜拼单'];
	const SMS_USER_PASSWORD=['code'=>'getMsg','uPhoneNo'=>'15618170643','uPassword'=>1111111];
	
	public static function  GetNumber(){
		return self::webservice(self::WEB_URL,self::GET_USER_PASSWORD);
	}
	private static function login(){
		return  file_get_contents('http://xunyaosoft.com/zc/zhicode/api.php?code=signIn&uPhoneNo=15618170643&uPassword=1111111');
	}
	public static function GetMessage($mobile,$project){
		return self::webservice(self::WEB_URL,array_merge(self::SMS_USER_PASSWORD,['projName'=>$project,'phoneNo'=>$mobile]));
	}
	public static  function webservice($url, $param, $data = '', $method = 'GET'){
		$opts = array(
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
		);
	
		/* 根据请求类型设置特定参数 */
		$opts[CURLOPT_URL] = $url . '?' . http_build_query($param);
		$opts[CURLOPT_COOKIE]='PHPSESSID='.$_COOKIE['PHPSESSID'];
		if(strtoupper($method) == 'POST'){
			$opts[CURLOPT_POST] = 1;
			$opts[CURLOPT_POSTFIELDS] = $data;
			if(is_string($data)){ //发送JSON数据
				$opts[CURLOPT_HTTPHEADER] = array(
					'Content-Type: application/json; charset=utf-8',  
					'Content-Length: ' . strlen($data),
				);
			}
		}
		print_r($opts);
		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		// var_dump($data);
		//发生错误，抛出异常
		if($error) throw new \Exception('请求发生错误：' . $error);
		return  $data;
	}
	
}
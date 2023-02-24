<?php

/**
 * 分割的字符串
 * @param $value 需要分割的字符串
 * @param $patt  分隔符
 * @return Array
 */
function string_split($value,$patt){
	if(empty($value) || empty($patt)) {
		return null;
	}
	return explode($patt,trim($value,$patt));
}
function curl_post_https($url,$data){ // 模拟提交数据函数
	$curl = curl_init(); // 启动一个CURL会话
	curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查;
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
	curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
	curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
	curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
	curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
	curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
	$tmpInfo = curl_exec($curl); // 执行操作
	if (curl_errno($curl)) {
		echo 'Errno'.curl_error($curl);//捕抓异常
	}
	curl_close($curl); // 关闭CURL会话
	return $tmpInfo; // 返回数据，json格式
}
/**
 * http get
 */
function webservice($url, $param, $data = '', $method = 'GET'){
    $opts = array(
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
    );

    /* 根据请求类型设置特定参数 */
	$opts[CURLOPT_URL] = $url . '?' . http_build_query($param);

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
	/* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data  = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    //发生错误，抛出异常
    if($error) throw new \Exception('请求发生错误：' . $error);
    return  $data;
}
function str_regex($reg,$str){
	return preg_match($reg,$str);
}

/**
 * 验证方法是否是汉字数字和下划线组成的字符串
 * @param $str string 要验证的字符串
 * @return bool
 */
function is_normalchar($str){
	return str_regex("/^[\x{4e00}-\x{9fa5}\w]+$/u",$str);
}
/**
 * 验证是否正确的电话号码
 * @param $str string 要验证的字符串
 * @return bool
 */
function is_phone($str){
	return str_regex("/^(0[0-9]{2,3}\-)?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/",$str);
}
/**
 * 验证是否正确的手机号码
 * @param $str string 要验证的字符串
 * @return bool
 */
function is_mobile($str){
	return str_regex("/^(((1[0-9]{2}))+\d{8})$/",$str);
}
/**
 * 验证是否正确的联系电话
 * @param $str string 要验证的字符串
 * @return bool
 */
function is_mobilephone($str){
	if(is_mobile($str) || is_phone($str)) {
		return true;
	}else{
		return false;
	}
}

/**
 * 验证是否是汉字
 * @param $str string 要验证的字符串
 * @return bool
 */
function is_chinese($str){
	return str_regex("/^[\x{4e00}-\x{9fa5}]+$/u",$str);
}

/**
 * 验证是否是数字
 * @param $str string 要验证的字符串
 * @return bool
 */
function is_digits($str){
	return str_regex("/^\d+$/",$str);
}
/**
 * 验证是否是字母
 * @param $str string 要验证的字符串
 * @return bool
 */
function is_word($str){
	return str_regex("/^[a-zA-Z_]*$/",$str);
}

/* function is_date($str){
	return str_regex("/^$/",$str);
} */

/**
 * 验证URL
 * @param $str string 要验证的字符串
 * @return bool
 */
function is_url($str){
	return str_regex("/^[a-z](?:[-a-z0-9\+\.])*:(?:\/\/(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=:])*@)?(?:\[(?:(?:(?:[0-9a-f]{1,4}:){6}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|::(?:[0-9a-f]{1,4}:){5}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){4}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:[0-9a-f]{1,4}:[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){3}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:(?:[0-9a-f]{1,4}:){0,2}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){2}(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:(?:[0-9a-f]{1,4}:){0,3}[0-9a-f]{1,4})?::[0-9a-f]{1,4}:(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:(?:[0-9a-f]{1,4}:){0,4}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3})|(?:(?:[0-9a-f]{1,4}:){0,5}[0-9a-f]{1,4})?::[0-9a-f]{1,4}|(?:(?:[0-9a-f]{1,4}:){0,6}[0-9a-f]{1,4})?::)|v[0-9a-f]+[-a-z0-9\._~!\$&'\(\)\*\+,;=:]+)\]|(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])(?:\.(?:[0-9]|[1-9][0-9]|1[0-9][0-9]|2[0-4][0-9]|25[0-5])){3}|(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=@])*)(?::[0-9]*)?(?:\/(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=:@]))*)*|\/(?:(?:(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=:@]))+)(?:\/(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=:@]))*)*)?|(?:(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=:@]))+)(?:\/(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=:@]))*)*|(?!(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=:@])))(?:\?(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=:@])|[\x{E000}-\x{F8FF}\x{F0000}-\x{FFFFD}|\x{100000}-\x{10FFFD}\/\?])*)?(?:\#(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~\x{A0}-\x{D7FF}\x{F900}-\x{FDCF}\x{FDF0}-\x{FFEF}\x{10000}-\x{1FFFD}\x{20000}-\x{2FFFD}\x{30000}-\x{3FFFD}\x{40000}-\x{4FFFD}\x{50000}-\x{5FFFD}\x{60000}-\x{6FFFD}\x{70000}-\x{7FFFD}\x{80000}-\x{8FFFD}\x{90000}-\x{9FFFD}\x{A0000}-\x{AFFFD}\x{B0000}-\x{BFFFD}\x{C0000}-\x{CFFFD}\x{D0000}-\x{DFFFD}\x{E1000}-\x{EFFFD}!\$&'\(\)\*\+,;=:@])|[\/\?])*)?$/u",$str);
}

/**
 * 验证是否是Email
 * @param $str string 要验证的字符串
 * @return bool
 */
function is_email($str){
	return str_regex("/^[\w\d]+[\w\d-.]*@[\w\d-.]+\.[\w\d]{2,10}$/i",$str);
}


function is_delete_ids($str){
	return str_regex("/^\d+(,\d+)*$/",$str);
}


function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)  {
  		if(function_exists("mb_substr")){
              if($suffix)  return mb_substr($str, $start, $length, $charset);
              else return mb_substr($str, $start, $length, $charset);
         } elseif(function_exists('iconv_substr')) {
             if($suffix) return iconv_substr($str,$start,$length,$charset);
             else   return iconv_substr($str,$start,$length,$charset);
         }
         $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]
                  [x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
         $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
         $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
         $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
         preg_match_all($re[$charset], $str, $match);
         $slice = join("",array_slice($match[0], $start, $length));
         if($suffix) return $slice;
         return $slice;

}
function listAllFiles($dir=""){
	if(is_dir($dir)){
		if($handle=opendir($dir)){
			while(false!==($file=readdir($handle))){
				if($file!="."&&$file!=".."){
					if(is_dir($dir."/".$file)){
						$files[$file]=listAllFiles($dir.$file);
					}else{
						$tem = str_replace(".",'',$dir);
						$files[$tem."/".$file]=$file;
					}
				}
			}
			closedir($handle);
		}
	}
	return $files;
}

function get_array_ids($arr,$field){
	$tem = array();
	foreach($arr as $v){
		if(!empty($v[$field])){
			$tem[$v[$field]] = $v[$field];
		}
	}
	return join(',',$tem);
}
function get_array_val($arr,$name,$def=null){
	if(empty($arr) || empty($name) || ! is_array($arr)){
		return $def;
	}
	if(isset($arr[$name])){
		return $arr[$name];
	}
	return $def;
}
function rand_keys($length=12){
 $pattern='12abcde34EFPQRvwxNOuhijklmAy56DzfgSTUVWXYZ7890nopqrstGHIJKLOMNOuhijklmABC';
 $key='';
 for($i=0;$i<$length;$i++){
  $key .= $pattern{mt_rand(0,35)};
 }
 return $key;
}
function get_base32_key($length = 12) {
		$b32     = "234567QWERTYUIOPASDFGHJKLZXCVBNM";
		$s     = "";
		for ($i = 0; $i < $length; $i++)
				$s .= $b32[rand(0,31)];
		return $s;
}

function deep_in_array($value, $array) {
    foreach($array as $item) {
        if(!is_array($item)) {
            if ($item == $value) {
                return true;
            } else {
                continue;
            }
        }

        if(in_array($value, $item)) {
            return true;
        } else if(deep_in_array($value, $item)) {
            return true;
        }
    }
    return false;
  }
/**
 * 获取当前页面完整URL地址
 */
function get_url() {
    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
function get_query_str($name,$value){
	$qstr = $_SERVER["QUERY_STRING"];
	if(!isset($qstr) || $qstr==''){
		return '?'.$name.'='.$value;
	}
	$qstr=preg_replace('/(^|&)'.$name.'(=?)([\w%]*)|($)/','',$qstr);
	if($qstr==''){
		return '?'.$name.'='.$value;
	}
	return '?'.$qstr.'&'.$name.'='.$value;
}

function remove_tags($str){
	return preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/", "", strip_tags($str));
}

function array_key_default($keys,& $arr,$def='0'){
	if(! is_array($keys)){
		$keys = array($keys);
	}
	foreach($keys as $v){
		if(!array_key_exists($v,$arr)){
			$arr[$v] = '0';
		}
	}
}
function initials($name){
	$new_name='';
	$nword = explode(" ",$name);
	foreach($nword as $k=>$v){
		$new_name.=$v[0];
	}
    return $new_name;
}
function service_img_init($path,$domail,$w,$h,$def=''){
	if( strpos($path, 'http://') !== false ){
		return $path;
	} else if( strpos($path, '/temp/') !== false ){
		return $domail.$path;
	} else if( strpos($path, '/upload/') === false ){
		return $def;
	}

	$npath = str_ireplace('/upload/', '/upload/temp/', $path);
	$tems = $ls=explode(".",$npath);
	if(count($tems) != 2)return $domail.$path;
	return $domail.$tems[0] . '___' . $tems[1] . '___' . $w . '_' .$h . '.' . $tems[1];
	///upload/temp/news/201612121212___jpg___300_300.jpg
}
function i_array_column($input, $columnKey, $indexKey=null){
	if(!function_exists('array_column')){
		$columnKeyIsNumber  = (is_numeric($columnKey))?true:false;
		$indexKeyIsNull            = (is_null($indexKey))?true :false;
		$indexKeyIsNumber     = (is_numeric($indexKey))?true:false;
		$result                         = array();
		foreach((array)$input as $key=>$row){
			if($columnKeyIsNumber){
				$tmp= array_slice($row, $columnKey, 1);
				$tmp= (is_array($tmp) && !empty($tmp))?current($tmp):null;
			}else{
				$tmp= isset($row[$columnKey])?$row[$columnKey]:null;
			}
			if(!$indexKeyIsNull){
				if($indexKeyIsNumber){
					$key = array_slice($row, $indexKey, 1);
					$key = (is_array($key) && !empty($key))?current($key):null;
					$key = is_null($key)?0:$key;
				}else{
					$key = isset($row[$indexKey])?$row[$indexKey]:0;
				}
			}
			$result[$key] = $tmp;
		}
		return $result;
	}else{
		return array_column($input, $columnKey, $indexKey);
	}
}
/**
* 把数字1-1亿换成汉字表述，如：123->一百二十三
* @param [num] $num [数字]
* @return [string] [string]
*/

function numToWord($num)
{
    $chiNum = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
    $chiUni = array('','十', '百', '千', '万','十', '百', '千', '亿', '十', '百','千','万','十', '百', '千');
    $uniPro = array(4, 8);
    $chiStr = '';


    $num_str = (string)$num;

    $count = strlen($num_str);
    $last_flag = true; //上一个 是否为0
    $zero_flag = true; //是否第一个
    $temp_num = null; //临时数字
    $uni_index = 0;

    $chiStr = '';//拼接结果
    if ($count == 2) {//两位数
        $temp_num = $num_str[0];
        $chiStr = $temp_num == 1 ? $chiUni[1] :                  $chiNum[$temp_num].$chiUni[1];
        $temp_num = $num_str[1];
        $chiStr .= $temp_num == 0 ? '' : $chiNum[$temp_num];
    }else if($count > 2){
        $index = 0;
        for ($i=$count-1; $i >= 0 ; $i--) {
            $temp_num = $num_str[$i];
            if ($temp_num == 0) {
                $uni_index = $index%15;
                if ( in_array($uni_index, $uniPro)) {
                    $chiStr = $chiUni[$uni_index]. $chiStr;
                    $last_flag = true;
                }else if (!$zero_flag && !$last_flag ) {
                   $chiStr = $chiNum[$temp_num]. $chiStr;
                   $last_flag = true;
                }
            }else{
                $chiStr = $chiNum[$temp_num].$chiUni[$index%16] .$chiStr;

               $zero_flag = false;
               $last_flag = false;
            }
           $index ++;
         }
    }else{
        $chiStr = $chiNum[$num_str[0]];
    }
    return $chiStr;
}
function LinkCss($url){
	$cssArray=listAllFiles($url.'css');
	if(empty($cssArray)) return false;
	$csHtml='';

	foreach( array_reverse($cssArray) as $k=>$v){
		$csHtml.='<link type="text/css" rel="stylesheet" href="'.$k.'?v='.rand(1,9).'">';

	}

	return $csHtml;
}
function LinkJS($url){
	$JSArray=listAllFiles($url.'js');

	if(empty($JSArray)) return false;
	$JShtml='<script src="/static/common/jquery-1.10.2.min.js" charset="utf-8"></script>';
	foreach($JSArray as $k=>$v){
		$JShtml.='<script src="'.$k.'?v='.rand(1,9).'"></script>';
	}
	$JShtml.='<script src="/static/common/jquery.validate.js" charset="utf-8"></script>';
	$JShtml.='<script src="/static/common/layer2.2/layer.js" charset="utf-8"></script>';
	$JShtml.='<script src="/static/index/js/style.js" charset="utf-8"></script>';
	return $JShtml;
}
function isweek($date){
	$weekarray=array("日","一","二","三","四","五","六");
	return '星期'.$weekarray[date('w',strtotime($date))];
}
function dateTodate($date){
	return date('m-d',strtotime($date));
}
/**
 *  将excel数据装换为数组
 *
 */
function format_excel2array($filePath,$sheet=0){
	if(empty($filePath) or !file_exists($filePath)) return false;

	vendor("PHPExcel.PHPExcel");
	$PHPReader = new \PHPExcel_Reader_Excel2007();
	if(!$PHPReader->canRead($filePath)){

			$PHPReader = new \PHPExcel_Reader_Excel5();
			if(!$PHPReader->canRead($filePath)){
					return false;
			}
	}
	$data = array();
	$cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
	$PHPExcel = $PHPReader->load($filePath);  //建立excel对象
	$currentSheet = $PHPExcel->getSheet($sheet);   //获取指定的sheet表
	$allColumn = $currentSheet->getHighestColumn();   //取得最大的列号
	$columnCnt = array_search($allColumn, $cellName);
	$allRow = $currentSheet->getHighestRow();   //获取总行数
	$currentColumn='A';
	for($_row=1; $_row<=$allRow; $_row++){  //读取内容
		for($_column=0; $_column<=$columnCnt; $_column++){
			 $cellId = $cellName[$_column].$_row;
		  	$cellValue = $currentSheet->getCell($cellId)->getCalculatedValue();
			if($cellValue instanceof PHPExcel_RichText){   //富文本转换字符串
				$cellValue = $cellValue->__toString();
			}
			$data[$_row][$cellName[$_column]] = $cellValue;
		}
	}
	return $data;
}
function IntToString(int $num){
	vendor("PHPExcel.PHPExcel");
	return  \PHPExcel_Cell::stringFromColumnIndex($num);
}

/**
 * 核实数组  数组格式['xx','xxxx'];
 * 传入文件地址
 * 传入当前入库名
 * 传入入库数组 前面数据库  后面数组['xx'=>'xxx']
 * 传入哪个字段不能为空 为空贼去掉数组  也可以是数组['xx','xx']
 * 传入是否多增加字段 格式['add'=>'username','row'=>'username','length'=>6,'hash'=>md5]
 */
function uploadFileToData($inarray,$fileurl,$db,$indbarray,$isnotnull=null,$ismore=null,$ischeckheader=true){

	if($ischeckheader && !is_array($inarray) && empty($inarray)) return false;
	vendor("PHPExcel.PHPExcel");
	set_time_limit(0);
	ini_set("memory_limit", "1024M");
	$excel = new \PHPExcel();
	$array=format_excel2array($fileurl);
	// print_r(trimToArray($inarray));

	// print_r( trimToArray(array_merge(array_filter(array_values($array[1])))));
	if((trimToArray($inarray) == trimToArray(array_merge(array_filter(array_values($array[1])))))  == false) return false;

	$keys=array_keys($indbarray);
	$values=array_values($indbarray);
	foreach($array as $k=>$v){
		if($k == 1 || empty($v)) continue;
		foreach($keys as $key=>$value){
			$m[$k][$value]=$v[$values[$key]];
		}
	}
	if(!is_null($isnotnull)){
		if(!is_array($isnotnull)){
			foreach($m as $k=>$v){
				if(empty($m[$k][$isnotnull]))  unset($m[$k]);
			}
		}else{
			foreach($isnotnull as $k=>$v){
				foreach($m as $key=>$value){
					if(empty($m[$key][$v])) unset($m[$key]);
				}
			}
		}
	}
	if(!is_null($ismore)){
		foreach($m as $k=>$v){
			$m[$k][$ismore['add']]=$ismore['hash'](msubstr($v[$ismore['row']],-6,$ismore['length']));
		}
	}
	return array_merge($m);

}
function dataToFile(Array $headerArr,Array $footerArr){
	if(empty($headerArr)) return false;
	vendor("PHPExcel.PHPExcel");
	$excel = new \PHPExcel();
	foreach($headerArr as $k=>$v) $excel->setActiveSheetIndex(0)->setCellValue(IntToString($k).'1',$v);

	$key=array_keys($footerArr[0]);
	foreach($footerArr as $k=>$v) {
		foreach($key as $keys=>$val){
			$excel->setActiveSheetIndex(0)->setCellValue(IntToString($keys).($i+2),$v[$val]);
		}
		$i++;
	}
	$excel->setActiveSheetIndex(0);
	$filename=urlencode(date('Ymd')."_".time());
	// header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	// header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
	// header('Cache-Control: max-age=0');
	$objWriter=\PHPExcel_IOFactory::createWriter($excel,'Excel2007');
	$objWriter = new \PHPExcel_Writer_Excel2007($excel);

  	return  trim(saveExcelToLocalFile($objWriter,$filename),'.');
}
function saveExcelToLocalFile($objWriter,$filename){
	$filePath = './upload/execl/'.$filename.'.xlsx';
	$objWriter->save($filePath);
	return $filePath;
}
function trimToArray($array){
	$newarray=[];
	foreach($array as $k=>$v){
		$newarray[$k]=trim($v);
	}
	return $newarray;
}
function encode($string = '', $skey) {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}
function decode($string = '', $skey) {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}
function sendMail($to, $title, $content) {
    Vendor('PHPMailer.PHPMailerAutoload');
    $mail = new PHPMailer(); //实例化
	$mail->IsSMTP(); // 启用SMTP
    $mail->Host=C('SYS_SET.MAIL_HOST'); //
    $mail->SMTPAuth = C('MAIL_SMTPAUTH');
    $mail->Username = C('SYS_SET.MAIL_USERNAME');
    $mail->Password = C('SYS_SET.MAIL_PASSWORD') ;
	$mail->From = C('SYS_SET.MAIL_FROM');
	$mail->SMTPSecure = "ssl";
	$mail->Port = 465;
	//$mail->IsSendmail();
    $mail->FromName = C('SYS_SET.MAIL_FROMNAME');
    $mail->AddAddress($to,$title);
    $mail->WordWrap = 100; //设置每行字符长度
    $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
    $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
    $mail->Subject =$title; //邮件主题
    $mail->Body =$content; //邮件内容
	$mail->AltBody = "请使用支持HTML的浏览器"; //邮件正文不支持HTML的备用显示

	return $mail->send() ? true : $mail->ErrorInfo;
}

function getMonthDays($date,$rtype=1){
	$tem=explode('-',$date);
	$days='';
	if(in_array($tem[1],[1,3,5,'07','08',10,12])) $days=31;
	else if($tem[1]===2 && ($tem[0]%400==0 || $tem[0]%4==0 || $tem[0]%100!==0)) $days=29;
	else if($tem[1]===2) $days=28;
	else $days=30;
	if($rtype == 2){
		$daysarr=[];
		for($i=1;$i<=$days;$i++){
			$daysarr[]=$i.'日';
		}
	}
	return $daysarr;
	// return $days;
}


if(!function_exists('dd')){
    function dd($data){
        echo '<pre/>';
        print_r($data);
        die;
    }
}



<?php
namespace Home\Controller;
use Think\Controller;
use Common\Libs\Util\Sms;
class RegController extends Controller {
    public function sendSms(){
        //获取手机号
        for($i=0;$i<6;$i++){
             sleep(6);
             echo "第{$i}个号";
             $mobile=Sms::GetNumber();
             $info=$this->msm($mobile);
             if($info===false) continue;
             $code=$this->findNum($info);
             if(strstr($mobile,$code) !==false) continue;
             else{
                 $data[$i]['phone']=$mobile;
                 $data[$i]['phone_check']=$code;
                 $data[$i]['email']='a'.$mobile;
                 $data[$i]['username']=$this->rand_strs(2,8);
                 $data[$i]['password']=$mobile;
                 $data[$i]['cpassword']=$mobile;
                 $data[$i]['pemail']=558843;
                 $data[$i]['ty']=1;
                 $this->curl_post_https('https://www.soso.cash/reg/regadd.html',$data[$i]);
             }
         }
         if(!empty($data)){
             M('mobile')->addAll(array_merge($data)) ;
         }
     }
     function findNum($str=''){
         $str=trim($str);
         if(empty($str)){return '';}
         $reg='/(\d{6}(\.\d+)?)/is';//匹配数字的正则表达式
         preg_match_all($reg,$str,$result); if(is_array($result)&&!empty($result)&&!empty($result[1])&&!empty($result[1][0])){
             return $result[1][0];
         }
         return '';
     }
     private function msm($mobile){
         for($i=0;$i<7;$i++){
             echo "第{$i}几次<br/>";
             sleep(6);
             $smsinfo=Sms::GetMessage($mobile,'搜搜拼单');
             var_dump($smsinfo);
             if(!empty($smsinfo)){
                return $smsinfo;
             }
         }
         return false;
     }
     private function curl_post_https($url,$data){ // 模拟提交数据函数
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
     public function addReg($phone='17157968859'){
         $data['phone']=$phone;
         $data['username']=$this->rand_strs(2,8);
         $data['email']='a'.$phone;
         $data['password']=$phone;
         $data['cpassword']=$phone;
         $data['phone_check']=557959;
         $data['ty']=1;
         $webUrl=$this->curl_post_https('https://www.soso.cash/reg/regadd.html',$data);
         var_dump($webUrl);
     }
     /**
      * Notes: 获取随机字符串
      * User: ZHL
      * Return : string
      */
     private function rand_strs($len=4, $type=0, $str=''){
         $newStr = ''; // 要获取的字符串
         if(preg_match("/[\x7f-\xff]/", $str) && $type != '8' && $type != '9'){ // 类型不为8,9并且存在中文字符时强制用汉字
             $str = '';
         }
         switch($type){ // 选定字符串类型
             case 1: // 纯数字
                 $str = '0123456789'.$str;
                 break;
             case 2: // 纯小写字母
                 $str = 'abcdefghijklmnopqrstuvwxyz'.$str;
                 break;
             case 3: // 纯大写字母
                 $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$str;
                 break;
             case 4: // 纯字母
                 $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.$str;
                 break;
             case 5: // 小写字母和数字
                 $str = 'abcdefghijklmnopqrstuvwxyz0123456789'.$str;
                 break;
             case 6: // 大写字母和数字
                 $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'.$str;
                 break;
             case 7: // 字母和数字
                 $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'.$str;
                 break;
             case 8: // 预生成汉字
                 $str = '大小多少左右上下白云太阳月亮星工人爸妈爷奶今天金木水火土红色衣花公母哭笑苦高兴吃玩乐打豆羊牛马车水电飞鸟东西南北方向'.$str;
                 break;
             case 9: // 自动生成汉字
                 for($i=0; $i<$len; $i++){
                     // 使用chr()函数拼接双字节汉字，前一个chr()为高位字节，后一个为低位字节
                     $strNo = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
                     $str .= iconv('GB2312', 'UTF-8', $strNo); // 转码
                 }
                 break;
             default :
                 // 默认，去掉了容易混淆的字母oOlZz和数字012
                 $str = 'abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXY3456789'.$str; 
                 break;
         }
         if($type != 9){ // 所需大于固定长度时
             $strLen = mb_strlen($str,'UTF8');
             if($len > $strLen) { // 位数过长重复字符串一定次数
                         $str = str_repeat($str,$len); 
                 }
         }
         if($type == 8 || $type == 9){ //汉字时
             // 计算最大长度-1
             $strLen = mb_strlen($str,'UTF8')-1;
             // 循环 $len 次获得字符串
             for($i=0;$i<$len;$i++){
                 $newStr .= mb_substr($str, floor(mt_rand(0,$strLen)),1,'UTF8'); //随机长度内数字，截取随机数向后一个长度
             } 
         }else{ // 普通字符串
             $newStr = substr(str_shuffle($str), 0, $len); // 字符串随机排序后截取$len长度
         }
         return $newStr;
     }
}
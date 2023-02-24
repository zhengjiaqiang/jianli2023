<?php


namespace Icenter\Model;
use Think\Model;
class ResumeuserModel extends Model {

	public function getDetail(string $id,string $uid){
        $config=$this->where(['isouttime'=>0,'cid'=>$id,'uid'=>$uid])->field('config,htmlconfig')->find();
        $config['config']=$this->parseToDecode($config['config']) ;
        $config['htmlconfig']=$this->BaseToString($config['htmlconfig']) ;
        return $config;
    }
    private function parseToDecode($config){
        parse_str(base64_decode($config),$user);

//        dd($user);
        foreach($user as $k=>$v){
            $user[$k]=htmlspecialchars_decode($v);
        }


        return $user;
    }
    private function BaseToString($config){
        return str_replace(array("\r\n", "\r", "\n"),'',htmlspecialchars_decode(base64_decode(($config),true)));
    }
    public function isDownResume($uid){
        //读取当前的简历配置
        $list=M('resumeconfig')->where(['status'=>1,'isdel'=>0,'type'=>278])->field('id,name')->select();
        foreach($list as $v){
            if(empty($this->where(['uid'=>$uid,'cid'=>$v['id']])->find())) return false;
        }
        return true;
    }


}

<?php


namespace Icenter\Model;
use Think\Model;
class ResumeconfigModel extends Model {
	public function getTitle(){
        return $this->where(['status'=>1,'type'=>278,'isdel'=>0])->order('sort desc')->field('id,name')->select();
    }
    public function getDetail(string $id){
        return $this->BaseToString($this->where(['status'=>1,'type'=>278,'isdel'=>0,'id'=>$id])->getField('formhtml')) ;
    }
    private function BaseToString($config){
        return str_replace(array("\r\n", "\r", "\n"),'',htmlspecialchars_decode(base64_decode(($config),true)));
    }
    public function getFirstId(){
        return $this->where(['status'=>1,'isdel'=>0])->order('sort desc')->getField('id');
    }
}

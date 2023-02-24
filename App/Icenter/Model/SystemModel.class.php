<?php


namespace Icenter\Model;
use Think\Model;
class SystemModel extends Model {
	public function initSet(){
        C('SYS_SET',$this->getField('name,value',true));
    }
}

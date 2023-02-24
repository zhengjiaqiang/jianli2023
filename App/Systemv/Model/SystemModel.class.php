<?php


namespace Systemv\Model;
use Think\Model;
class SystemModel extends Model {
	public function initSet(){
        C('SYS_SET',$this->getField('name,value',true));
    }
    /**
     *@param where 查询条件
     *@param order 排序规则
     *@return array
     */
    public function getSetList($where=[],$order='type desc,sort asc'){
       return  $this->where($where)->order($order)->select();
    }
    /**
     *@param update 更新数组
     *@return array
     */
    public function setValue(Array $update){
        foreach($update as $k=>$v){
            $this->where(['name'=>$k])->save(['value'=>$v]);
        }
    }
}

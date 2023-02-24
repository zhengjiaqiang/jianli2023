<?php


namespace Systemv\Model;

use Think\Model;
class WordModel extends Model {
	public function tree($where=[],$order='sort desc,id desc',$field='*'){
		$list=$this->where(array_merge(['status'=>1,'isdel'=>0]))->order($order)->field($field)->select();
		return $this->getTree($list);
	}
	public function getTree($list,$pid = 0,$level =0){
		static $tree=array();
			foreach ($list as $k=>$v){
				if($v['parentId'] == $pid){
					$v['level']=$level;
					$tree[]=$v;
					$this->getTree($list,$v['id'],$level + 1);
				}
			}
		return $tree;
	}
}
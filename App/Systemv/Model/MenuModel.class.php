<?php


namespace Systemv\Model;

use Think\Model;
class MenuModel extends Model {

	public function getDroplist($pid=0,$isinput=false,$where=null,$order=null,$field=null,$depth=0){
		$list = $this->readTreeArray($pid,$isinput,$where,$order,$field,$depth);
		foreach($list as $v){
			$drarr[$v['id']] = $v['name'];
		}
		return $drarr;
	}
	public function readTreeArray($pid=0,$isinput=false,$where=null,$order=null,$field=null,$depth=0){
		$re = $this->where($where)->order($order)->field($field)->select();
	
		if(empty($re)){
			return null;
		}
		
		$list = $this->getTreeToArray($re,$pid,$depth);
		if(empty($list)){
			return null;
		}
		foreach($list as &$v){
			$v['_name'] = $v['name'];
			if($isinput){
				$v['name'] = '<input name="listorders['.$v['id'].']" type="text" size="3" value="'.$v['sort'].'" class="input">&nbsp;' .$v['name'];
			}
			$tem='';
			for ($i = 0; $i < $v['dem_depth']; $i++) {
				$tem .= '&nbsp;&nbsp;&nbsp;'; 
			}
			if($tem != ''){$v['name'] = $tem.'├─'.$v['name'];}
			
		}unset($v);
		return $list;
	}
	private function getTreeToArray($arr,$pid,$depth,$loc=0){
		if($depth != 0 && $loc != $depth){
			return null;
		}
	    $tree = array();
	    $list = array();
		foreach($arr as $v){
			if($v['pid'] == $pid){
				array_push($list,$v);
			}
		}
		foreach($list as $v){
			//查找是否存在下一级
			$v['isonly']=$this->where(['pid'=>$v['id'],'status'=>1,'delete'=>0])->count();
			$v['dem_depth'] = $loc;
			array_push($tree,$v);
			$ltem = $this->getTreeToArray($arr,$v['id'],$depth,$loc+1);
			if(!empty($ltem)){
				foreach($ltem as $n){
					array_push($tree,$n);
				}
			}
		}
		return $tree;
	}

	public function setParentAndDepth($start,$id,$pid){

		if($id == $pid)return;
		$clist = $this->where("id=$start or depth like '%|$start|%'")->field('id,pid,depth')->select();
		$locObj = null;
		$npObj  = null;
		$depth = null;
		foreach($clist as $v){
			if($v['id'] == $id){
				$locObj = $v;
			}
			if($v['id'] == $pid){
				if(strpos($npObj['depth'],"|$id|") === false){
					$npObj = $v;			
				}else{return;}
			}
			if(!empty($locObj) && !empty($npObj))break;
		}
		if(empty($locObj)){
			return;
		}
		if(empty($npObj)){
			$depth = "|$start|";
		}else{
			$depth =  $npObj['depth'].$npObj['id'].'|';
		}
		$data = array('depth'=>$depth,'pid'=>$pid);

		$this->where("id=$id")->save($data);

		$this->setOneDepth($clist,$id,$depth);
	}
	private function setOneDepth($clist,$pid,$depth){
		foreach($clist as $v){
			if($v['pid'] == $pid){
				$id = $v['id'];
				$ndepth =$depth.$pid.'|';
				$data = array('depth'=>$ndepth);
				$this->where("id=$id")->save($data);
				$this->setOneDepth($clist,$id,$ndepth);
			}
		}

	}
}
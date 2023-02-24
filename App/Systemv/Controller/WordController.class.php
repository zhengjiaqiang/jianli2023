<?php
// +----------------------------------------------------------------------
// | I DO
// +----------------------------------------------------------------------
// | Copyright (c) 2016 All rights reserved.
// +----------------------------------------------------------------------
// | Author: 雪地里的松鼠
// +----------------------------------------------------------------------

namespace Systemv\Controller;
use Systemv\Common\BaseController;
class WordController extends BaseController{
	private $dbname='word';
	private $bll=null;
	private $id=null;
	private $powerArray=array();
	private $title=null;
	public function __construct(){
		parent::__construct();
		$this->bll=M($this->dbname);
		//获取当前
		$this->id=I('get.id/d',0);
		$this->title=$this->getTitle($this->id);
		$this->powerArray=$this->checkType($this->id);
		$this->checkType($this->id,true);
		$this->assign('title',$this->title);
		$this->assign('id',$this->id);
	}
	public function index(){
		if(!$this->check('select',$this->id)) $this->errorTypeMessage($this->roletype['select']['name']);
		$page=I('get.page/d',0);
		if(IS_AJAX){
			$re = $this->pageData($this->dbname,$page,$this->getWhere(),$this->getSort());
	
			exit(json_encode($re,true));
		}
		$this->display();
	}
	public function operate(){
		if(! IS_POST){exit();}
		$event = I('post.event');
		$uid = $this->userInfo['id'];
		$ids = I('post.chkItem');
		if(is_array($ids)) $ids = join(',',$ids);
		
		if($event == 'delete'){
			// if(!$this->check('delete',$id)) $this->errorTypeMessage($this->roletype['delete']['name']);
			$m['isdel']=1;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前计划删除失败，请刷新网络重试！');
			else{
				$this->addLog('delete','删除计划IDS：'.$ids);
				$this->success('当前计划删除成功！');
			}
		}else if($event  == 'okpass' || $event == 'nopass'){
			// if(!$this->check('pass',$id)) $this->errorTypeMessage($this->roletype['pass']['name']);
			$message=$event == 'okpass' ? '审核通过' : '审核拒绝'; 
			$m['status']=$event == 'okpass' ? 1 : 0;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前计划'.$message.'失败，请刷新网络重试！');
			else{
				$this->addLog('pass',$message.'计划IDS：'.$ids);
				$this->success('当前计划改变审核状态成功！');
			}
		}
	}
	/**
	 * 获取查询条件
	 */
	private function getWhere(){
		$map = array();
		$id=I('get.id/d',0);
		$map['typeid']=$id;
		$key = I('get.key');
		$status=I('get.status',-1);
		if(!empty($key)){
			$map['name'] = array('like',"%$key%");
			$this->assign('key',$key);
		}
		$map['isdel']=0;
		return $map;
	}
	/**
	 * 获取排序条件
	 */	
	private function getSort(){
		$sort = I('get.sort/d',-1);
		$str ='addtime desc,id desc';
		if($sort>=0){
			switch($sort){
				case 2:$str='id desc';
					break;
				case 1:$str='id asc';
					break;	
				case 8:$str='addtime desc';
					break;
				case 7:$str='addtime asc';
					break;
			}
			$this->assign('sort',$sort);
		}
		return $str;
	}
	public function edit(){
		if(!IS_POST) exit();
		$id=I('post.nodeId',0,'int');
		$pid=I('post.parentId/d');
		$m['name']=I('post.context');
		$m['value']=I('post.value');
		$m['sort']=I('post.sort/d');
		if(!$id){//添加
			//设置深度
			$depth=$this->bll->where(['status'=>1,'isdel'=>0,'id'=>$pid])->getField('depth');
			$m['depth']=!empty($depth) ? $depth.$pid.'|' : '|'.$pid.'|';
			$m['pid']=$pid;
			$m['status']=1;
			$rs=$this->bll->add($m);
		}else $rs=$this->bll->where(['id'=>$id])->save($m);
		if($rs === false) $this->error('当前设置修改失败，请检查网络设置');
		else{
			$this->addLog('update','修改当前设置成功:'.$id);
			$this->success('当前设置修改成功！');
		}
	}
	public function delete(){
		if(!IS_POST) exit();
		$id=I('post.nodeId/d');
		//读取当前是否存在下级
		$count=$this->bll->where(['status'=>1,'isdel'=>0,'depth'=>array('like',"%|$id|%")])->count();
		if($count) $this->error('当前设置删除失败：当前的配置下存在未删除的子栏目！');
		$rs=$this->bll->where(['id'=>$id])->save(['isdel'=>1]);
		if($rs === false) $this->error('当前设置删除失败，请检查网络设置');
		else{
			$this->addLog('delete','删除当前设置成功:'.$id);
			$this->success('当前设置删除成功！');
		}
	}
	public function tree(){
		$list=$this->bll->where(array_merge(['status'=>1,'isdel'=>0]))->order('sort desc,id desc')->field('id,name as title,pid as parentId')->select();
		$lists=D('word')->getTree($list);
		$array=array(
			'status'=>['code'=>200,'message'=>'数据加载成功!'],
			'data'=>$lists
		);
		exit(json_encode($array));
	}
	public function uedit(){
		$id=I('get.id/d');
		$this->assign('info',$this->bll->where(['status'=>1,'isdel'=>0,'id'=>$id])->find());
		$this->display();
	}
}
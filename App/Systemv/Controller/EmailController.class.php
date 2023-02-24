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
class EmailController extends BaseController{
	private $dbname='email';
	private $bll=null;
	private $id=null;
	private $powerArray=array();
	private $title=null;
	public function __construct(){
		parent::__construct();
		$this->bll=M($this->dbname);
		$this->id=I('get.id/d',0);
		$this->title=$this->getTitle($this->id);
		$this->powerArray=$this->checkType($this->id);
		$this->checkType($this->id,true);
		$this->assign('title',$this->title);
		$this->assign('id',$this->id);
	}
	public function index(){
		if(!$this->check('select',$this->id)) $this->errorTypeMessage($this->roletype['select']['name']);
		$page=I('get.p/d',0);
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
		if( is_array($ids)){
			$ids = join(',',$ids);
		}
		if($event == 'delete'){
			// if(!$this->check('delete',$id)) $this->errorTypeMessage($this->roletype['delete']['name']);
			$m['isdel']=1;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前模板删除失败，请刷新网络重试！');
			else{
				$this->addLog('delete','删除模板IDS：'.$ids);
				$this->success('当前模板删除成功！');
			}
		}else if($event  == 'okpass' || $event == 'nopass'){
			// if(!$this->check('pass',$id)) $this->errorTypeMessage($this->roletype['pass']['name']);
			$message=$event == 'okpass' ? '审核通过' : '审核拒绝'; 
			$m['status']=$event == 'okpass' ? 1 : 0;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前模板'.$message.'失败，请刷新网络重试！');
			else{
				$this->addLog('pass',$message.'模板IDS：'.$ids);
				$this->success('当前模板改变审核状态成功！');
			}
		}
	}
	/**
	 * 获取查询条件
	 */
	private function getWhere(){
		$map = [];
		$id=I('get.id/d',0);
		$map['isdel']=0;
		return $map;
	}
	/**
	 * 获取排序条件
	 */	
	private function getSort(){
		$sort = I('get.sort/d',-1);
		$str ='id desc';
		return $str;
	}
	public function edit(){
		$id=I('get.id/d');
		$pid=I('get.pid/d',0);
		if(IS_POST){
			$id=I('post.id/d',0);
			$pid=I('post.pid/d',0);
			$m=I('post.');unset($m['id']);unset($m['pid']);
			if($pid){
				if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				$info=$this->bll->where(array('id'=>$pid,'isdel'=>0))->find();
				if(empty($info)) $this->error('当前模板不存在或被删除');
				$rs=$this->bll->where(array('id'=>$pid))->save($m);
				if($rs == false) $this->error('当前模板修改失败，请刷新重试！');
				else{
					$this->addLog('update','修改模板ID：'.$pid);
					$this->success('修改成功');
				}
			}else{
				if(!$this->check('add',$id)) $this->errorTypeMessage($this->roletype['add']['name']);
				$m['uid']=$this->userId;
				$m['addtime']=time();
				$sid=$this->bll->add($m);
				if($sid === false) $this->error('当前模板添加失败，请刷新重试！');
				else{
					$this->addLog('add','添加模板ID：'.$id);
					$this->success('添加模板成功');
				}
			}
		}
		if($pid > 0){
			if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
			$info=$this->bll->where(array('id'=>$pid))->find();
			$this->assign('info',$info);
		}
		$this->assign('pid',$pid);
		$this->assign('id',$id);
		$this->display();
	}
}
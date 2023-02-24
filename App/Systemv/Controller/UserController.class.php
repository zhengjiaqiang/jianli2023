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
class UserController extends BaseController{
	private $dbname='usernumber';
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
		$page=I('get.page/d',0);
		if(IS_AJAX){
			$re = $this->pageData($this->dbname,$page,$this->getWhere(),$this->getSort());
			exit(json_encode($re,true));
		}
		$this->display();
	}
	/**
	 * 获取查询条件
	 */
	private function getWhere(){
		$map = array();
		$id=I('get.id/d',0);
		$key = I('get.key');
		if(!empty($key)){
			$map['number|nickname'] = array('like',"%$key%");
			$this->assign('key',$key);
		}
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
			$m=$this->bll->create($m);
			if($pid){
				if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				if($this->_isset($m['number'],['id'=>['neq',$pid]])) $this->error('当前专家账号已经存在！');
				$info=$this->bll->where(array('id'=>$pid))->find();
				if(empty($info)) $this->error('当前专家不存在或被删除');
				$rs=$this->bll->where(array('id'=>$pid))->save($m);
				if($rs == false) $this->error('当前专家修改失败，请刷新重试！');
				else{
					$this->addLog('update','修改专家ID：'.$pid);
					$this->success('修改成功',U('/Systemv/user/index/',array('id'=>$id)));
				}
			}else{
				if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				if($this->_isset($m['number'])) $this->error('当前专家账号已经存在！');
				$m['lasttime']=$m['addtime']=time();
				$sid=$this->bll->add($m);
				if($sid === false) $this->error('当前专家添加失败，请刷新重试！');
				else{
					$this->addLog('add','添加专家ID：'.$id);
					$this->success('添加成功',U('/Systemv/user/index/',array('id'=>$id)));
				}
			}
		}
		if($pid > 0){
			if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
			$info=$this->bll->where(array('id'=>$pid))->find();
			$this->assign('info',$info);
		}
		$this->display();
	}
	private function _isset($number,array $where=[]){
		return $this->bll->where(array_merge(['number'=>$number],$where))->count();
	}
}
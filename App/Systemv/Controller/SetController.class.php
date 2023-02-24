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
class SetController extends BaseController{
	private $dbname='system';
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
	
		//$this->checkType($this->id,true);
		$this->assign('title',$this->title);
		$this->assign('id',$this->id);
	}
	public function index(){
		$this->assign('mlist',D('system')->getSetList());
		$this->display();
	}

	public function edit(){
		// if(!$this->check('update',$this->id)) $this->errorTypeMessage($this->roletype['select']['name']);
		$id=I('get.id/d');
		$pid=I('get.pid/d',0);
		if(IS_POST){
			$m=I('post.');unset($m['pid']);unset($m['id']);
			D('system')->setValue($m);
			$this->addLog('update','修改了系统配置');
			$this->success('修改了系统配置成功,正在重新加载');
		}
		$this->assign('type',M('menu')->where(array('id'=>$id))->getField('type'));
		$this->assign('pid',$pid);
		$this->display();
	}
}
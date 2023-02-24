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
class FormController extends BaseController{
	private $dbname='resumeconfig';
	private $name='topic';
	private $bll=null;
	private $id=null;
	private $powerArray=array();
	private $title=null; 
	public function __construct(){
		parent::__construct();
		$this->bll=M($this->dbname);
		//获取当前
		$this->id=I('param.id/d',0);
		// $status=M($this->name)->where(['status'=>1,'isdel'=>0,'id'=>$this->id])->find();
		// if(empty($status)) $this->error('当前的被删除或未审核！');
		$this->checkType($this->id,true);
		$this->assign('title','初始化简历');
		$this->assign('id',$this->id);
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
			$m['isdel']=1;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			
			if($rs === false) $this->error('当前配置申请删除失败，请刷新网络重试！');
			else{
				$this->addLog('delete','删除配置申请IDS：'.$ids);
				$this->success('当前配置申请删除成功！');
			}
		}else if($event  == 'okpass' || $event == 'nopass'){
			$message=$event == 'okpass' ? '审核通过' : '审核拒绝'; 
			$m['status']=$event == 'okpass' ? 1 : 0;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前配置申请'.$message.'失败，请刷新网络重试！');
			else{
				$this->addLog('pass',$message.'配置申请IDS：'.$ids);
				$this->success('当前配置申请改变审核状态成功！');
			}
		}else if($event == 'sort'){
			$listorders = I('post.sort');
			$ids=',';
            foreach ($_POST['sort'] as $id => $listorder) {
            	$ids.= $id = (int) $id;
            	$listorder = (int) $listorder;
                $this->bll->where(array('id' => $id))->save(array('sort' => $listorder));
            }
			$this->addLog('sort',$this->title.'排序IDS:'.$ids);
			$this->success('编号为“'.$ids.'”排序成功');
		}
	}

	/**
	 * 获取查询条件
	 */
	private function getWhere(){
		$map = array();
		$key = trim(I('get.key'));
		if(!empty($key)) $map['name'] = array('like',"%$key%");
		$map['isdel']=0;
		$map['type']=$this->id;
		
		return $map;
	}
	/**
	 * 获取排序条件
	 */	
	private function getSort(){
		$str ='sort desc,id desc';
		return $str;
	}
	public function edit(){
		$id=I('get.id/d');
		$pid=I('get.pid/d',0);
		if(IS_POST){
			$pid=I('post.pid/d',0);
			$m=I('post.');
			$type=$m['id'];
			unset($m['id']);unset($m['pid']);
			$m['status']=I('post.status/d',0);
			$m=$this->bll->create($m);
			$m['type']=$type;
			if($pid){
				$rs=$this->bll->where(array('id'=>$pid))->save($m);
				if($rs === false) $this->error('当前配置修改失败，请刷新重试！');
				else {
					$this->addLog('update','修改配置申请ID：'.$pid);
					$this->success('当前配置修改成功！');
				}
			}else{
				$m['tid']=$this->id;
				$m['uid']=$this->userInfo['id'];
				$m['isdel']=0;
				$m['addtime']=time();
				$id=$this->bll->add($m);
				if($rs) $this->error('当前配置添加失败，请刷新重试！');
				else{
					$this->addLog('add','添加配置申请ID：'.$id);
					$this->success('当前配置添加成功！');
				}
			}
		}
		if($pid){
			$info=$this->bll->where(array('id'=>$pid))->find();
			$this->assign('info',$info);
		}
		$this->assign('pid',$pid);
		$this->display(); 
	}
	public function set(){
		$pid = I('get.pid/d');
		//读取当前的
		$this->assign('html',preg_replace('/\'/', '"',str_replace(array("\r\n", "\r", "\n"),'',htmlspecialchars_decode(base64_decode(($this->bll->where(['id'=>$pid])->getField('formhtml')),true))))) ;
		$this->assign('pid',$pid);
		$this->display();
	}
	public function index(){
		if(IS_AJAX){
			$page=I('get.page/d',0);
			$re = $this->pageData($this->dbname,$page,$this->getWhere(),$this->getSort());
			exit(json_encode($re,true));
		}
		$this->assign('pid',I('get.pid/d'));
		$this->display();
	}
	public function save(){
		if(!IS_POST) exit();
		$m=I('post.');
		$pid=$m['pid'];
		// print_r($m);die;
		// $data['formconfig']=base64_encode($m['node']);
		$data['formhtml']=base64_encode($m['h']);
		M('resumeuser')->where(['cid'=>$pid])->save(['htmlconfig'=>base64_encode($m['h'])]);
		$rs = $this->bll->where(['tid'=>$this->id,'id'=>$pid])->save($data);
		if($rs === false) $this->error('当前的文档配置失败，请联系管理员');
		else $this->success('当前文档配置成功');
	}
	public function answer(){
		$this->display();
	}
}
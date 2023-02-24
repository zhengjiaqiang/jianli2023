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
class MenuController extends BaseController {
	private $dbname=null;
	protected $onePageRow    = 1500;
	public function __construct(){
		parent::__construct();
		$this->dbname='menu';
		$this->bll=M($this->dbname);
		$this->id=I('get.id/d',0);
		$this->title=$this->getTitle($this->id);
		$this->powerArray=$this->checkType($this->id);
		$this->assign('title',$this->title);
		$this->assign('id',$this->id);
		
	}
	public function index(){	

		
		$page = I('get.page/d','1');
		if(IS_AJAX){
			$bll  = D($this->dbname);
			$list = $bll->readTreeArray(0,false,$this->getWhere(),$this->getSort());
			$re['aaData'] = empty($list) ? array() : $list;
			exit(json_encode($re,true));
		}
		if(!$this->check('select',$this->id)) $this->errorTypeMessage($this->roletype['select']['name']);
		$this->display();
	}
	private function getWhere(){
		$map=array();
		$map['delete']=0;
		$map['isdep']=$this->depId;
		$map['ifdel']=0;
		return $map;
	}
	private function getSort(){
		return 'sort desc,id desc';
	}
	public function edit(){
		$id=I('get.id/d',0);
		$pid=I('get.pid/d',0);
		if(IS_POST){
			$id=I('post.id/d',0);
			$pid=I('post.parent_id/d',0);
			$parid=I('post.pid/d',0);
			$m['pid']=$pid;
			$m['name']=I('post.title','');
			if(empty($m['name'])) $this->error('必要条件缺失!');
			$m['islink']=I('post.islink/d',0);
			if(!empty($m['islink'])) $m['link']=I('post.link','');
			$m['status']=I('post.status/d',0);
			$m['sort']=I('post.msort/d',0);
			$m['type']=I('post.type/d',0);
			$m['index']=I('post.index/d',0);
			$m['iszhuanye']=I('post.iszhuanye/d',0);
			$m['index']=I('post.index/d',0);
			$m['phone']=I('post.phone/d',0);
			$m['mtid']=I('post.mtid/d',0);
			$m['oldid']=I('post.oldid/d',0);
			$m=M($this->dbname)->create($m);
			$opid=I('post.opid/d',0);
			if($parid){//修改 
				if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				if(M($this->dbname)->where(array('id'=>$parid,'ifdel'=>0))->save($m) === false)
					$this->error("修改失败，您可以重新尝试提交");
				else{
					if($npid !=$pid){
						D($this->dbname)->setParentAndDepth(0,$parid,$pid);
					}
					if($m['type'] != 0){
						$mtype=M('menutype')->where(['id'=>$m['type']])->getField('mtype');
						unset($m);
						$mt['mid']=$parid;
						$mt['type']=json_encode($this->signle[$mtype]);
						M('menupower')->where(['mid'=>$parid])->delete();
						M('menupower')->add($mt);
						//查询是否存在
						$pm['mid']=$parid;
						$pm['power']=json_encode($this->signle[$mtype]);
						M('permission')->where(['mid'=>$parid,'rid'=>1])->delete();
						$pm['rid']=1;
						
						$isadd=M('permission')->add($pm);
					}
					$this->addLog('update','修改编号为：'.$parid.'的栏目修改成功');
					$back = I('get.url');
					$this->success($this->ptitle.'修改成功', empty($back) ? U(CONTROLLER_NAME.'/index') : $back);
				}
			}else{
				//添加 
				if(!$this->check('add',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				$m['addtime']=time();
				$m['adduid']=$this->userId;
				$m['isdep']=$this->depId;
				if($pid){
					$pm = M($this->dbname)->where(array('id' =>$pid))->find();
					if(empty($pm)) $this->error('上级栏目不存在！');
					$m['depth'] = $pm['depth'].$pid.'|';
				}else $m['depth'] ='|0|';
				$sid = M($this->dbname)->add($m);
				//添加的时候 将所有的超级管理员自动加上权限    id 分别位1   4 
				if($sid){
					if($m['type'] != 0 ){
						$mtype=M('menutype')->where(['id'=>$m['type']])->getField('mtype');
						unset($m);
						$mt['mid']=$sid;
						$mt['type']=json_encode($this->signle[$mtype]);
						M('menupower')->add($mt);
						//查询是否存在
						$pm['mid']=$sid;
						$pm['power']=json_encode($this->signle[$mtype]);
						$pm['rid']=1;
						$isadd=M('permission')->add($pm);
					}
					$this->addLog('add','新增栏目,编号为：'.$id);
					$this->success('当前栏目以及权限配置成功，如需使用请前往权限分配');
				}else{
					$this->error("新增栏目失败，您可以重新尝试提交");
				}
			}
		}
		//读取一级栏目 递增查询出所有的栏目
		$list=D('menu')->getDroplist(0,false,array('delete'=>0,'status'=>1,'isdep'=>$this->depId),'sort desc,id asc','id,name,pid,status');
		if($pid){
		  if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
		  $info=M($this->dbname)->where(array('id'=>$pid))->find();
		  $info['power']=M('menupower')->where(array('mid'=>$pid))->getField('type,mid');
		  $this->assign('info',$info);
		}
		$this->assign('list',$list);
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
			$m['delete']=1;
			$m['ifdel']=0;
			$where['id']=array('in',$ids);
			$rs=M($this->dbname)->where($where)->save($m);
			if($rs === false) $this->error('当前栏目删除失败，请刷新网络重试！');
			else{
				//删除栏目权限 以及 用户所属栏目权限
				M('menupower')->where(array('mid'=>array('in',$ids)))->delete();
				M('permission')->where(array('mid'=>array('in',$ids)))->delete();
				$this->addLog('delete','删除栏目IDS：'.$ids);
				$this->success('当前栏目删除成功！');
			}
		}else if($event  == 'okpass' || $event == 'nopass'){
			// if(!$this->check('pass',$id)) $this->errorTypeMessage($this->roletype['pass']['name']);
			$message=$event == 'okpass' ? '审核通过' : '审核拒绝'; 
			$m['status']=$event == 'okpass' ? 1 : 0;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前'.$message.'失败，请刷新网络重试！');
			else{
				$this->addLog('pass',$message.'IDS：'.$ids);
				$this->success('当前改变审核状态成功！');
			}
		}else if($event  == 'oktop' || $event == 'notop'){
			// if(!$this->check('top',$id)) $this->errorTypeMessage($this->roletype['top']['name']);
			$message=$event == 'oktop' ? '置顶' : '取消置顶'; 
			$m['istop']=$event == 'oktop' ? 1 : 0;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前'.$message.'失败，请刷新网络重试！');
			else{
				$this->addLog('pass',$message.'新闻IDS：'.$ids);
				$this->success('当前改变审核状态成功！');
			}
		}else if($event == 'sort'){
			$listorders = I('post.listorders');
            $bll =M('menu');
            foreach ($_POST['listorders'] as $id => $listorder) {
            	$id = (int) $id;
            	$listorder = (int) $listorder;
                $bll->where(array('id' => $id))->save(array('sort' => $listorder));
            }
			$this->addLog('sort','栏目排序IDS:'.$ids);
			$this->success('编号为“'.$ids.'”排序成功');
		}
	}
}
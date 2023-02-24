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
class NewsController extends BaseController{
	private $dbname='news';
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
		if(!$this->check('select',$this->id)) $this->errorTypeMessage($this->roletype['select']['name']);
		$page=I('get.page/d',0);
		if(IS_AJAX){
			$re = $this->pageData($this->dbname,$page,$this->getWhere(),$this->getSort());
			exit(json_encode($re,true));
		}
		$this->display();
	}
	public function info(){
		if(IS_POST){
			$id=I('post.id/d',0);
			$info=M('info')->where(array('pid'=>$id))->find();
			$m=I('post.');unset($m['id']); 
			$m['content']=$m['content'];
			if(!empty($info)){
				if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				$rs=M('info')->where(array('pid'=>$id))->save($m);
				if($id === false) $this->error('修改'.$this->title.'失败，请刷新重试！');
				else{
					$this->addLog('update','修改'.$this->title.'成功！');
					$this->success('修改'.$this->title.'成功！');
				}
			}else{
				if(!$this->check('add',$id)) $this->errorTypeMessage($this->roletype['add']['name']);
				$m['adder']=$this->userInfo['id'];
				$m['addtime']=time();
				$m['pid']=$id;
				$id=M('info')->add($m);
				if($id === false) $this->error('添加'.$this->title.'失败，请刷新重试！');
				else{
					$this->addLog('add','添加'.$this->title.'成功！');
					$this->success('添加'.$this->title.'成功！');
				}
			}
		}
		if(!$this->check('select',$this->id)) $this->errorTypeMessage($this->roletype['select']['name']);
		$info=M('info')->where(array('pid'=>$this->id))->find();
		$this->assign('info',$info);
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
			$m['isdel']=1;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前新闻删除失败，请刷新网络重试！');
			else{
				$this->addLog('delete','删除新闻IDS：'.$ids);
				$this->success('当前新闻删除成功！');
			}
		}else if($event  == 'okpass' || $event == 'nopass'){
			$message=$event == 'okpass' ? '审核通过' : '审核拒绝'; 
			$m['status']=$event == 'okpass' ? 1 : 0;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前新闻'.$message.'失败，请刷新网络重试！');
			else{
				$this->addLog('pass',$message.'新闻IDS：'.$ids);
				$this->success('当前新闻改变审核状态成功！');
			}
		}else if($event  == 'oktop' || $event == 'notop'){
			$message=$event == 'oktop' ? '置顶' : '取消置顶'; 
			$m['istop']=$event == 'oktop' ? 1 : 0;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前新闻'.$message.'失败，请刷新网络重试！');
			else{
				$this->addLog('pass',$message.'新闻IDS：'.$ids);
				$this->success('当前新闻改变审核状态成功！');
			}
		}else if($event == 'sort'){
			$listorders = I('post.sort');
            $bll =M('news');
			$ids=',';
            foreach ($_POST['sort'] as $id => $listorder) {
            	$ids.= $id = (int) $id;
            	$listorder = (int) $listorder;
                $bll->where(array('id' => $id))->save(array('sort' => $listorder));
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
		$id=I('get.id/d',0);
		$map['typeid']=array('like','%|'.$id.'|%');
		$key = I('get.key');
		$status=I('get.status',-1);
		if(!empty($key)){
			$map['title'] = array('like',"%$key%");
			$this->assign('key',$key);
		}
		$start = I('get.start');
		if(!empty($start)){ 
			$map['addtime'] = array('egt',strtotime($start));
			$this->assign('start',$start);
		}
		if($status > -1 ){
			$map['status']=$status;
		}
		$end = I('get.end');
		if(!empty($end)){$end = $end .' 24:00:00';
			if(isset($map['addtime'])){
				$map['addtime'] =array($map['addtime'],array('elt',strtotime($end)));
			}else{
				$map['addtime'] =array('elt',strtotime($end));
			}
			$this->assign('end',$end);
		}
		$map['isdel']=0;
		$map['isdep']=$this->depId;
		return $map;
	}
	/**
	 * 获取排序条件
	 */	
	private function getSort(){
		$sort = I('get.sort/d',-1);
		$str ='istop desc,sort desc,ownertime desc,id desc';
		if($sort>=0){
			switch($sort){
				case 2:$str='id desc';
					break;
				case 1:$str='id asc';
					break;	
				case 8:$str='readnums desc';
					break;
				case 7:$str='readnums asc';
					break;
			}
			$this->assign('sort',$sort);
		}
		return $str;
	}
	public function edit(){
		$id=I('get.id/d');
		$pid=I('get.pid/d',0);
		if(IS_POST){
			$id=I('post.id/d',0);
			$pid=I('post.pid/d',0);
			$m=I('post.');unset($m['id']);unset($m['pid']);
			$m['typeid']='|'.$id.'|';
			$m['ownertime']=strtotime($m['ownertime']);
			$m['isdep']=$this->depId;
			$m['status']=I('post.status/d',0);
			$m['istop']=I('post.istop/d',0);
			$m=$this->bll->create($m);
			if($pid){
				if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				$info=$this->bll->where(array('id'=>$pid,'isdel'=>0))->find();
				if(empty($info)) $this->error('当前新闻不存在或被删除');
				$rs=$this->bll->where(array('id'=>$pid))->save($m);
				if($rs == false) $this->error('当前新闻修改失败，请刷新重试！');
				else{
					$this->addLog('update','修改新闻ID：'.$pid);
					$this->success('修改成功',U('/Systemv/news/index/',array('id'=>$id)));
				}
			}else{
				if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				$m['adder']=$this->userInfo['id'];
				$m['addname']=$this->userInfo['nickname'];
				$m['addtime']=time();
				$m['isdel']=0;
				$sid=$this->bll->add($m);
				if($sid === false) $this->error('当前新闻添加失败，请刷新重试！');
				else{
					$this->addLog('add','添加新闻ID：'.$id);
					$this->success('添加成功',U('/Systemv/news/index/',array('id'=>$id)));
				}
			}
		}
		if($pid > 0){
			if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
			$info=$this->bll->where(array('id'=>$pid))->find();
			$this->assign('info',$info);
		}
		$this->assign('type',M('menu')->where(array('id'=>$id))->getField('type'));
		$this->assign('pid',$pid);
		$this->display();
	}
}
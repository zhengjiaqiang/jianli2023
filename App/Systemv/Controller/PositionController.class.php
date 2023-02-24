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
class PositionController extends BaseController{
	private $dbname='position';
	private $bll=null;
	private $id=null;
	private $powerArray=array();
	private $title=null;
	private $set=[];
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
		$list=M('word')->field('r.name as pname,w.value,w.name,w.id')->where(['w.depth'=>array('like','|1|%'),'r.pid'=>array('neq',0),'w.status'=>1,'w.isdel'=>0])->join('as w left join hh_word as r on w.pid=r.id')->select(); 
		foreach($list as $k=>$v) $this->set[$v['pname']][]=$v;
	}
	public function index(){
		if(!$this->check('select',$this->id)) $this->errorTypeMessage($this->roletype['select']['name']);
		$page=I('get.page/d',0);
		if(IS_AJAX){
			$re = $this->pageData($this->dbname,$page,$this->getWhere(),$this->getSort());
			foreach($re['aaData'] as $k=>$v){
				$re['aaData'][$k]['bigname']=M('bigtype')->where(['id'=>$v['bigtypeid']])->getField('name');
			}
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
			if($rs === false) $this->error('当前职位删除失败，请刷新网络重试！');
			else{
				$this->addLog('delete','删除职位IDS：'.$ids);
				$this->success('当前职位删除成功！');
			}
		}else if($event  == 'okpass' || $event == 'nopass'){
			// if(!$this->check('pass',$id)) $this->errorTypeMessage($this->roletype['pass']['name']);
			$message=$event == 'okpass' ? '审核通过' : '审核拒绝'; 
			$m['status']=$event == 'okpass' ? 1 : 0;
			$where['id']=array('in',$ids);
			$rs=$this->bll->where($where)->save($m);
			if($rs === false) $this->error('当前职位'.$message.'失败，请刷新网络重试！');
			else{
				$this->addLog('pass',$message.'职位IDS：'.$ids);
				$this->success('当前职位改变审核状态成功！');
			}
		}else if($event == 'on'){
			//查询当前
			$info=$this->bll->where(['id'=>$ids])->find();
			$this->bll->where(['id'=>$ids])->save(['status'=>($info['status'] ? 0 : 1)]);
			$this->success('当前职位'.($info['status'] ? '关闭' : '开启').'成功');
		}else if($event  == 'copy'){
			//复制当前的项目
			$info=M('position')->where(['id'=>$ids])->find();
			unset($info['id']);
			$info['adduid']=$this->userId;
			$info['addtime']=time();
			$rs=M('position')->add($info);
			if($rs === false) $this->error('当前职位复制失败，请检查网络连接设置！');
			else{
				$this->addLog('add','复制当前职位：'.$ids);
				$this->success('当前职位复制成功！');
			}
			
		}else if($event == 'sort'){
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
		$str ='sort desc,addtime desc,id desc';
		if($sort>=0){
			switch($sort){
				case 2:$str='id desc';
					break;
				case 1:$str='id asc';
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
			$data=$this->bll->create($m);
			$date=trimToArray(array_filter(explode('~', $data['btime'])));
			$data['btime']=strtotime($date[0].' 00:00:00');
			$data['etime']=strtotime($date[1].' 23:59:59');
			$data['usetime']=strtotime($data['usetime']);
			$data['type']=implode(',',$m['type']);
			$data['status']=1;
			if($pid){
				if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				$info=$this->bll->where(array('id'=>$pid,'isdel'=>0))->find();
				if(empty($info)) $this->error('当前职位不存在或被删除');
				$rs=$this->bll->where(array('id'=>$pid))->save($data);
				if($rs == false) $this->error('当前职位修改失败，请刷新重试！');
				else{
					$this->addLog('update','修改职位ID：'.$pid);
					$this->success('修改成功');
				}
			}else{
				$data['adduid']=$this->userId;
				$data['addtime']=time();
				 if(!$this->check('add',$id)) $this->errorTypeMessage($this->roletype['add']['name']);
				$sid=$this->bll->add($data);
				if($sid === false) $this->error('当前职位添加失败，请刷新重试！');
				else{
					$this->addLog('add','添加职位ID：'.$id);
					$this->success('添加职位成功');
				}
			}
		}
		if($pid > 0){
			if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
			$info=$this->bll->where(array('id'=>$pid))->find();
			$this->assign('info',$info);
		}
		//读取当前科室 职位 分类
		$this->assign('depart',M('department')->where(['status'=>1,'isdel'=>0])->field('id,name,code')->select());
		$this->assign('plan',M('plan')->where(['status'=>1,'isdel'=>0])->field('id,name')->select());
		$this->assign('bigtype',M('bigtype')->where(['status'=>1,'isdel'=>0])->field('id,name')->select());
		//
		 $this->assign('station',M('station')->where(['status'=>1])->select());
		//$this->assign('station',['医生','护士','医技','行政','研究','后勤','其他']);
		$this->assign('set',$this->set);
		$this->assign('pid',$pid);
		$this->assign('id',$id);
		$this->display();
	}
	
}
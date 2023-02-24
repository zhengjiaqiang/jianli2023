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
use Common\Libs\Util\Wpy;
class AdminController extends BaseController{

	protected $ptitle = '职员';
	private   $dbname = 'admin';
	private   $filedir = '/upload/avatar/101/';
	public function __construct(){
		parent::__construct();
		$this->assign('filedir',$this->filedir);
		$this->bll=M($this->dbname);
		//获取当前
		$this->id=I('get.id/d',0);
		$this->title=$this->getTitle($this->id);
		$this->powerArray=$this->checkType($this->id);
		$this->assign('title',$this->title);
		$this->assign('id',$this->id);
	}
	/**
	 * 管理
	 */
	public function index(){
		$page = I('get.page/d','1');
		if(IS_AJAX){
			$re = $this->pageData($this->dbname,$page,$this->getWhere(),$this->getSort());
			foreach($re['aaData'] as $k=>&$v){
				$v['pic'] = $v['id'].substr(md5($v['id']),8,16);
				$v['dname']=$v['isdep']==0 ? '网站管理员' : M('deparment')->where(array('id'=>$v['isdep']))->getField('name');
				$v['rname']=M('adminrole')->where(array('id'=>$v['rid']))->getField('name');
			}
			exit(json_encode($re,true));
		}
		if(!$this->check('select',$this->id)) $this->errorTypeMessage($this->roletype['select']['name']);
		$rolelist = M('adminrole')->where(array('ishide'=>'0','isdep'=>0))->order('sort asc')->field('id,name')->select();
		$this->assign('rolejson',json_encode($rolelist,JSON_UNESCAPED_UNICODE));
		$this->assign('rolelist',$rolelist);
		$this->display();
	}

	/**
	 * 编辑添加
	 */
	public function edit(){
		$pid = I('get.pid/d',0);
		$back = I('get.url');
		$bll  = D($this->dbname);
		if(IS_POST){
			$bll  = D($this->dbname);
			$pid   = I('post.pid/d',0);//自增id
			$id=I('post.id/d',0);
			$m    = I('post.');
			array_key_default(array('status'),$m);
			unset($m['id']);
			if($pid){ 
				if(!$this->check('update',$id)) $this->errorTypeMessage($this->roletype['update']['name']);
				unset($m['id']);
				unset($m['username']);
				unset($m['password']);
				if($bll->where(array('id'=>$pid))->save($m) === false){
					$this->addLog('admin','修改编号为：'.$pid.'的'.$this->ptitle.'失败');
					$this->error("修改失败，您可以重新尝试提交");
				}else{
					$back = I('get.url');
					$this->addLog('admin','修改编号为：'.$pid.'的'.$this->ptitle.'成功');
					$this->success('修改成功',U('/Systemv/admin/index/',array('id'=>$id)));
				}

			} else {
				if(!$this->check('add',$id)) $this->errorTypeMessage($this->roletype['add']['name']);

			
				if(empty($m['name'])){
					$this->error("请输入用户名");
				}
				if(empty($m['password']) || strlen($m['password'])<8){
					$this->error("请输入不少于6位的登录密码");
				}

				
				$tem = $bll->where(array('name'=>$m['name']))->field('id')->find();
				
				if(! empty($tem)){
					$this->error("您输入的用户名已经存在");
				}
				unset($tem);
				$m['rnd'] = rand_keys();

				$m['password'] = $bll->hashPassword($m['password'],$m['rnd']);
				$m['addtime'] = $m['lasttime'] = time();
				$m['addip']=$m['lastip']=get_client_ip();
				$m['isfirst'] = 0;
				unset($m['id']);
				$iid = $bll->data($m)->add();
				if($iid){
					$oldfile = $_SERVER['DOCUMENT_ROOT'].$this->filedir.I('post.filename').'.jpg';
					$newfile = $_SERVER['DOCUMENT_ROOT'].$this->filedir.$id.substr(md5($id),8,16).'.jpg';
					if (is_file($oldfile)){
						rename ($oldfile, $newfile);
					}
					$this->addLog('admin','新增'.$this->ptitle.',编号为：'.$iid.',账号为：'.$m['name']);
					$this->success($this->ptitle.'添加成功',U('/Systemv/admin/index/',array('id'=>$id)));
				}else{
					$this->error("新增'.$this->ptitle.'失败，您可以重新尝试提交");
				}
			}
			return ;
		}

		if($pid){
			$info = $bll->where(array('id' => $pid))->find();
			if(empty($info)){
				$this->error($this->ptitle.'不存在', empty($back) ? U(CONTROLLER_NAME.'/index') : $back);
			}
			$urlist=M('adminrole')->where(array('_string'=>'isdep='.$info['isdep'].' or isdefaultdep =1'))->getField('id,name');
			$this->assign('urlist',$urlist);
			$this->assign('info',$info);
		}else{
			$this->assign('filename',uniqid());
		}
		$dlist=M('adminrole')->where(array('status'=>1,'isdel'=>0))->getField('id,name');
		//读取当前科室的

		$this->assign('rolelist',$rolelist);
		$this->assign('dlist',$dlist);
		$this->display();
	}

	private function getOrganization(){
		$org_id = I('post.org_id');
		if(! is_array($org_id))return;
		$re = array();
		foreach($org_id as $n=>$v){
			$re[$v] = I('post.cko_'.$v,'0');
		}
		return json_encode($re,JSON_UNESCAPED_UNICODE);
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
			if(empty($ids)){
				$this->error('请选择要删除的'.$this->ptitle);
			}else if(is_array($ids)){
				if(in_array($uid,$ids)){
					$this->error("不能删除自己");
				}
				$ids = join(',',$ids);
			}else if(is_number($ids)){
				if($ids == $uid){
					$this->error("不能删除自己");
				}
			}else{$this->error('请选择要删除的'.$this->ptitle);}
			
			if(!is_delete_ids($ids)){
				$this->error("请选择要删除的".$this->ptitle);
			}
		  	$re = M('admin')->where("id in ($ids)")->delete();
			if($re){
				$this->addLog('admin',"删除".$this->ptitle."IDS:$ids");
				$this->success("编号为{$ids}已经删除！");
			}else{
				$this->error($this->ptitle.'删除失败');
			}
		}else if($event == 'nopass' || $event == 'okpass' || $event == 'nostatus'){
			if(!is_delete_ids($ids)){
				$this->error("请选择要审核的".$this->ptitle);
			}
			$data = array('status'=>'1');$ename = '在职';
			switch($event){
				case 'nopass':
					$data['status']=0;$ename = '禁用';
					break;
					break;
				case 'nostatus':
					$data['status']='-2';$ename = '启用';
					break;
			}
		    $re = M($this->dbname)->where("id in ($ids)")->save($data);
 			if($re === false){
 				$this->error($ename.'审核'.$this->ptitle.'失败');
			}else{
				$this->addLog('update',$ename.'审核'.$this->ptitle.'IDS:'.$ids);
				$this->success('编号为“'.$ids.'”'.$ename.'审核');
			}
		}
	}

	/**
	 * 获取查询条件
	 */
	private function getWhere(){
		$map = array();
		$map['is_hide'] = '0';
		$key = I('get.key');
		$stype = I('get.stype/d','0');
		$where = array();
		if(!empty($key)){
			switch($stype){
				case 1:
					$map['nickname'] = array('like',"%$key%");
					break;
				case 2:
					$map['id'] = (int) $key;
					break;
				default:
					$where['name'] = array('like',"%$key%");
					// $where['email'] = array('like',"%$key%");
					// $where['phone'] = array('like',"%$key%");
					$where['nickname'] = array('like',"%$key%");
					$where['_logic'] = 'or';
					$map['_complex'] = $where;
			}
			$this->assign('key',$key);
			$this->assign('stype',$stype);
		}
		$role_id = I('get.role_id/d');
		if($role_id>0){
			$map['rid'] = $role_id;
			$this->assign('rid',$role_id);
		}
		$status = I('get.status');
		if($status != ''){
			switch ((int)$status) {
				case 0:
					$map['status'] = 0;
					break;
				case 1:
					$map['status'] = 1;
					break;
				case -2:
					$map['status'] = -2;
					break;
			}
			$this->assign('status',$status);
		}
		$this->roleId == 1 ? :$map['isdep']=$this->depId;
		$map['isdel'] = '0';
		$map['id']=array('neq',$this->userId);
		return $map;
	}
	/**
	 * 获取排序条件
	 */	
	private function getSort(){
		return 'id desc';
	}
	/**
	 * 重置密码
	 */	
	public function pwd(){
		if(!$this->check('update',$this->id)) $this->errorTypeMessage($this->roletype['update']['name']);
		$pid = I('get.pid/d',0);
		$bll  = D($this->dbname);
		if(IS_POST){
			$id   = I('post.id/d',0);
			if($id>0){
				$m = I('post.');
				if(strlen($m['password'])<6){
					$this->error("请输入至少6位的密码");
				}
				$m['isfirst'] = '0';
				$m['rnd'] = rand_keys();
				$m['password'] = $bll->hashPassword($m['password'],$m['rnd']);
				if($bll->where(array('id'=>$id))->save($m) === false){
					$this->addLog('admin','修改编号为：'.$id.'的'.$this->ptitle.'失败');
					$this->error("重置密码失败，您可以重新尝试提交");
				}else{
					$back = I('get.url');
					$this->addLog('admin','修改编号为：'.$id.'的'.$this->ptitle.'成功');
					$this->success('重置密码修改成功', empty($back) ? U(CONTROLLER_NAME.'/index') : $back);
				}
			}else{
				$this->error('对象不存在');
			}
		}
		if($pid){
			$info = $bll->where(array('id' => $pid))->field('id,name')->find();
			if(empty($info)){
				$this->error($this->ptitle.'不存在',U(CONTROLLER_NAME.'/index'));
			}
			$this->assign('info',$info);
		}else{
	 		$this->error('对象不存在');
		}
		$this->display();
	}
	/**
	 * 设置头像
	 */	
	public function photo(){
		// if(!$this->check('update',$this->id)) $this->errorTypeMessage($this->roletype['update']['name']);
		 $pid = I('get.id/d',0);
		 $action = I('get.action');
		 if(empty($action)){
			if($pid<1) $this->error('对象不存在');
		 }else{
			 $uid = (int) str_ireplace("upload","",$action);
			 if($uid<1) {
				 json_encode(array('status'=>'-1'));exit();
			 }
			 $id = $uid;
			 $action = 'upload';
		 }
		 if(!empty($action)){
			switch($action){
				//上传切头像
				case 'upload':
					$input = file_get_contents('php://input');
					if(! empty($input)){
						$data = explode('--------------------', $input);
						$filename = $_SERVER['DOCUMENT_ROOT'].$this->filedir.$uid.substr(md5($uid),8,16).'.jpg';
						file_put_contents($filename, $data[0]);
						$rs['status'] = 1;
					}else $rs['status'] = -1;

				break;

				default:
					$rs['status'] = -1;
			}
			echo json_encode($rs);
			return;
		 }
		$this->assign('uid',$pid);
		$this->display();
	}
	/**
	 * 临时头像存储
	 */	
	public function temphoto(){
		 $name = I('get.name');
		 $action = I('get.action');

		 if(!$this->check('add')){
			$this->error('没有修改'.$this->ptitle.'的权限！');
		 }

		 if(empty($name) && empty($action)){
			 $this->error('访问出错');
		 }else if(! empty($action)){
			 $name = str_ireplace("upload","",$action);
			 if($name == 'upload') {
				 json_encode(array('status'=>'-1'));exit();
			 }
			 $action = 'upload';
		 }

		 if(!empty($action)){
			switch($action){
				/*//上传临时图片
				case 'uploadtmp':
					$file = 'uploadtmp.jpg';
					@move_uploaded_file($_FILES['Filedata']['tmp_name'], $file);
					$rs['status'] = 1;
					$rs['url'] = './php/' . $file;
				break;*/

				//上传切头像
				case 'upload':
					$input = file_get_contents('php://input');
					$dpath = $_SERVER['DOCUMENT_ROOT'].$this->filedir;
					if(! empty($input)){
						$data = explode('--------------------', $input);
						if ( file_exists($dpath) || mkdir($dpath, 0777, true)) {
							$filename = $dpath.$name.'.jpg';
							file_put_contents($filename, $data[0]);
							$rs['status'] = 1;
						}
					}else $rs['status'] = -1;

				break;

				default:
					$rs['status'] = -1;
			}
			echo json_encode($rs);
			return;
		 }
		 $this->assign('name',$name);
		 $this->display(); 
	}
	public function rolelist(){
		if(!IS_POST) exit();
		$depid=I('post.depid/d',0);
		if($depid ==0 ){
			$map['isdep']=$depid;
			$map['isdefaultdep']=0;
			$map['status']=1;
		}else{
			$where['isdep']=$depid;
			$where['isdefaultdep']=  1 ;
			$where['_logic'] = 'or';
			$map['_complex'] = $where;
			$map['status']=1;
		}
		$rlist=M('adminrole')->where($map)->field('id,name')->select();
		echo json_encode($rlist);exit;
	}
}
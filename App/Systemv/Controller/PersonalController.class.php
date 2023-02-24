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
class PersonalController extends BaseController{

	public $ptitle='个人资料';
	private   $filedir = '/upload/avatar/101/';
	public function __construct(){
		parent::__construct();
		$this->assign('filedir',$this->filedir);
	}
	/**
	 * 管理
	 */
	public function index(){
	
		$bll =  M('admin');
		if(IS_POST){
			$data = I('post.');
			//判定手机和邮箱的重复性 可以为空不可重复
			if(!empty($data['email'])){
				if(!is_email($data['email'])) $this->error("您输入的不是有效的Email");
				$m = $bll->where(array('id'=>array('NEQ',$id),'email' => $data['email']))->field('id')->find();
				if(! empty($m)){
					$this->error("您输入的Email已经存在");
				}
			}
			if(!empty($data['phone'])){
				if(!is_mobile($data['phone'])) $this->error("您输入的不是有效的手机号码");
				$m = $bll->where(array('id'=>array('NEQ',$id),'phone' => $data['phone']))->field('id')->find();
				if(! empty($m)){
					$this->error("您输入的手机号码已经存在");
				}
			}

			$info = $bll->where(array('id' => $id))->field('id,username,nickname,email,phone,telephone')->find();
			if(strpos($info['username'], '@') != false){
				if(empty($data['email']) && empty($data['phone'])){
					$this->error("必须绑定邮箱和手机中的至少一个");
				}
			}
			$datam = array();
			// $datam['nickname'] = $data['nickname'];
			$datam['email'] = $data['email'];
			$datam['phone'] = $data['phone'];
			$datam['birthday'] = strtotime($data['birthday']);
			$datam['sex'] = $data['sex'];

			if($bll->where('id='.$id)->save($datam) === false){
				$this->error("个人资料修改失败，您可以重新尝试提交");
			}else{
				$this->addLog('admin','个人资料修改');
				$this->success('个人资料修改已成功修改');
			}
		}
		$info = $bll->field('u.nickname,u.name,r.name as rname,u.id')->where(array('u.id' => $this->userId))->join('as u left join hh_adminrole as r on u.rid=r.id ')->find();
		$this->assign('info',$info);
		$this->display();
	}
	
	/**
	 * 修改密码
	 */
	 function pwd(){
		if(IS_POST){
			$form = I('post.');
			if(strlen($form['newpwd']) < 6 || is_digits($form['newpwd']) || is_word($form['newpwd'])){
				$this->error("密码安全度不够，请使用数字和字符的组合作为登陆密码。");
			}
			if(strlen($form['oldpwd'])<6){
				$this->error("请输入原密码");
			}
			if($form['oldpwd'] == $form['newpwd']){
				$this->error("原密码和新密码不能相同");
			}
			
			$bll = D('admin');
			$info = $bll->where(array('id' => $this->userId))->find();
			$oldpwd = $bll->hashPassword($form['oldpwd'],$info['rnd']);
			if($oldpwd != $info['password']){
				$this->error("原密码输入错误！");
			}
			//判断当时密码是否和前五次相等
			$data =array();
			$data['rnd'] = rand_keys();
			$data['password'] = $bll->hashPassword($form['newpwd'],$data['rnd']);
			$data['isfirst'] = '1';
			$pwdArray=M('pwdhistroy')->where(array('uid'=>$this->userId))->limit(5)->order('creatime desc')->getField('password',true); 
			if(in_array(md5($form['newpwd']),$pwdArray)){
				$this->error("当前密码和前面密码一致！");
			}
			if($bll->where(array('id'=>$this->userId))->save($data)){
				$m['uid']=$this->userId;
				$m['password']=md5($form['newpwd']);
				$m['creatime']=time();
				M('pwdhistroy')->add($m);
				$this->addLog('update','密码修改');
				$this->success('密码修改成功，请重新登录',U('/resumeLogin/index/out'));
			}else{
				$this->error("修改密码失败，您可以重新尝试提交");
			}
			return;
		}
		$info = M('admin')->where(array('id' => $this->userId))->find();
		if(empty($info) || $info['status']<1){
			$this->redirect(U("/login/index/out"));
		}
		$this->assign('info',$info);
		$this->display();
	 }
	/**
	 * 修改头像
	 */
	 function avatar(){
		 $action = I('get.action');
		 $id = $this->userInfo['id'];
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
					$data = explode('--------------------', $input);
					$filename = $_SERVER['DOCUMENT_ROOT'].'/upload/avatar/101/'.$id.substr(md5($id),8,16).'.jpg';
					file_put_contents($filename, $data[0]);
					$rs['status'] = 1;
				break;

				default:
					$rs['status'] = -1;
			}
			echo json_encode($rs);
			return;
		 }
		 $this->assign('uid',$id);
		 $this->display();
	 }

}
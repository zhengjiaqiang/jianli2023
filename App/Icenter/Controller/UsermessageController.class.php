<?php
namespace Icenter\Controller;
use Icenter\Common\BaseController;

class UsermessageController extends BaseController {
	private $bll=null;
	public function __construct(){
		parent::__construct();
		$this->bll=M('user');
	}
    public function index(){
		$this->assign('userinfo',$this->userInfo);
		$this->display();
	}
	public function reset(){
		if(!IS_POST) exit();
		$email=I('post.email');
		$username=I('post.username');
		$mobile=I('post.mobile');
		//if($this->bll->where(['card'=>$card,'id'=>['neq',$this->userId]])->count()) $this->error('当前身份证号已被使用，请重新输入！');
		if($this->bll->where(['mobile'=>$mobile,'id'=>['neq',$this->userId]])->count()) $this->error('当前手机号码已被使用，请重新输入！');
		if($this->bll->where(['email'=>$email,'id'=>['neq',$this->userId]])->count()) $this->error('当前邮件已被使用，请重新输入！');
		if($this->bll->where(['id'=>$this->userId])->save(['username'=>$username,'mobile'=>$mobile,'email'=>$email]))
			$this->success('基本信息已经更改完成！');
		else $this->error('当前信息更改失败，请检查网络链接设置！');
	}
}
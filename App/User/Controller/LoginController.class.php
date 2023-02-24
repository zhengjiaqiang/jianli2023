<?php
namespace User\Controller;
use Think\Cache\Driver\Redis;
use Think\Controller;
class LoginController extends Controller {
    public function __construct()
    {
        parent::__construct();
        D('Icenter/System')->initSet();
    }
    public function index(){
        $this->display();
    }
    public function reg(){
        if(!IS_POST) exit();
        $code=I('post.code','');
        // $verify = new \Common\Libs\Util\VerifyImg();
		// if(!$verify->check($code, 329))  $this->error('当前验证码错误！');
        $passwd=I('post.password','');
        $passwd1=I('post.passwd','');
        $post=I('post.');
	
        //$data=D('user')->create($post);
	$data['email']=I('post.email');
	$data['mobile']=I('post.mobile/d');
	$data['card']=I('post.card');
	$data['username']=I('post.username');
	$data['sex']=I('post.sex');	
	//验证数据合法性	
	!is_email($data['email']) ?:$this->error('请输入正确的邮箱地址！');
	is_mobile($data['mobile'])?:$this->error('请输入正确的联系方式！');	
	//isset(['男','女'][$data['sex']]) ?:$this->error('请选择正确的性别!');
			
        $data['zhiye']=implode(',',$data['zhiye']);
        if($passwd != $passwd1) $this->error('俩次密码不一致！');
        // var_dump($data);die;
        $returndata=D('user')->reg($passwd,$data);
    
        $error=['-101'=>'必要条件缺失!','-103'=>'当前身份证已被注册!','-102'=>'当前手机号已被注册!','-104'=>'当前邮箱已被注册！'];
        if(!is_array($returndata)) $this->error($error[$returndata]);
        $data=array_merge($data,$returndata);
        $result=M('user')->add($data);
        if($result < 1)  $this->error('当前注册失败，请检查网络连接设置！');
        else{
            $rs=D('user')->Login($data['mobile'],$passwd);
            $this->success('当前注册成功，前往简历中心!');
        } 
    }
    public function ulogin(){
        if(!IS_POST) exit();
        $mobile=I('post.mobile','');
        $passwd=I('post.password','');
        $code=I('post.code','');
        
        $verify = new \Common\Libs\Util\VerifyImg();
        //改
		//if(!$verify->check($code, 3928))  $this->error('当前验证码错误！');
        $rs=D('user')->Login($mobile,$passwd);
        // $error=array('-1'=>'当前手机号码不存在!','-2'=>'当前用户名或密码错误！','-1001'=>'当前验证码错误！');
        $error=array('-1'=>'当前用户名或密码错误！','-2'=>'当前用户名或密码错误！','-1001'=>'当前验证码错误！');
        if(!is_array($rs)) $this->error($error[$rs]);
        else $this->success('登录成功，前往简历中心!');
    }
    public function register(){
        $this->display();
    }
    public function reset(){ 
        $this->assign('type',1);
        $this->display();
    }
    public function email(){ 
        $this->assign('type',2);
        $this->display('reset');
    }
    public function sendreset(){
        if(!IS_POST) exit();
    	$mode = M('user');
    	if (!$mode->autoCheckToken($_POST)){
            $this->error('请刷新当前页面');
        }
        
        $phone=I('post.phone');
        $code=I('post.code');
        $type=I('post.type/d',1);
        $uinfo=M('user')->field('rnd,id')->where($type == 1 ?  ['mobile'=>$phone] : ['email'=>$phone])->find();
        if($type == 1){
            if(empty($uinfo)) $this->error('当前手机号码不存在，请先注册！');
            if(!session('?send_sms_codeid')) $this->error('请先发送短信！');
            $codeinfo=M('usercode')->where(['id'=>session('send_sms_codeid')])->find();
            if($codeinfo['mobile'] != $phone) $this->error('当前手机号码与接收短信手机号码不一致！');
            if(time()-$codeinfo['addtime'] > 600){
                //删除掉当前的
                $this->error('当前验证码已经过期，请重新发送！');
            }
            if($codeinfo['code']!=$code) $this->error('当前验证码错误，请重新输入！');
            session('istrue',$codeinfo['uid']);
            $this->success('验证成功，前往重置密码页面！','resetpwd');
        }elseif($type == 2){
            if(empty($uinfo)) $this->error('当前邮箱不存在，请仔细核查！'); 
            $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
            $url=$sys_protocal.$_SERVER['HTTP_HOST'].'/user/login/resetpwd?token='.encode($phone,$uinfo['rnd']);
            $emailbody = "亲爱的" . $phone . "：<br/>您在" . date('Y-m-d') . "提交了找回密码请求。请点击下面的链接重置密码（链接10分钟内有效）。<br/><a href='" . $url . "' target='_blank'>" . $url . "</a><br/>如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问。<br/>如果您没有提交找回密码请求，请忽略此邮件。"; 
            $isresult=sendMail($phone,'找回密码',$emailbody);
            if($isresult !== true) $this->error('当前邮件发送失败，请重新发送！');
            else{
                session('send_email_time',time());
                session('send_email_key',$uinfo['rnd']);
                session('send_email_user',$phone);
                $this->success('系统已向您的邮箱发送了一封邮件<br/>请登录到您的邮箱及时重置您的密码！','/icenter');
            }
        }
    }
    public function sendsms(){
        if(!IS_POST) exit();
        /*$mode = M('user');
        if (!$mode->autoCheckToken($_POST)){
            $this->error('请刷新当前页面');
        }*/
        $yzm = I('post.yzm');
        $verify = new \Common\Libs\Util\VerifyImg();
        if(!$verify->check($yzm, 3928))  $this->error('当前图形验证码错误！');
        
        $mobile=I('post.phone');
        //判断次数
        if (cookie(get_client_ip())>=10) {
            $this->error('当前ip地址本日已无法发送短信！');
        }
        if (cookie($mobile)>=10) {
            $this->error('当前手机号码本日已无法发送短信！');
        }
        if(!is_mobile($mobile)) $this->error('当前手机号码错误，请检查重新输入！');
        $uinfo=M('user')->where(['mobile'=>$mobile])->find();
        if(empty($uinfo)) $this->error('当前手机号码不存在，请先注册！');
        $code=rand(1000,9999);
        //str_replace('{$code}',$code,C('SENDMESSAGEMESSAGE'))
        webservice(C('SYS_SET.SENDMESSAGEWEB'),['userName'=>C('SYS_SET.SENDMESSAGEUSER'),'password'=>C('SYS_SET.SENDMESSAGEPASSWD'),'destCode'=>$mobile,'content'=>str_replace('{$code}',$code,C('SYS_SET.SENDMESSAGEMESSAGE')),'sequenceId'=>123456,'priority'=>100,'isEncrypt'=>0]);
        $data['uid']=$uinfo['id'];
        $data['code']=$code;
        $data['addtime']=time();
        $data['ip']=get_client_ip();
        $data['mobile']=$mobile;
        $id=M('usercode')->add($data);
        session('send_sms_codeid',$id);
        
        $ipcount = cookie(get_client_ip());
        $mobilecount = cookie($mobile);
        if ($ipcount==NULL) {
            cookie(get_client_ip(),1,['expire'=>24*60*60]);
        } else {
            cookie(get_client_ip(),$ipcount+1);
        }
        if ($mobilecount==NULL) {
            cookie($mobile,1,['expire'=>24*60*60]);
        } else {
            cookie($mobile,$mobilecount+1);
        }
        
        $this->success('短信发送成功');
    }
    public function resetpwd(){
        if(IS_POST){
            
            $post=I('post.');
            if($post['passwd']!=$post['passwd2']) $this->error('俩次密码不一致请重新输入！');
            $m['rnd']=rand_keys();
            $m['passwd']=D('user')->hashPassword($post['passwd'],$m['rnd']);
            $result='';
            if(session('?istrue')) $result=M('user')->where(['id'=>session('istrue')])->save($m);
            else if(session('?send_email_time')) $result=M('user')->where(['email'=>session('send_email_user')])->save($m);
            if($result == false) $this->error('修改密码失败，请重新输入！');
            else $this->success('修改密码成功，前往登录...','/icenter');
        }
        //判断当前模式
     
        if(!session('?istrue') &&  !session('?send_email_time'))redirect('reset');
        $token=I('get.token');
        if($token){
            if(!session('?send_email_time')) redirect('reset');
            
            //验证是否过期
            if(time()-session('send_email_time') > 600) $this->error('当前链接失效，请重新发送！','reset');
            $email=decode($token,session('send_email_key'));
            if($email !== session('send_email_user')) $this->error('当前邮件与接收邮件不一致！');
            $uinfo=M('user')->field('rnd,id')->where(['email'=>$email])->find();
            if(empty($uinfo)) $this->error('当前邮箱不存在，请重新点击链接！');
        }
       $this->display();
    }
}

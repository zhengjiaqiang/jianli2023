<?php
namespace ResumeLogin\Controller;
use Think\Controller;
use ResumeLogin\Model;
class IndexController extends Controller {
    public function index(){
		//$redis= new \Think\Cache\Driver\Redis();
        if(IS_POST){
			//记录登陆失败者IP
			$ip = get_client_ip();
			$username = I('post.username');
			$password = I('post.password');
			$code = I('post.code');
			if (empty($username) || empty($password))  $this->error("用户名或者密码不能为空！", U("index"));
	
			$re = D('Admin')->Login($username,$password,$code);
			if(is_array($re)){
				D('Logs')->AddItem($re['id'],0,$re['username'].'登录成功');
				$this->success('登录成功！', U('/systemv/index'));
			}else{
				$error =array(
					'0'=>'用户已经禁用',
					'-1'=>'当前用户或密码错误！',
					'-101'=>'yzm',
					'-102'=>'当前用户或密码错误！',
					'-1001'=>'密码连续输错3次，已被锁定，请'.(int)(C('LOCKTIME')/60).'分钟后再试',
					'-1002'=>'密码连续输错5次，已被禁用，请联系管理员解锁',
					'-1022'=>'当前用户正在使用',
					'-1007'=>'当前用户已被禁用'
				);
				$m['uid']=0;
				$m['type']=0;
				$m['createtime']=time();
				$m['url']=get_url();
				$m['ips']=get_client_ip();
				$m['info']='当前用户名：'.$username.'，错误信息：'.$error[$re];
				M('logs')->add($m);
				$this->error($error[$re], U("index"));
			}
		}
      	$this->display();
    }
    public function out(){
		//退出修改状态
		$m['islogin']=0;
		$u= session('admin_auth');
		M('admin')->where(array('id'=>$u['id']))->save($m);unset($m);
		D('Systemv/Admin')->LoginOut();
		$this->redirect('index');
	}
}
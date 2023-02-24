<?php


namespace Systemv\Model;

use Think\Model;

class AdminModel extends Model {
	
		protected $_validate = array(
			//用户名验证
			array('name', '6,64', -1, 0, 'length'),
			array('name', '', -2, 0, 'unique', 1)
		);
		//array(填充字段,填充内容,[填充条件,附加规则])
		protected $_auto = array(
				array('addtime', 'time', 1, 'function'),
				array('lasttime', 'time', 3, 'function')
		);
		
		/**
		 * 用户登录
		 * @param  string  $username 用户名
		 * @param  string  $password 用户密码
		 * @return integer           登录成功-用户ID，登录失败-错误编号
		 */
		public function Login($username, $password,$code){
			$map = $this->getUsername($username);
			if(empty($map)) return '-102';
			/* 获取用户数据 */
			$verify = new \Common\Libs\Util\VerifyImg();
			if(! $verify->check($code, 3928)){
				return '-101';
			}
			$user = $this->where($map)->find();

			if(is_array($user)){
				if($user['status']==0) return -1007;
				if(C('ISONERROR')){
					if(!$this->userError($user)) return -1001;
				}
				/* 验证用户密码 */
				if($this->hashPassword($password, $user['rnd']) === $user['password']){
					//更新用户登录信息
					$rnd = rand_keys();
					$updata = array(
						'id'              => $user['id'],
						'rnd'             => $rnd,
						'password' 	  	  => $this->hashPassword($password, $rnd),
						'lasttime' => NOW_TIME,
						'errorloginnum'   => '0',
						'loginnum'   => $user['loginnum']+1,
						'lastip'   => get_client_ip(),
						'iserror'	=> NULL,
						'loginstatus'=>1
					);
					if($user['errorloginnum'] == '0'){
						$updata['errorip'] = '';
					}
					$this->save($updata);
					/* 记录登录SESSION和COOKIES */
					$auth = array(
							'id'             => $user['id'],
							'lasttime'        => $updata['lasttime'],
					);
					$token =$auth;
					ksort($token); //排序
					$token = http_build_query($token); //url编码并生成query字符串
					$token = sha1($token); //生成签名
					session('admin_auth', $auth);
					session('admin_token', $token);
					return $user; //登录成功，返回用户ID
				} else {
					$data = array(
						'id'              => $user['id'],
						'errorloginnum' => $user['errorloginnum']+1,
						'errorip'   => get_client_ip(),
					);
					$errorcode=-102;
					if(C('ISONERROR')){
						if($user['errorloginnum'] == 2){
							$data['errortime']=time();
							$errorcode =-1001;
						}else if($user['errorloginnum']  >= (C('ERRORNUMS')-1)){
							$data['status']=0;
							$errorcode =-1002;
						}
					}
					$this->save($data);
					return $errorcode;
				}
			} else {
				return -1; //用户不存在
			}
		}

		/**
		 * 用户登录
		 * @param  string  $username 用户名
		 * @param  integer $type     用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
		 * @return integer           登录成功-用户ID，登录失败-错误编号
		 */
		public function iscode($username, $password, $type = 1){
			$map = $this->getUsername($username);
			if(empty($map)) return '-1';
			/* 获取用户数据 */
			$user = $this->field('id,errorloginnum,errorip')->where($map)->find();
			if(is_array($user)){
				if($user['errorloginnum']>0) return '1';
				if($user['errorloginnum'] == '0' && $user['errorloginip'] == get_client_ip()) return '1';
			} else {
				return -1; //用户不存在
			}
		}
		private function getUsername($username){
			$map = array();
			$map['name'] = $username;
			return $map;
		}
		private function userError($user){
			if(!empty($user['errortime'])){
				if(time()- ($user['errortime'] + C('LOCKTIME')) < 0){
					return false;
				}else return true;
			}else return true;
		}
		public function isLogin($type=true){
			$this->staticParam();
			$user = session('admin_auth');
			if (empty($user))  return 0;
			// if(C('ISONELOGIN')){
			// 	$logintime=$this->where(array('id'=>$user['id']))->getField('lasttime');
			// 	if(($user['lasttime']+C('MAXINLINETIME'))-time() < 0 || $logintime !=$user['lasttime']){
			// 		$m['loginstatus']=0;
			// 		M('admin')->where(array('id'=>$user['id']))->save($m);unset($m);
			// 		session_destroy();
			// 		return -2011;
			// 	};
			// }
			$utoken =$user;
			ksort($utoken); //排序
			$utoken = http_build_query($utoken); //url编码并生成query字符串
			$utoken = sha1($utoken); //生成签名
			if($utoken == session('admin_token')){
				if($type){
					$re = $this->where(array('id'=>$user['id']))->find();
					if(! is_array($re) || $re['status'] != 1){
						return -1;//禁用
					}
					return $re;
				}
				return $user;
			}else{
				return 0;
			}
		}
		
	public function LoginOut(){
		session('admin_auth', null);
		session('admin_token', null);
	}
	
	/**
	 * 密码加密
	 * @param  string  $password 密码
	 * @param  string  $rnd 验证码
	 * @return string
	 */
    public function hashPassword($password, $rnd) {
	  return md5(md5($password) . md5($rnd));
	}
	private function staticParam(){
		$sys=M('system')->select();
		foreach($sys as $k=>$v){
			C($v['name'],$v['value']);
		}
	}
}

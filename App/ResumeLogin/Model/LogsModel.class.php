<?php

namespace ResumeLogin\Model;

use Think\Model;

class LogsModel extends Model {
		
		protected $_auto = array(
				array('createtime', 'time', 1, 'function'),
				array('ips', 'get_client_ip', 1, 'function'),	
				array('url', 'get_url', 1, 'function', 12)
		);
		public function AddItem($uid,$type,$remark,$data_id=0){
			$data = array('uid'=>$uid,'type'=>$type,'info'=>$remark);
			$this->create($data);
			return $this->add() !== false ? true : false;
		}
		
}

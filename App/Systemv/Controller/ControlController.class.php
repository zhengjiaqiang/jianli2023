<?php
// +----------------------------------------------------------------------
// | I DO
// +----------------------------------------------------------------------
// | Copyright (c) 2016 All rights reserved.
// +--------------------------- -------------------------------------------
// | Author: 雪地里的松鼠
// +----------------------------------------------------------------------

namespace Systemv\Controller;
use Systemv\Common\BaseController;
class ControlController extends BaseController {
	public function set(){
		$id=I('get.id/d',0);
		if(!$this->checkMenu($id,$this->roleId)) $this->error('未拥有此栏目权限，请联系管理员！');
		$info=M('menu')->where(array('id'=>$id))->field('id,pid,type,index,ifdel')->find();

		//读取当前
		if($info['index'] !=2){
			$sinfo=M('menutype')->where(['id'=>$info['type'],'isdel'=>0])->find();
			if(empty($sinfo)) $this->error('当前类型不存在，请仔细核查！');
			
			$url=empty($sinfo['url']) ? (empty($sinfo['system']) ? __MODULE__.'/'.$sinfo['controller'].'/'.$sinfo['action'] : $sinfo['system']) : $sinfo['url'];
		}else{
			switch($info['pid']){
				case 117: $url = '/Systemv/menu/index'; break;
				case 114: $url = '/Systemv/admin/index'; break;
				case 113: $url = '/Systemv/Adminrole/index';break;
				case 258: $url = '/Systemv/message/index';break;
				case 260: $url = '/Systemv/log/index';break;
				case 79:  $url = '/Systemv/mtype/index';break;
			}
		}
		echo $url;
		$this->redirect($url.'/id/'.$id);
	}
}
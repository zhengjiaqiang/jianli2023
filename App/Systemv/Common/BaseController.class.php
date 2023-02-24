<?php
// +----------------------------------------------------------------------
// | 超管基类
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 All rights reserved.
// +----------------------------------------------------------------------
// | Author: 雪地里的松鼠
// +----------------------------------------------------------------------

namespace Systemv\Common;

use Systemv\Model;
use Think\Controller;
class BaseController extends Controller {
	protected $isLogin    =true;
	protected $userInfo   = null;
	protected $ptitle     = null;
	protected $onePageRow = 15;
	protected $roleList   =null;
	protected $userId=null;
	protected $roleId=null;
	protected $webtype=null;
	protected $depId=null;
	protected $keshiPower=null;
	protected $rolePath = CONTROLLER_NAME;
	protected $type=null;
	protected $logtype    =  array( 'login'=>array('value'=>'0','name'=>'登录'),
								'add'=>array('value'=>'1','name'=>'添加'),
								'update'=>array('value'=>'2','name'=>'编辑'),
								'delete'=>array('value'=>'3','name'=>'删除'),
								'pass'=>array('value'=>'4','name'=>'审核'),
								'sort'=>array('value'=>'5','name'=>'排序'),
								'admin'=>array('value'=>'6','name'=>'管理员'),
								'other'=>array('value'=>'7','name'=>'其它'));
	protected $roletype   = array('select'=>array('value'=>'1','name'=>'查看'),
								'add'=>array('value'=>'2','name'=>'添加'),
								'update'=>array('value'=>'4','name'=>'编辑'),
								'delete'=>array('value'=>'8','name'=>'删除'),
								'pass'=>array('value'=>'16','name'=>'审核'),
								'top'=>array('value'=>'64','name'=>'置顶'),
								'sort'=>array('value'=>'128','name'=>'排序'));
	protected $signle=[1=>['update','select','add'],2=>["select","add","update","delete","pass","top","sort"]];
	protected function _initialize() {
		//验证登录
		if($this->isLogin){
			$this->competence();
		}
		//https://blog.csdn.net/qq_34886696/article/details/105369696
		D('Icenter/system')->initSet();
		//初始化数据字典
		$this->assign('ptitle',$this->ptitle);
		$this->assign('onePageRow',$this->onePageRow);
		import("Common.Libs.Util.Html");
		//读取权限信息   
		$this->userId=$this->userInfo['id'];
		$this->roleId=$this->userInfo['rid'];
		//实例化
		$this->type=M('type')->order('sort asc,id asc')->getField('id',true);
		
		$this->webtype=M('menutype')->where(array('isshow'=>1,'isdel'=>0))->getField('id,name');
		$this->roleList = M('permission')->where(array('rid'=>$this->roleId))->select();
		$this->assign('roletype',$this->roletype);
		$this->assign('webtype',$this->webtype);
		$this->assign('webtitle',C('WEBTITLE'));

	}
	/**
	 * 验证登录
	 */
	private function competence() {
		$this->userInfo = D('Admin')->isLogin();
		if(! is_array($this->userInfo)){
			$this->redirect(U("/ResumeLogin/index"));
		}
		if($this->userInfo['isfirst'] == 0 && (CONTROLLER_NAME.ACTION_NAME) != 'Personalpwd'){
			$this->redirect(U("Personal/pwd"));
		}
	}
	// priva
	/**
	 * 获取日志类型
	 * @return string
	 */
	private function getLogType($type){
		if(isset($this->logtype[$type])){
			return $this->logtype[$type]['value'];
		}
		return $this->logtype['other']['value'];
	}
	/**
	 * 添加操作日志
	 */
	protected function addLog($type,$remark,$data_id=0){
		$userInfo = $this->userInfo;
		D('Logs')->AddItem($userInfo['id'],$this->getLogType($type),$userInfo['username'].$remark,$data_id);
	}
	/**
	 * 读取分页数据
	 * @return array
	 */
	protected function pageData($obj,$currPage,$where=null,$order=null,$field=null,$join=false,$iscount=false){
		if($currPage<=0){$currPage = 1;}
		$re = array();
		$bll = M($obj);
		$re['onePageRow'] = $this->onePageRow;		
		$re['currPage'] = $currPage;
		if($join  == false){
			$re['rowCount'] = $bll->where($where)->count();
		}else{
			$re['rowCount'] = $bll->where($where)->join($join)->count();
			
		}
		$re['pageCount'] =ceil($re['rowCount']/$re['onePageRow']); 
		if($currPage<=$re['pageCount']){
			$re['aaData'] =  $join == false ? $bll->where($where)->order($order)->field($field)->page($currPage,$this->onePageRow)->select() : $bll->where($where)->order($order)->field($field)->page($currPage,$this->onePageRow)->join($join)->select();
		}
		if(empty($re['aaData'])){
			$re['aaData'] = array();
		}
		$re['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
		$re['sEcho'] = I('get.sEcho/d','1');
		$re['iTotalRecords'] = $re['rowCount'];
		$re['iTotalDisplayRecords'] = $re['rowCount'];
		return $re;
	}
	public function checkMenu($id,$roleid){
		if(empty($id) || empty($roleid)) return false;
		$info=M('permission')->where(array('mid'=>$id,'rid'=>$roleid))->find();
		if(empty($info)) return false;
		else return true;
	}
	/**
	 * 判断权限
	 * @return bool
	 */
	protected function check($type='select',$id){
		$power=$this->checkType($id);
		if(empty($power)) return false;
		foreach($power as $k=>$v){
			if($v  == $type){
				return true;
			}
		}
		return false;
	}
	//获取当前栏目的权限  返回
	public function checkType($id){
		//1 先获取所有的  然后匹配
		if(empty($id)) return false;
		$allpower=json_decode(M('menupower')->where(array('mid'=>$id))->getField('type'));
		
		$mpower=array_keys($this->roletype);
		//当前用户所有的权限
		$selfpower=json_decode(M('permission')->where(array('mid'=>$id,'rid'=>$this->roleId))->getField('power'));
		$countpower=array_intersect($allpower,$selfpower);
		foreach($mpower as $k=>$v){
			if(!in_array($v,$selfpower)) $this->assign($v,0);
			else $this->assign($v,1);
		}
		return $countpower;
		
	}
	public function errorTypeMessage($message){
		$this->error('您未拥有'.$message.'权限，请联系管理员');
	}
	public function getTitle($id){
		if(empty($id)) return false;
		return M('menu')->where(array('status'=>1,'delete'=>0,'id'=>$id))->getField('name');
	}
}
;
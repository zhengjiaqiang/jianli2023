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
class LogController extends BaseController{

	private    $dbname = 'logs';
	private $bll=null;
	private $id=null;
	private $powerArray=array();
	private $title=null;
	public function __construct(){
		parent::__construct();
		$this->bll=M($this->dbname);
		//获取当前
		$this->id=I('get.id/d',0);
		$this->title=$this->getTitle($this->id);
		$this->powerArray=$this->checkType($this->id);
		$this->checkType($this->id,true);
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
			exit(json_encode($re,true));
		}
		if(!$this->check('select',$this->id)) $this->errorTypeMessage($this->roletype['select']['name']);
		$this->assign('logtype',$this->logtype);
		$this->display();
	}
	/**
	 * 获取查询条件
	 */
	private function getWhere(){
		$map = array();
		$stype =  I('get.stype','');

		if($stype != ''){
			$map['type'] = (int)$stype;
			$this->assign('stype',$stype);
		}
		$key = I('get.key');
		if(!empty($key)){
			$map['remark'] = array('like',"%$key%");
			$this->assign('key',$key);
		}
		$start = I('get.start');
		if(!empty($start)){ 
			$map['createtime'] = array('egt',strtotime($start));
			$this->assign('start',$start);
		}
		$end = I('get.end');
		if(!empty($end)){$end = $end .' 24:00:00';
			if(isset($map['createtime'])){
				$map['createtime'] =array($map['createtime'],array('elt',strtotime($end)));
			}else{
				$map['createtime'] =array('elt',strtotime($end));
			}
			$this->assign('end',$end);
		}

		return $map;
	}
	/**
	 * 获取排序条件
	 */	
	private function getSort(){
		$sort = I('get.sort/d',-1);
		$str ='createtime desc';
		if($sort>=0){
			switch($sort){
				case 3:$str='createtime desc';
					break;
				case 4:$str='createtime asc';
					break;
			}
			$this->assign('sort',$sort);
		}
		return $str;
	}
	public function createlog(){
		if(!IS_POST) exit();
		$t=I('post.t/','');
		$b=I('post.b','');
		$e=I('post.e','');
		$map=array();
		if(!empty($b) && empty($e)){ 
			$map['createtime'] = array('egt',strtotime($start));
		}else if(empty($b) && !empty($e)){
			$map['createtime'] =array('elt',strtotime($e));
		}else if(!empty($b) && !empty($e)){
			$e = $e .' 24:00:00';
			$map['createtime'] =array('between',array(strtotime($b),strtotime($e)));
		
		}
		if($stype != '') $map['type'] = (int)$stype;
		$list=M('logs')->where($map)->order('createtime desc')->select();

		vendor("PHPExcel.PHPExcel");
		$excel = new \PHPExcel(); 
		$excel->setActiveSheetIndex(0)->setCellValue('A1','操作类型')
		->setCellValue('B1','操作IP')
		->setCellValue('C1','操作行为')
		->setCellValue('D1','操作链接')
		->setCellValue('E1','操作时间')
		->setCellValue('F1','管理员账号');
		$array=array_flip(array('login'=>0,
				'add'=>1,
				'update'=>2,
				'delete'=>3,
				'pass'=>4,
				'sort'=>5,
				'admin'=>6,
				'other'=>7)) ;
		foreach($list as $k=>$v){
			$uinfo=M('admin')->field('name,nickname')->where(array('id'=>$v['uid']))->find();
			$excel->setActiveSheetIndex(0)->setCellValue('A'.($k+2),$array[$v['type']]);
			$excel->setActiveSheetIndex(0)->setCellValue('B'.($k+2),$v['ips']);
			$excel->setActiveSheetIndex(0)->setCellValue('C'.($k+2),$v['info']);
			$excel->setActiveSheetIndex(0)->setCellValue('D'.($k+2),$v['url']);
			$excel->setActiveSheetIndex(0)->setCellValue('E'.($k+2),date('Y-m-d H:i:s',$v['createtime']));
			$excel->setActiveSheetIndex(0)->setCellValue('F'.($k+2),'用户名:'.$uinfo['name'].'#用户昵称:'.$uinfo['nickname']);
		}
		
		$excel->setActiveSheetIndex(0);
		$filename=urlencode(date('Ymd')."_".time());  
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');  
		header('Cache-Control: max-age=0');  
		$objWriter=\PHPExcel_IOFactory::createWriter($excel,'Excel2007');  
		$objWriter = new \PHPExcel_Writer_Excel2007($excel);  
		
		$response = array(  
			'status' => 1,  
			'url' => trim($this->saveExcelToLocalFile($objWriter,$filename),'.')
		);  
		echo json_encode($response);exit();
	}
	private function saveExcelToLocalFile($objWriter,$filename){
		$filePath = './upload/execl/'.$filename.'.xlsx';  
		$objWriter->save($filePath);  
		return $filePath;  
	}
}
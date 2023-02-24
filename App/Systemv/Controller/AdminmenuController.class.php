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
class AdminmenuController extends BaseController{

	protected $ptitle = '后台菜单';
	private   $dbname = 'adminmenu';
	private   $start_parent_id =0;
	private   $TemPath=null;
	public function __construct(){
		// $isDebug = C('DEBUG');
		// if(! $isDebug){
		// 	$this->error('没有管理 “'.$this->ptitle.'” 权限！',U('Index/main'));die;
		// }
		// $this->TemPath='.'.__ROOT__ . substr ( APP_PATH, 1 ).'Systemv/View/';
		parent::__construct();
	}

	/**
	 * 管理
	 */
	public function index(){
		$page = I('get.page/d','1');
		if(IS_AJAX){
			$bll  = D($this->dbname);
			$list = $bll->readTreeArray($this->start_parent_id,true,$this->getWhere(),$this->getSort());
			$re = array();
			$re['aaData'] = $list;
			echo json_encode($re,JSON_UNESCAPED_UNICODE);exit();
		}

		//($pid=0,$where=null,$order=null,$field=null,$depth=0)
		// $this->assign('dlist',$list);
		$this->display();
	}
	/**
	 * 编辑添加
	 */
	public function edit(){
		$id = I('get.id/d',0);
		$back = I('get.url');
		$bll  = D($this->dbname);
		if(IS_POST){
		
			$id   = I('post.id/d',0);
			$m    = I('post.');
			unset($m['parent_id']);
			$m['parent_id']=I('post.parent_id')==-1 ? 0  : I('post.parent_id');
			if(empty($m['name'])){
				$this->error($this->ptitle.'名称不能为空.');
			}
			$m['role_path']=ucfirst(strtolower($m['role_path']));
			if($m['parent_id']==0){
				if(!file_exists($this->TemPath.ucfirst(strtolower($m['role_path'])))){
					$m['isindex']=1;
					$this->copyDir($this->TemPath.'/Survey',$this->TemPath.ucfirst(strtolower($m['role_path'])));
					$rm['name']=$m['name'];
					$rm['permissions']=223;
					$rm['path']=ucfirst(strtolower($m['role_path']));
					$rm['add_time']=time();
					$rm['status']=1;
					M('rolepath')->add($rm);unset($rm);
				}
			}
			if($m['status'] =='-2'){
				$m['role_type'] = 'sys';
			}
			if($id>0){
				$npid = $m['parent_id'];
				$opid = $m['opid'];
				unset($m['parent_id']);unset($m['opid']);

				if($bll->where('id='.$id)->save($m) === false){
					$this->error($this->ptitle."修改失败，您可以重新尝试提交");
				}else{
					if($npid !=$opid){
						$bll->setParentAndDepth(0,$id,$npid);
					}
					$this->addLog('kaifa','修改编号为：'.$id.'的'.$this->ptitle.'修改成功');
					$back = I('get.url');
					$this->success($this->ptitle.'修改成功', empty($back) ? U(CONTROLLER_NAME.'/index') : $back);
				}
			} else {
				if($m['parent_id']>0){
					$pm = $bll->where(array('id' => $m['parent_id']))->find();
					if(empty($pm)){
						$this->error('上一级加载失败');
					}
					$m['depth'] = $pm['depth'].$m['parent_id'].'|';
				}else{
					$m['depth'] = '|0|';
				}
				$m['add_time'] = strtotime($m['add_time']);
				$m['up_time'] = time();
				$m['adder'] = $this->userInfo['id'];

				$id = $bll->data($m)->add();

				if($id>0){
					$this->addLog('kaifa','新增'.$this->ptitle.',编号为：'.$id);
					$this->success($this->ptitle.'添加成功',U(CONTROLLER_NAME.'/index'));
				}else{
					$this->error("新增'.$this->ptitle.'失败，您可以重新尝试提交");
				}
			}
			return ;
		}

		if($id>0){
			$info = $bll->where(array('id' => $id))->find();
			if(empty($info)){
				$this->error($this->ptitle.'不存在', empty($back) ? U(CONTROLLER_NAME.'/index') : $back);
			}
			$this->assign('info',$info);
			$this->assign('title',$this->ptitle.'修改');

		} else {
			$this->assign('title',$this->ptitle.'添加');
		}

		$tree = $bll->where(array('id'=>array('neq',$id),'isindex'=>1))->order('sort desc,id desc')->getField('id,name');
		$this->assign('patree',$tree);
		$roledr = array();
		foreach($this->roletype as $n=>$v){ $roledr[$n] = $n.'('.$v['name'].')'; }
		$roledr['sys'] = 'sys(直接显示)';
		$this->assign('roletype',$roledr);
		$this->assign('id',$id);
		$this->display();
	}
	/**
	* 需要复制的原文件
	* 
	*/
	public function copyDir($src,$dst){
		if(empty($src)) return false;
		$dir=opendir($src);
		mkdir($dst);
		while(false !== ($file = readdir($dir))){
			if($file != '.' && $file != '..'){
				if(is_dir($src.'/'.$file)){
					$this->copyDir($src.'/'.$file,$dst.'/'.$file);
					continue;
				}else copy($src . '/' . $file,$dst . '/' . $file);
			}
		}
		closedir($dir);
	}
	public function operate(){
		if(! IS_POST){exit();}
		$event = I('post.event');
		$uid = $this->userInfo['id'];
		$ids = I('post.chkItem');
		if( is_array($ids)){
			$ids = join(',',$ids);
		}

		if($event == 'delete'){

			if(!is_delete_ids($ids)){
				$this->error("请选择要删除的".$this->ptitle);
			}
			$m['status']=0;
		    $re = M($this->dbname)->where("id in ($ids)")->save($m);
			if($re){
				$this->addLog('delete',"删除".$this->ptitle."IDS:$ids");
				$this->success("编号为“$ids”已经删除！");
			}else{ 
				$this->error($this->ptitle.'删除失败');
			}
		}else if($event == 'order'){
			$listorders = I('post.listorders');
            $bll =M($this->dbname);
            foreach ($_POST['listorders'] as $id => $listorder) {
            	$id = (int) $id;
            	$listorder = (int) $listorder;
                $bll->where(array('id' => $id))->save(array('sort' => $listorder));
            }
			$this->addLog('sort',$ename.'排序'.$this->ptitle.'IDS:'.$ids);
			$this->success('编号为“'.$ids.'”'.$ename.'排序成功');
		}
	}

	/**
	 * 获取查询条件
	 */
	private function getWhere(){
		return null;
	}
	/**
	 * 获取排序条件
	 */	
	private function getSort(){
		return 'sort desc';
	}
	public function getpath(){
		if(!IS_POST) exit();
		$id=I('post.id/d',0);
		$info=M('adminmenu')->where(array('id'=>$id,'status'=>1,'parent_id'=>0))->field('id,role_path')->find();
		exit(json_encode($info,true));
	}
}
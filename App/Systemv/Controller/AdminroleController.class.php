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
class AdminroleController extends BaseController{

	protected $ptitle = '角色';
	private   $dbname = 'adminrole';
	public function index(){
		$page = I('get.page/d','1');
		if(IS_AJAX){
			$re = $this->pageData($this->dbname,$page,null,'id desc');
			exit(json_encode($re,true));
		}
		$this->display();
	}
	/**
	 * 编辑添加
	 */
	public function edit(){
		if(IS_POST){
			$bll = M('adminrole');
			$id = I('post.id/d',0);
			$m = array();
			$m['name'] = I('post.tname');
			$m['isdep']=$this->depId;
			$m['status']=1;
			if($id>0){//修改
				if($bll->where(array('id'=>$id))->save($m) === false){
					$this->error("修改失败，您可以重新尝试提交",U(CONTROLLER_NAME.'/index'));
				}
				//需要给超级管理员自动添加权限
				$this->setPermission($id);
				$this->addLog('admin','修改编号为：'.$id.'的角色成功');
				$this->success($this->ptitle.'已完成修改',U(CONTROLLER_NAME.'/index'));
			} else {
				$m['lasttime'] = time();
				if(empty($m['isdep'])) $m['isdep']=0;
				$id = $bll->data($m)->add();
				
				
				if($id>0){
					$this->setPermission($id,true);
					$this->addLog('admin','新增'.$this->ptitle.',编号为：'.$id);
					$this->success($this->ptitle.'添加成功',U(CONTROLLER_NAME.'/index'));
				}else{
					$this->error('新增'.$this->ptitle.'失败，您可以重新尝试提交');
				}
			}
			return;
		}
		$id = I('get.id/d',0);
		$roleList = M('adminrole')->order('sort desc,id desc')->field('id,name')->select();
		if($id>0){
			$info = array();
			foreach($roleList as $v){
				if($v['id'] == $id){
					$info  = $v;break;
				}
			}
			$this->assign('info',$info);	
		}
		$this->assign('html',$this->createSelectHtml($id));
		$this->assign('id',$id);
		$this->display();
	}

	public function operate(){
		if(! IS_POST){exit();}
		$event = I('post.event');
		$ids = I('post.chkItem');
		if( is_array($ids)){
			$ids = join(',',$ids);
		}else{
			$id = (int)$ids;
		}
		if($event == 'delete'){
			$id = I('post.chkItem/d');
			if($id<=0){
				$this->error("请选择要删除的".$this->ptitle);
			}
			if($id ==1){
				$this->error("超级管理员角色不可以修改");
			}
		    $re = M($this->dbname)->where(array('id'=>$id,'isdefault'=>0))->delete();
			if($re){
				M('permission')->where(array('rid'=>$id))->delete();
				$this->addLog('delete',"删除".$this->ptitle."ID:$id");
				$this->success("编号为“$id”已经删除！");
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
			$this->addLog('update',$ename.'排序'.$this->ptitle.'IDS:'.$ids);
			$this->success('编号为“'.$ids.'”'.$ename.'排序成功');
		}
	} 
	private function createSelectHtml($roleid=0){
		$paths=D('menu')->readTreeArray(0,false,array('status'=>1,'delete'=>0,'isdep'=>$this->depId),'sort desc,id asc','id,name,pid,status,type,depth');
		if(empty($paths)) return false;
		//读取当前栏目的权限
		$tps   = null;
		if($roleid>0)  $tps =M('permission')->where(array('rid'=>$roleid))->field('mid,power')->select();
		$html='';
		foreach($paths as $n=>$v){
			$marray=json_decode(M('menupower')->where(array('mid'=>$v['id']))->getField('type'));
			$html .='<div class="space-4"></div><div class="form-group"><div class="col-sm-2"></div><label class="col-sm-2 control-label no-padding-right" for="form-field-2" style="text-align:left;height:30px; line-height:30px;" > '.$v['name'].'</label><div class="col-sm-1" style="height:30px; line-height:30px;"><label style=" margin-top:4px;" class="help-inline">';
			if($v['isonly'] == 0)
				$html.='<input type="checkbox"  value="'.$v['id'].'" class="ace role_one_cbk"><span class="lbl" style=" margin-top:4px;"></span></label>';
			$html.='</div><div class="col-sm-7 checkbox-div" style="height:30px; line-height:30px;">';
			
			if($v['pid'] != 0){
				$narray=['select'=>0];
				foreach($this->roletype as $k=>$r){
					if(in_array($k,$marray)){
						$status='';
						foreach($tps as $key=>$val){ 
							if($v['id'] == $val['mid'] && in_array($k,json_decode($val['power']))){
								$status='checked';
							}
						}
						$html .='<label class="help-inline"><input name="chk_ids[]" type="checkbox" value="'.$k.'_'.$v['id'].'" class="ace" '.$status .'><span class="lbl">'.$r['name'].'</span>	</label>';
					}
				}
			}
			$html .='</div></div>';		
		}
		return $html;
	}

	private function setPermission($id,$isnew=false){
		if($id<=0) return false;
		$list =  M('menu')->where('status=1 and `delete`=0')->order('sort asc')->select();
		if(empty($list)){
			return;
		}
		$bll = M('permission');
		if(!$isnew) $bll->where(array('rid'=>$id))->delete();
		$carr =I('post.chk_ids');
		if(empty($carr)) return;
		$n=[];
		foreach($list as $v){
			$tem = $v['id'];
			foreach($carr as $c){
				$arr = string_split($c,'_');
				if(count($arr) != 2) continue;
				if($tem == $arr[1]){
					 $n[$tem]['power'][]=$arr[0];
				}
			}
		}
		$i=0;
		foreach($n as $k=>$v){
			$m[$i]['mid']=$k;
			$m[$i]['power']=json_encode($v['power']);
			$m[$i]['rid']=$id;
			$m[$i]['isdefault']=0;
			$i++;
		}
		return	$bll->addAll($m);
	}
}
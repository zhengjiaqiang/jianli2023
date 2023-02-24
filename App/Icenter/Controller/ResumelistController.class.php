<?php
namespace Icenter\Controller;
use Icenter\Common\BaseController;
class ResumelistController extends BaseController {
    public function index(){
      $list=M('resumelist')->where(['uid'=>$this->userId,'status'=>['neq',8]])->select();
      foreach($list as $k=>$v){
          $list[$k]['posname']=M('position')->where(['id'=>$v['rid']])->find();
      } 
      $depart=M('department')->where(['status'=>1])->getField('id,name',true);
      //读取职位
    //   print_r($list);
      $station= M('station')->where(['status'=>1])->getField('id,name');
 
      $this->assign('depart',$depart);
      $this->assign('station',$station);
      $this->assign('list',$list);
      $this->display();
    }
}
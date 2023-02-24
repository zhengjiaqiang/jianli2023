<?php
// +----------------------------------------------------------------------
// | 超管基类
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2017 All rights reserved.
// +----------------------------------------------------------------------
// | Author: 雪地里的松鼠
// +----------------------------------------------------------------------

namespace Icenter\Common;
use Think\Controller;
class BaseController extends Controller {
   protected $indexView;
   protected $userInfo;
   protected $userId;
   protected $onePageRow=15;
   protected $volunteer=['请选择志愿','第一志愿','第二志愿','第三志愿'];
   protected $qinfo=null;
   protected $issubmit=false;
   protected $nead=null;
   protected $uploaded=false;
	protected function _initialize() {
       //初始系统配置
       D('system')->initSet();
       //开启大类型
       if(C('SYS_SET.isstartbigtype')) {
        $this->indexView='type';
        $where['btime']=['elt',time()];
        $where['etime']=['egt',time()];
        //var_dump(D('bigtype')->getList($where));die;
        $this->assign('btype',D('bigtype')->getList($where));
       }else $this->indexView='index';
       $this->assign('volunteer',$this->volunteer);
       if($this->islogin()) $this->userId=$this->userInfo['id'];
        //获取当前的
       $this->assign('max',C('SYS_SET.maxsendresume'));
       //读取总的简历数
       $this->assign('uinfo',$this->userInfo);
       $this->assign('allcount',M('position')->where(['status'=>1,'isdel'=>0,'btime'=>['ELT',time()],'etime'=>['EGT',time()]])->count()) ;
       $this->assign('webtitle',C('SYS_SET.webtitle'));
       $this->assign('max',C('SYS_SET.MAX_FILE_NUMBER'));
       $this->assign('issubmit',$this->issubmit=D('Resumeuser')->isDownResume($this->userId)==false ? 0 :1);
       //获取当前需要的
       $colums=implode(',',array_unique(array_column(C('STATIC_PARENT'),'join')));
       $this->nead=M('uremusemessage')->where(['uid'=>$this->userId])->field($colums)->find();
       $fileclass=M('file');
       $guipei=1;
       if($this->nead['1etmnffrzs8'] == '已完成')
         $guipei=$fileclass->where(['parentid'=>3,'uid'=>$this->userId])->count();
       if($this->nead['2izv9jv98uk'] == '是')
         $yjs=$fileclass->where(['parentid'=>4,'uid'=>$this->userId])->count();
       else
         $yjs=$fileclass->where(['parentid'=>5,'uid'=>$this->userId])->count();

       $this->assign('istrue',$this->uploaded=($guipei && $yjs ? 1 : 0));
       //读取开启的问卷调查
       $this->qinfo=null;
       $this->assign('qinfo',$this->qinfo);
       $this->assign('isquestion',$this->isSaveQuestion());

    }
    protected function islogin(){
        $this->userInfo = D('User/User')->isLogin();
        if( is_array($this->userInfo)) return true;
        return false;
    }
    protected function isSaveQuestion(){
      return empty($this->qinfo) ? true :  M('questionnairee')->where(['uid'=>$this->userId,'qid'=>$this->qinfo['id']])->count();
    }
    protected function pageData($obj,$currPage,$where=null,$order=null,$field=null,$join=false,$iscount=false){
		if($currPage<=0){$currPage = 1;}
		$re = array();
		$bll = M($obj);
		$re['onePageRow'] = $this->onePageRow;		
		$re['currPage'] = $currPage;
		if($join  == false) $re['rowCount'] = $bll->where($where)->count();
      else $re['rowCount'] = $bll->where($where)->join($join)->count();
		$re['pageCount'] =ceil($re['rowCount']/$re['onePageRow']); 
		if($currPage<=$re['pageCount']){
			$re['aaData'] =  $join == false ? $bll->where($where)->order($order)->field($field)->page($currPage,$this->onePageRow)->select() : $bll->where($where)->order($order)->field($field)->page($currPage,$this->onePageRow)->join($join)->select();
		}
		if(empty($re['aaData'])) $re['aaData'] =[];
		$re['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
		$re['sEcho'] = I('get.sEcho/d','1');
		$re['iTotalRecords'] = $re['rowCount'];
		$re['iTotalDisplayRecords'] = $re['rowCount'];
		return $re;
	}
}

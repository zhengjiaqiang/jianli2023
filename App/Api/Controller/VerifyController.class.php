<?php
namespace Api\Controller;
use Think\Controller;
use Common\Libs\Util\VerifyImg;
use  Common\Libs\Util\Sms;
class VerifyController extends Controller {
	public function index(){

        $depart=M('department')->where(['status'=>1,'isdel'=>0])->order('sort desc,id asc')->getField('id,name',true);
        //var_dump($depart);die;
		ob_clean();
		$id=I('get.type',1,'int');
		$height=I('get.h',0,'int');
		$width=I('get.w',0,'int');
		$f=I('get.f',24,'int');		
		$verify = new VerifyImg(array('length'=>4,'fontSize'=>$f,'useCurve'=>true,'imageH'=>$height,'imageW'=>$width));
		$verify->entry($id);
	}
	public function useTcode(){
		ob_end_clean();
		$id=I('get.type',1,'int');
		$height=I('get.h',40,'int');
		$width=I('get.w',85,'int');
		$s= new \Think\Verify(array('length'=>4,'fontSize'=>30,'imageH'=>$height,'imageW'=>$width));
		$s->entry($id);	
	}
	public function verify(){

		ob_end_clean();
			$verify = new \Think\Verify();
			$verify->entry();
	}


}
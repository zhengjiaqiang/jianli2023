<?php
namespace Icenter\Controller;
use Icenter\Common\BaseController;

class PrviewController extends BaseController {
	private $bll=null;
	public function __construct(){
		parent::__construct();
		$this->bll=M('resumelist');
		if(!in_array(strtolower( ACTION_NAME),['index','check'])) !session('?loginuser') ? redirect('/icenter/prview/index/id/'.I('get.id/d')) : '';
	}
    public function index(){
		$id=I('get.id/d',0);
        if(IS_AJAX){
			$page=I('get.p/d',0);
			$re = $this->pageData('resumelist',$page,$this->getWhere(),$this->getSort(),'list.*,message.*,status.status as biaoji,pos.depid,pos.name,dep.name as dname','as list left join hh_uremusemessagecache as message on message.lid = list.id left join hh_resumestatus as status on list.id=status.rid left join hh_position as pos on list.rid=pos.id left join hh_department as dep on dep.id = pos.depid');
			$istrue=true;
			foreach($re['aaData'] as $k=>$v){
				$re['aaData'][$k]['name']=$v['dname'];
				$re['aaData'][$k]['depname']=$v['name'];
				$re['aaData'][$k]['show']=M('type')->where(['id'=>$v['status']])->getField('show');
				$re['aaData'][$k]['volunteer']=$this->volunteer[$v['vid']];
				if(empty($v['biaoji'])) $istrue=false;
			}if(!empty($re['aaData'])) $re['aaData'][0]['istrue']=$istrue;
			exit(json_encode($re,true));
		}
		$info=M('emaillist')->where(['id'=>$id])->find();
		if(empty($info)) $this->error('当前分配简历信息不存在，请联系管理员！');
		if($info['endtime'] < time()) $this->error('当前分配简历信息已经过期请联系管理！');
		$this->assign('islogin',session('?loginuser.'.$id) == false ? 0 : 1);
		//判断是否有操作
		// $emailist=M('emailuserlist')->where(['eid'=>['in',session('loginuser.'.session('loginuser.id'))]])->field('lid')->select();

		$dlist=M('department')->where(['status'=>1,'isdel'=>0])->order('sort desc,id desc')->getField('id,name',true);
		$plist=M('position')->where(['status'=>1,'isdel'=>0])->getField('id,name',true);
		$this->assign('plist',$plist);
		$this->assign('dlist',$dlist);
		$this->assign('id',$id);
        $this->display();
    }
    private function getWhere(){
		$map = [];
		$getArr=I('get.');
		$map['list.id']=['in',session('loginuser.'.session('loginuser.id'))];
		!empty($getArr['dep']) ? $map['dep.id']=$getArr['dep']: '';
		!empty($getArr['pos']) ? $map['pos.id']=$getArr['pos']: '';
		!empty($getArr['biaoqian']) ? ($getArr['biaoqian']==1 ? $map['status.status']=['exp','is null'] : $map['status.status']=['exp','is not null']) : '';
		unset($getArr['id']);unset($getArr['dep']);unset($getArr['type']);unset($getArr['sEcho']);unset($getArr['page']);unset($getArr['pos']);unset($getArr['biaoqian']);
		foreach($getArr as $k=>$v) $map['message.'.$k]=$v;
		$map['list.status']=11;
		// print_r($map);
		return $map;
	}
	/**
	 * 获取排序条件
	 */	
	private function getSort(){
		$sort = I('get.sort/d',-1);
		$str ='list.uid desc,list.vid asc ';
		return $str;
	}
	public  function check(){
		if(!IS_POST) exit();
		$passwd=I('post.passwd');
		$id=I('post.id/d',0);
		$info=M('emaillist')->where(['id'=>$id,'password'=>md5($passwd)])->find();
		if(empty($info)) $this->error('当前密码错误，请重新输入！');
		else{
			//读取当前
			$session['id']=$id;
			$session[$id]=implode(',',M('emailuserlist')->where(['eid'=>$id])->getField('lid',true));
			$session['uid']=M('emaillist')->where(['e.id'=>$session['id']])->join('as e left join hh_emailuser as user on e.touser = user.id')->getField('user.id');
			session('loginuser',$session);
			$this->success('密码输入成功！');
		}
	}
	public function resume(){
		$pid=I('get.id/d',0);
		$this->views($pid);
		$this->display();
	}
	private function views($pid){
		$info=$this->bll->where(['id'=>$pid])->find();
		if(empty($info)) $this->error('当前用户投递的简历不存在！');
		$this->bll->where(['id'=>$pid])->save(['isread'=>1]);
		$resumelist=M('rusercache')->where(['rid'=>$pid,'uid'=>$info['uid']])->order('sort desc')->field('config,htmlconfig')->select();
        $m=[]; 
        $ques=[];
        foreach($resumelist as $k=>$v) {
            parse_str(base64_decode($v['config']),$u);
            $m=array_merge($m,(array)$u);
            $ques[$k]=$v['htmlconfig'];
        }
        $this->assign('info',$info);
        $this->assign('user',$m);
		$this->assign('ques',$ques);
		$this->assign('pid',$pid);
	}
	public function implement(){
		if(!IS_POST) exit();
		$list=M('resumestatus')->where(['rid'=>['in',session('loginuser.'.session('loginuser.id'))]])->select();
		$typeBll=M('type');
		foreach($list as $k=>$v){
			$intype=$typeBll->where(['id'=>$v['status']])->field('id,isend,isreturn,isadd,step')->find();
			if(!$intype['isreturn'] && !$intype['isend']){
				//读取当前的状态
				$next=$this->bll->where(['id'=>$v['rid']])->getField('status');
				$step=$typeBll->where(['id'=>$next])->getField('step');
				$instatus= $intype['isadd']==0  ? 8 : $step;
				$this->bll->where(['id'=>$v['rid']])->save(['status'=>$instatus]);
				M('resumestatus')->where(['rid'=>$v['rid']])->delete();
				$this->bll->where(['id'=>$v['rid']])->save(['isread'=>0]);
			}	
		}
		$this->success('当前标签执行成功....');
	}
	public function show(){
		// if(!IS_GET) exit();
		$ids=I('get.id');
		$p=I('get.p/d',0);
		$array = array_filter(explode('_',$ids));
		//读取当前第一项
		$info=$this->bll->where(['id'=>$array[$p]])->find();
		if(empty($info)) $this->error('当前用户投递的简历不存在！');
		// $this->views($ids[$p]);
		$this->ajaxReturn(['status'=>1,'id'=>$array[$p],'next'=>$array[$p+1],'ids'=>$ids,'message'=>'当前用户投递存在，实例化页面','p'=>$p]);
		// $this->success('当前用户投递存在，实例化页面','');
	}
	public function next(){
		$id=I('get.id/d');
		$info=$this->bll->where(['id'=>$id])->find();
		if(empty($info)) $this->error('当前用户投递的简历不存在！');
		$this->views($id);
		$p=I('get.p/d',0);
		$ids=I('get.ids');
		$array = array_filter(explode('_',$ids));
		$this->assign('pid',$id);
		$this->assign('islast',($id == $array[count($array)-1]) ? true : false);
		$this->assign('ids',$ids);
		$this->assign('p',$p+1);
		$this->display();
	}
	public function isnext(){
		if(!IS_POST) exit();
		$status=I('post.status','');
		$id=I('post.rid/d','');
		if(!in_array($status,[10,8])) $this->error('当前状态不存在，请刷新重试！');
		$info=$this->bll->where(['id'=>$id])->find();
		if(empty($info)) $this->error('当前简历不存在，请刷新重试！');unset($info);
		$info=M('resumestatus')->where(['rid'=>$id])->find();
		if($status == 8){
			if(empty(I('post.reson',null,'trim'))) $this->error('当前拒绝理由未填写！');
			 M('emailuserlist')->where(['lid'=>$id,'eid'=>session('loginuser.id')])->save(['reson'=>I('post.reson')]);
		}else  M('emailuserlist')->where(['lid'=>$id,'eid'=>session('loginuser.id')])->save(['reson'=>null]);
		$m['lasttime']=time();
		$m['rid']=$id;
		$m['status']=$status;
		if(empty($info)) M('resumestatus')->add($m);
		else M('resumestatus')->where(['rid'=>$id])->save($m);
		$this->success('当前简历标记成功！');
	}
	public function word(){
		if(!IS_POST) exit();
		$PHPWord=new \PhpOffice\PhpWord\PhpWord();
		$section = $PHPWord->createSection();
		$center=['alignment' =>\PhpOffice\PhpWord\SimpleType\Jc::CENTER];
		$PHPWord->addFontStyle('rStyle', array('bold'=>true,'color'=>'000000','size'=>16));
		$PHPWord->addParagraphStyle('pStyle', array('align'=>'center'));
		$section->addTextBreak(1);
		$styleTable = array('borderSize'=>6, 'borderColor'=>'006699', 'cellMargin'=>80);
		$list=$this->bll->where(['id'=>['in',session('loginuser.'.session('loginuser.id'))]])->select();
		foreach($list as $v){
			$resumelist=M('rusercache')->where(['rid'=>$v['id']])->order('sort desc')->field('config,htmlconfig')->select();
			$m=[]; 
			foreach($resumelist as $k=>$v) {
				parse_str(base64_decode($v['config']),$u);
				$m=array_merge($m,(array)$u);
			}
			$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
			$section->addText('应聘职位:'.$m['gangwei'].'        投递日期:'.$m['addtime'], 'rStyle',$center);
			$section->addText('简历信息', 'rStyle',$center);
			$section->addTextBreak(1);
			$table = $section->addTable('myOwnTableStyle');
			$fontStyle = array('bold'=>true, 'align'=>'center');
			$table->addRow();
			$table->addCell(4000)->addText("姓名",$fontStyle);
			$table->addCell(6000)->addText($m['wwhqywgur0'],$fontStyle);
			$table->addCell(2000, ['gridSpan'=>2,'vMerge'=>'restart'])->addImage( empty($m['selfpic'])? './static/index/images/file_bg.jpg' : trim($m['selfpic']),['width'=>150,'height'=>100,'align'=>'center']);
			$table->addRow();
			$table->addCell(2000)->addText("性别",$fontStyle);
			$table->addCell(3000)->addText($m['xlb0ix53hv'],$fontStyle);
			$table->addCell(2000, ['gridSpan'=>2,'vMerge'=>'restart','vMerge'=>'continue']);
			$table->addRow();
			$table->addCell(2000)->addText("身份证号",$fontStyle);
			$table->addCell(3000)->addText($m['26jmr8kmsz'],$fontStyle);
			$table->addCell(2000, ['gridSpan'=>2,'vMerge'=>'restart','vMerge'=>'continue']);
			$table->addRow();
			$table->addCell(2000)->addText("出生日期",$fontStyle);
			$table->addCell(3000)->addText($m['2da9x8ajf56'],$fontStyle);
			$table->addCell(2000, ['gridSpan'=>2,'vMerge'=>'restart','vMerge'=>'continue']);
			$table->addRow();
			$table->addCell(2000)->addText("民族",$fontStyle);
			$table->addCell(3000)->addText($m['vpyfqb4s85'],$fontStyle);
			$table->addCell(2000, ['gridSpan'=>2,'vMerge'=>'restart','vMerge'=>'continue']);
			$table->addRow();
			$table->addCell(2000)->addText("政治面貌",$fontStyle);
			$table->addCell(3000)->addText($m['1mb0ylvappl'],$fontStyle);
			$table->addCell(2000)->addText("婚姻状况",$fontStyle);
			$table->addCell(3000)->addText($m['1hryxiw6t37'],$fontStyle);
			$table->addRow();
			$table->addCell(2000)->addText("户口所在地",$fontStyle);
			$table->addCell(3000,['vMerge'=>'restart'])->addText($m['province'].'-'.$m['city'],$fontStyle);
			$table->addCell(2000)->addText("住址",$fontStyle);
			$table->addCell(3000)->addText($m['k7855fgiqp'],$fontStyle);
			$table->addRow();
			$table->addCell(2000)->addText("应届生",$fontStyle);
			$table->addCell(3000)->addText($m['2izv9jv98uk'],$fontStyle);
			$table->addCell(4000)->addText("现工作单位",$fontStyle);
			$table->addCell(6000)->addText($m['2izv9jv928ukw'],$fontStyle);
			$table->addRow();
			$table->addCell(2000)->addText("最高学历",$fontStyle);
			$table->addCell(3000)->addText($m['2htc2bme4keq8'],$fontStyle);
			$table->addCell(2000)->addText("邮编",$fontStyle);
			$table->addCell(3000)->addText($m['2fcppwd33z5'],$fontStyle); 
			$table->addRow();
			$table->addCell(2000)->addText("毕业院校",$fontStyle);
			$table->addCell(3000)->addText($m['1wqxq7r9dpdyg'],$fontStyle);
			$table->addCell(2000)->addText("毕业专业",$fontStyle);
			$table->addCell(3000)->addText($m['dpa1eqch5bsv'],$fontStyle);
			$table->addRow();
			$table->addCell(2000)->addText("手机",$fontStyle);
			$table->addCell(3000)->addText($m['2ywi8z97l7'],$fontStyle);
			$table->addCell(2000)->addText("Email",$fontStyle);
			$table->addCell(3000)->addText($m['qspywhdn8w'],$fontStyle);
			$table->addRow();
			$table->addCell(2000)->addText("有无其它慢性病 传染病",$fontStyle);
			$table->addCell(3000,['gridSpan'=>3,'vMerge'=>'restart'])->addText($m['es9mn0ww5t'].''.($m['es9mn0ww5t']=='是' ? '/'.$m['wwhqywgur1'] : ''),$fontStyle);
			
			$table->addRow();
			$table->addCell(2000)->addText("健康情况",$fontStyle);
			$table->addCell(3000)->addText($m['1flzix4x2ln'],$fontStyle);
			$table->addCell(2000)->addText("岗位调剂",$fontStyle);
			$table->addCell(3000)->addText($m['19payq4d2d8'],$fontStyle);
			unset($table);
			$JT=$this->keySearchInArray('JT',$m);
			if($JT && !empty($JT['JT0'][0])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('家庭主要成员情况', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(2000)->addText("称谓",$fontStyle);
				$table->addCell(3000)->addText('姓名',$fontStyle);
				$table->addCell(2000)->addText("工作单位",$fontStyle);
				$table->addCell(3000)->addText("职务",$fontStyle);
				for($i=0;$i<count(current($JT));$i++){
					$table->addRow();
					$table->addCell(2000)->addText($JT['JT0'][$i],$fontStyle);
					$table->addCell(3000)->addText($JT['JT1'][$i],$fontStyle);
					$table->addCell(2000)->addText($JT['JT2'][$i],$fontStyle);
					$table->addCell(3000)->addText($JT['JT3'][$i],$fontStyle);
				}
			}
	
			$QS=$this->keySearchInArray('QS',$m);
			if($QS && $m['es9mn0ww5t']=='是'){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('亲属受雇情况', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(2000)->addText("关系",$fontStyle);
				$table->addCell(3000)->addText('姓名',$fontStyle);
				$table->addCell(2000)->addText("所在科室",$fontStyle);
				for($i=0;$i<count(current($QS));$i++){
					$table->addRow();
					$table->addCell(2000)->addText($QS['QS0'][$i],$fontStyle);
					$table->addCell(3000)->addText($QS['QS1'][$i],$fontStyle);
					$table->addCell(2000)->addText($QS['QS2'][$i],$fontStyle);
				}
			}
	
			$jy=$this->keySearchInArray('JY',$m);
			if($jy  && !empty($jy['JY0'][0])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('教育背景（从高中起填）', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(2000)->addText("时间",$fontStyle);
				$table->addCell(3000)->addText('学历',$fontStyle);
				$table->addCell(2000)->addText("教学性质",$fontStyle);
				$table->addCell(3000)->addText("职务",$fontStyle);
				$table->addCell(3000)->addText("学校",$fontStyle);
				$table->addCell(2000)->addText("专业",$fontStyle);
				for($i=0;$i<count(current($jy));$i++){
					$table->addRow();
					$table->addCell(2000)->addText($jy['JY0'][$i],$fontStyle);
					$table->addCell(3000)->addText($jy['JY1'][$i],$fontStyle);
					$table->addCell(2000)->addText($jy['JY2'][$i],$fontStyle);
					$table->addCell(3000)->addText($jy['JY3'][$i],$fontStyle);
					$table->addCell(3000)->addText($jy['JY4'][$i],$fontStyle);
					$table->addCell(3000)->addText($jy['JY5'][$i],$fontStyle);
				}
			}
		
			$section->addTextBreak(3);
			$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
			// $section->addText('最高学分', 'rStyle');
			$table = $section->addTable('myOwnTableStyle');
			$fontStyle = array('bold'=>true, 'align'=>'center');
			$table->addRow();
			$table->addCell(2000)->addText("最高学历绩点",$fontStyle);
			$table->addCell(3000)->addText($m['4cj1d257wo'],$fontStyle);
			$table->addCell(2000)->addText("成绩排名",$fontStyle);
			$table->addCell(3000)->addText($m['1vwsy4zc3o'],$fontStyle);
			$table->addCell(2000)->addText("证明人/联系方式",$fontStyle);
			$table->addCell(3000)->addText($m['18wy38f2fha'],$fontStyle);
			$table->addRow();
			$table->addCell(2000)->addText("次高学历绩点",$fontStyle);
			$table->addCell(3000)->addText($m['2jooj3xjbww'],$fontStyle);
			$table->addCell(2000)->addText("成绩排名",$fontStyle);
			$table->addCell(3000)->addText($m['1b2xhu6p1m8'],$fontStyle);
			$table->addCell(2000)->addText("证明人/联系方式",$fontStyle);
			$table->addCell(3000)->addText($m['ibvkcv09o8'],$fontStyle);
			$section->addTextBreak(3);
			
			$GZ=$this->keySearchInArray('GZ',$m);
			if($GZ && !empty($GZ['GZ0'][0])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('工作经历', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(2000)->addText("时间",$fontStyle);
				$table->addCell(3000)->addText('单位',$fontStyle);
				$table->addCell(2000)->addText("所在部门",$fontStyle);
				$table->addCell(5000)->addText("工作内容",$fontStyle);
				$table->addCell(2000)->addText("联系方式",$fontStyle);
				for($i=0;$i<count(current($GZ));$i++){
					$table->addRow();
					$table->addCell(2000)->addText($GZ['GZ0'][$i],$fontStyle);
					$table->addCell(3000)->addText($GZ['GZ1'][$i],$fontStyle);
					$table->addCell(2000)->addText($GZ['GZ2'][$i],$fontStyle);
					$table->addCell(2000)->addText($GZ['GZ3'][$i],$fontStyle);
					$table->addCell(2000)->addText($GZ['GZ4'][$i],$fontStyle);
				}
			}
		
			$sx=$this->keySearchInArray('SX',$m);
			if($sx && !empty($sx['SX0'][0])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('实习经历', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(2000)->addText("时间",$fontStyle);
				$table->addCell(4000)->addText('单位',$fontStyle);
				$table->addCell(2000)->addText("实习岗位",$fontStyle);
				$table->addCell(4000)->addText("工作内容",$fontStyle);
				$table->addCell(2000)->addText("联系方式",$fontStyle);
				for($i=0;$i<count(current($sx));$i++){
					$table->addRow();
					$table->addCell(2000)->addText($sx['SX0'][$i],$fontStyle);
					$table->addCell(4000)->addText($sx['SX1'][$i],$fontStyle);
					$table->addCell(2000)->addText($sx['SX2'][$i],$fontStyle);
					$table->addCell(4000)->addText($sx['SX3'][$i],$fontStyle);
					$table->addCell(2000)->addText($sx['SX3'][$i],$fontStyle);
				}
			}
			$ZY=$this->keySearchInArray('ZY',$m);
			if($ZY && $m['1etmnffrzs8']=='是'){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('住院培训', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(2000)->addText("时间",$fontStyle);
				$table->addCell(8000)->addText('培训单位',$fontStyle);
				$table->addCell(8000)->addText("培训专业",$fontStyle);
				$table->addCell(2000)->addText("是否取得证书",$fontStyle);
				for($i=0;$i<count(current($ZY));$i++){
					$table->addRow();
					$table->addCell(2000)->addText($ZY['ZY0'][$i],$fontStyle);
					$table->addCell(8000)->addText($ZY['ZY1'][$i],$fontStyle);
					$table->addCell(8000)->addText($ZY['ZY2'][$i],$fontStyle);
					$table->addCell(2000)->addText($ZY['ZY3'][$i],$fontStyle);
				}
			}
	
			$section->addTextBreak(3);
			$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
			// $section->addText('最高学分', 'rStyle');
			$table = $section->addTable('myOwnTableStyle');
			$fontStyle = array('bold'=>true, 'align'=>'center');
			$table->addRow();
			$table->addCell(2000)->addText("英语等级",$fontStyle);
			$table->addCell(3000)->addText($m['25svm1v86i0'],$fontStyle);
			$table->addCell(2000)->addText("口语水平",$fontStyle);
			$table->addCell(3000)->addText($m['1i65d9ted4a'],$fontStyle);
			$table->addRow();
			$table->addCell(2000)->addText("计算机等级",$fontStyle);
			$table->addCell(3000)->addText($m['2226ggmk69m'],$fontStyle);
			$table->addCell(2000)->addText("熟练程度",$fontStyle);
			$table->addCell(3000)->addText($m['289hugsg66w'],$fontStyle);
			$QTPX=$this->keySearchInArray('QTPX',$m);
			if($QTPX && !empty($QTPX['QTPX0'][0])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('其他培训与证书', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(4000)->addText("时间",$fontStyle);
				$table->addCell(8000)->addText('培训/证书',$fontStyle);
				$table->addCell(8000)->addText("培训/发证机构",$fontStyle);
				for($i=0;$i<count(current($QTPX));$i++){
					$table->addRow();
					$table->addCell(4000)->addText($QTPX['QTPX0'][$i],$fontStyle);
					$table->addCell(8000)->addText($QTPX['QTPX1'][$i],$fontStyle);
					$table->addCell(8000)->addText($QTPX['QTPX2'][$i],$fontStyle);
				}
			}
	
			$FB=$this->keySearchInArray('FB',$m);
			if($FB && !empty($FB['FB0'][0])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('发表论文', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(5000)->addText("题目",$fontStyle);
				$table->addCell(6000)->addText('论文名称、期刊名称（卷、期、页）',$fontStyle);
				$table->addCell(2000)->addText("影响因子",$fontStyle);
				$table->addCell(2000)->addText("排名",$fontStyle);
				for($i=0;$i<count(current($FB));$i++){
					$table->addRow();
					$table->addCell(5000)->addText($FB['FB0'][$i],$fontStyle);
					$table->addCell(6000)->addText($FB['FB1'][$i],$fontStyle);
					$table->addCell(2000)->addText($FB['FB2'][$i],$fontStyle);
					$table->addCell(2000)->addText($FB['FB3'][$i],$fontStyle);
				}
			}
			$KT=$this->keySearchInArray('KT',$m);
			if($KT && !empty($KT['KT0'][0])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('课题', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(3000)->addText('时间',$fontStyle);
				$table->addCell(3000)->addText("课题名称",$fontStyle);
				$table->addCell(2000)->addText("级别",$fontStyle);
				$table->addCell(2000)->addText("经费",$fontStyle);
				$table->addCell(2000)->addText("第几完成人",$fontStyle);
				$table->addCell(2000)->addText("批准部门",$fontStyle);
				for($i=0;$i<count(current($KT));$i++){
					$table->addRow();
					$table->addCell(2000)->addText($KT['KT0'][$i],$fontStyle);
					$table->addCell(3000)->addText($KT['KT1'][$i],$fontStyle);
					$table->addCell(2000)->addText($KT['KT2'][$i],$fontStyle);
					$table->addCell(2000)->addText($KT['KT3'][$i],$fontStyle);
					$table->addCell(2000)->addText($KT['KT4'][$i],$fontStyle);
					$table->addCell(2000)->addText($KT['KT5'][$i],$fontStyle);
				}
			}
			$HJ=$this->keySearchInArray('JFQK',$m);
			if($HJ  && !empty($HJ['JFQK0'][0])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('奖惩情况', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(8000)->addText("取得年月",$fontStyle);
				$table->addCell(4000)->addText('奖励/荣誉',$fontStyle);
				$table->addCell(8000)->addText('奖惩情况',$fontStyle);
				for($i=0;$i<count(current($HJ));$i++){
					$table->addRow();
					$table->addCell(8000)->addText($HJ['JFQK0'][$i],$fontStyle);
					$table->addCell(4000)->addText($HJ['JFQK1'][$i],$fontStyle);
					$table->addCell(8000)->addText($HJ['JFQK2'][$i],$fontStyle);
				}
			}
			if(!empty($m['1z3u6esgam2'])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('奖惩情况内容', 'rStyle');
				$section->addTextBreak(1);
				$table = $section->addTable('myOwnTableStyle');
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(20000,['gridSpan'=>5,'vMerge'=>'restart'])->addText($m['1z3u6esgam2'],$fontStyle);
			}
			if(!empty($m['ufvilpx5hd'])){
				$section->addTextBreak(3);
				$PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
				$section->addText('期望薪资 月薪（元）', 'rStyle');
				$table = $section->addTable('myOwnTableStyle');
				$section->addTextBreak(1);
				$fontStyle = array('bold'=>true, 'align'=>'center');
				$table->addRow();
				$table->addCell(20000,['gridSpan'=>5,'vMerge'=>'restart'])->addText($m['ufvilpx5hd'],$fontStyle);
			}
			$section->addPageBreak();
		}
		$objWrite = \PhpOffice\PhpWord\IOFactory::createWriter($PHPWord, 'Word2007');
		$filename='/upload/docx/'.time().'.docx';
		$objWrite->save('.'.$filename);
		$this->success('正在下载...',$filename);
	}
	private function keySearchInArray($key,$array=[]){
		$list=[];
		foreach($array as $k=>$v){
			if (strstr($k , $key ) !== false ){
				$ex=explode('_',$k);
				$list[$ex[0].$ex[1]][]=$v;
			}
		}
		return array_merge($list);
	}
	public function excel(){
		if(!IS_POST) exit;
		$list=$this->bll->where(['id'=>['in',session('loginuser.'.session('loginuser.id'))]])->select();
		$cache=M('uremusemessagecache');
		$config=D('system')->where(['name'=>'download'])->getField('value');
		$array=array_filter(explode('|',$config));
		$field=[];
		$header=['申请时间','申请科室','申请职位'];
		foreach($array as $k=>$v){
			list($key,$value)=explode(':',$v);
			$field[$key]=$value;
		}
		$nlist=[];
		foreach($list as $k=>$v){
			$message=$cache->where(['lid'=>$v['id']])->field('addtime,'.implode(',',array_keys($field)))->find();
			$depinfo=M('position')->where(['id'=>$v['rid']])->find();
			$dname=M('department')->where(['id'=>$depinfo['depid']])->getField('name');
			$depname=$depinfo['name'];
			foreach($message as $key=>$value){
				if($k == 0 && in_array($key,array_keys($field))) array_push($header,$field[$key]);
				
				$nlist[$k][$key]=' '.$value;
				
				$nlist[$k]['addtime']=date('Y-m-d',time());
				$nlist[$k]['dep']=$dname;
				$nlist[$k]['zhiwei']=$depname;
			}
		}
		$file=dataToFile($header,$nlist);
		$this->ajaxReturn(['status'=>1,'info'=>'正在下载...','url'=>$file]);
	}
	public function forward(){
		// if(!IS_AJAX) exit();
		$ids=I('get.ids');
		//读取科室
		$this->assign('ids',$ids);
		$list=M('emailuser')->where(['status'=>1,'isdel'=>0,'id'=>['neq',session('loginuser.uid')]])->field('id,department,name')->select();
		$this->assign('list',$list);
		$this->display();
	}
	public function setforward(){
		if(!IS_POST) exit();
		$ids=I('post.ids');
		$uid=I('post.uid/d');
		$email=M('emailuser')->where(['status'=>1,'isdel'=>0,'id'=>$uid])->find();
		if(empty($email)) $this->error('当前接收人不存在，请刷新重试！');
		$arr=array_filter( explode('_',$ids) );
		$m=[];
		$deleteUser=M('emailuserlist');
		foreach($arr as $k=>$v){
			$m[$k]['touid']=$uid;
			$m[$k]['addtime']=time();
			$m[$k]['status']=0;
			$m[$k]['rid']=$v;
			$m[$k]['uid']=session('loginuser.uid');
			//删除当前的
			$deleteUser->where(['lid'=>$v,'eid'=>session('loginuser.id')])->delete();
		}
		$result=M('rforward')->addAll($m);
		if($result === false) $this->error('当前转发失败，请检查网络链接设置！');
		$status=M('type')->where(['step'=>3])->getField('id');
		$this->bll->where(['id'=>['in',implode(',',$arr)]])->save(['status'=>$status]);
		$this->success('当前转发成功....');
	}
	
}
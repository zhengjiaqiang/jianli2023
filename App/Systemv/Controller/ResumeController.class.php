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
use Common\Libs\Util\ZipFolder as zip;

class ResumeController extends BaseController
{
    private $dbname = 'resumelist';
    private $bll = null;
    private $id = null;
    private $powerArray = array();
    private $title = null;
    protected $onePageRow = 100;
    // $this->onePageRow=100;
    protected $volunteer = ['请选择志愿', '第一志愿', '第二志愿', '第三志愿'];
    protected $allstatus = [1 => '通知面试', 2 => '已面试', 3 => '缺席', '4' => '录用', 5 => '不录用', 6 => '入职', 7 => '缺席'];
    protected $button = [
        1 => [
            // 0=>['name'=>'预览','url'=>'/systemv/resume/prview/id/{id}/pid/{pid}','isalert'=>1]
        ],
        2 => [
            // 0=>['name'=>'预览','url'=>'/systemv/resume/prview/id/{id}/pid/{pid}','isalert'=>1]
        ],
        3 => [
            0 => ['name' => '预览', 'url' => '/systemv/resume/prview/id/{id}/pid/{pid}', 'isalert' => 1],
            1 => ['name' => '通知面试', 'url' => '/systemv/resume/notice/id/{id}/pid/{pid}', 'event' => 'change', 'status' => 1, 'step' => 4]
        ],
        4 => [
            1 => ['name' => '已面试', 'url' => null, 'event' => 'change', 'status' => 2, 'step' => 5],
            2 => ['name' => '缺席', 'url' => null, 'event' => 'change', 'status' => 3, 'step' => 0],
        ],
        5 => [
            1 => ['name' => '录用', 'url' => null, 'event' => 'change', 'status' => 4, 'step' => 6],
            2 => ['name' => '不录用', 'url' => null, 'event' => 'change', 'status' => 5, 'step' => 8],
            3 => ['name' => '面试情况', 'url' => '/systemv/resume/situation/id/{id}/pid/{pid}', 'event' => 'change', 'status' => 7, 'step' => 6],
        ],
        6 => [
            1 => ['name' => '入职', 'url' => null, 'event' => 'change', 'status' => 6, 'step' => 7],
            2 => ['name' => '缺席', 'url' => null, 'event' => 'change', 'status' => 7, 'step' => 0],
        ],
        7 => [
            // 0=>['name'=>'预览','url'=>'/systemv/resume/prview/id/{id}/pid/{pid}','isalert'=>1],
            // 1=>['name'=>'已报到','url'=>null,'event'=>'change','status'=>4],
            // 2=>['name'=>'未报到','url'=>null,'event'=>'change','status'=>5]
        ],
        8 => [
            // 0=>['name'=>'预览','url'=>'/systemv/resume/prview/id/{id}/pid/{pid}','isalert'=>1]
        ],
        11 => [

        ]
    ];

    public function __construct()
    {
        parent::__construct();
        $this->bll = M($this->dbname);
        $this->id = I('get.id/d', 0);
        $this->title = $this->getTitle($this->id);
        $this->powerArray = $this->checkType($this->id);
        $this->checkType($this->id, true);
        $this->assign('title', $this->title);
        $this->assign('id', $this->id);

        $this->assign('type', !in_array(I('get.type/d'), $this->type) ? 1 : I('get.type/d'));
        $this->assign('volunteer', $this->volunteer);
        $this->assign('alltype', M('type')->order('sort asc,id asc')->getField('id,show'));
    }

    public function index()
    {
        if (!$this->check('select', $this->id)) $this->errorTypeMessage($this->roletype['select']['name']);

        $type = I('get.type/d', 1);
        if (IS_AJAX) {
            $page = I('get.page/d', 0);
            //,emailtrueuser.name as ename,emailtrueuser.department as edepartment,emailtrueuser.email as useremail
            $re = $this->pageData(
                $this->dbname, $page,
                $this->getWhere(), $this->getSort(), 'list.*,list.addtime as intimes,message.*,status.status as biaoji,pos.depid,pos.name,dep.name as dname,viewtime.time as date', 'as list left join hh_uremusemessagecache as message on message.lid = list.id left join hh_resumestatus as status on list.id=status.rid left join hh_position as pos on list.rid=pos.id left join hh_department as dep on dep.id = pos.depid left join hh_resumereviewtime as viewtime on list.id = viewtime.rid');
            //echo M($this->dbname)->_sql();
            $forward = M('rforward');
            $emailuserlist = M('emailuserlist');
            $emailuser = M('emailuser');
            $result = M('usernumbertoresult');
            foreach ($re['aaData'] as $k => $v) {
                //实习/工作经历（单位）
//                $uremusemessageinfo = M('uremusemessage')->where(['uid' => $v['uid']])->field('practiceunits,workunits')->find();
//                if ($uremusemessageinfo) {
//                    $re['aaData'][$k]['practiceWorkUnits'] = $uremusemessageinfo['practiceunits'] . '/' . $uremusemessageinfo['workunits'];
//                } else {
//                    $re['aaData'][$k]['practiceWorkUnits'] = '';
//                }
                $re['aaData'][$k]['practiceWorkUnits'] = $v['practiceunits'] . '/' . $v['workunits'];
                //在职学历
                if ($v['2htc2bme4keq8'] && $v['2f2wq303jut3h']) {
                    //学历
                    $re['aaData'][$k]['education'] = $v['2htc2bme4keq8'];
                    //毕业学校
                    $re['aaData'][$k]['graduateSchool'] = $v['1wqxq7r9dpdyg'];
                    //专业
                    $re['aaData'][$k]['major'] = $v['dpa1eqch5bsv'];
                } else { //全日制
                    //学历
                    $re['aaData'][$k]['education'] = $v['2htc2bme4k8'];
                    //毕业学校
                    $re['aaData'][$k]['graduateSchool'] = $v['1xq7r9dpdyg'];
                    //专业
                    $re['aaData'][$k]['major'] = $v['dpa1ch5bsv'];
                }

                $re['aaData'][$k]['name'] = $v['dname'];
                $re['aaData'][$k]['depname'] = $v['name'];
                $re['aaData'][$k]['show'] = M('type')->where(['id' => $v['status']])->getField('show');
                $re['aaData'][$k]['volunteer'] = C('SYS_SET.isstartbigtype') ? $this->volunteer[$v['vid']] : '无';
                $re['aaData'][$k]['showtitle'] = $this->allstatus[$v['cstatus']];
                $re['aaData'][$k]['button'] = $this->button[$v['status']];
                //评审
                $re['aaData'][$k]['passcount'] = $result->where(['status' => 1, 'viewid' => $v['id']])->count();
                $re['aaData'][$k]['nopasscount'] = $result->where(['status' => 2, 'viewid' => $v['id']])->count();
                //读取当前
                $emailinfo = $emailuserlist->field('u.name,u.email')->where(['user.lid' => $v['id']])->join('as user left join hh_emaillist as email on user.eid = email.id left join hh_emailuser as u on email.touser=u.id')->find();
                $re['aaData'][$k]['ename'] = $emailinfo['name'];
                $re['aaData'][$k]['useremail'] = $emailinfo['email'];
                $forinfo = $forward->where(['rid' => $v['id'], 'status' => 0])->find();
                $re['aaData'][$k]['forward'] = $forinfo;
                if (!empty($forinfo)) {
                    $re['aaData'][$k]['fromuser'] = $emailuser->where(['id' => $forinfo['uid'], 'rid' => $v['id']])->field('department,email')->find();
                    $re['aaData'][$k]['touser'] = $emailuser->where(['id' => $forinfo['touid'], 'rid' => $v['id']])->field('department,email')->find();
                }
            }
            exit(json_encode($re, true));
        }
        //读取当前可是
        $dlist = M('department')->where(['status' => 1, 'isdel' => 0])->getField('id,name', true);
        $plist = M('position')->where(['status' => 1, 'isdel' => 0])->getField('id,name', true);
        $this->assign('plist', $plist);
        $this->assign('dlist', $dlist);
        $this->assign('typelist', M('type')->where(['step' => ['neq', 0], 'id' => ['neq', 5]])->getField('id,name', true));
        $this->assign('type', $type);
        $this->display();
    }

    public function operate()
    {
        if (!IS_POST) {
            exit();
        }
        $event = I('post.event');
        $uid = $this->userInfo['id'];
        $ids = I('post.chkItem');
        if (is_array($ids)) $ids = join(',', $ids);
        if ($event == 'recommend') {
            $list = $this->bll->where(['id' => ['in', $ids]])->field('id,status')->select();
            $typeBll = M('type');
            foreach ($list as $k => $v) {
                $intype = $typeBll->where(['id' => $v['status']])->field('id,isend,isreturn,isadd,step')->find();
                $this->bll->where(['id' => $v['id']])->save(['status' => $intype['step']]);
                M('resumestatus')->where(['rid' => $v['id']])->delete();
                $this->bll->where(['id' => $v['id']])->save(['isread' => 0]);
                $this->addLog('update', '批量推荐标签操作，IDS：' . $v['id']);
            }
            $this->success('当前推荐执行成功....');
        } else if ($event == 'warehousing') {
            $list = $this->bll->where(['id' => ['in', $ids]])->save(['status' => 8]);
            //清除标记
            M('resumestatus')->where(['rid' => ['in', $ids]])->delete();
            $this->success('当前入库执行成功....');
        } else if ($event == 'word') {
            $time = date('YmdHis');
            $PHPWord = new \PhpOffice\PhpWord\PhpWord();
            $list = $this->bll->field('list.id,list.vid,p.name,dep.name as department,list.addtime')
                ->where(['list.id' => ['in', $ids]])
                ->join('as list left join hh_position as p on list.rid = p.id left join hh_department as dep on p.depid = dep.id')
                ->select();
           // echo M()->_sql();die;
            //dd($list);
            foreach ($list as $v) {
                $resumelist = M('rusercache')
                    //->where(['cid' => 47])
                    ->where(['rid' => $v['id']])->order('sort desc')->field('config')->select();
                //dd($resumelist);
                $m = [];
                $m['gangwei'] = $v['name'];
                $m['department'] = $v['department'];
                $m['addtime'] = date('Y-m-d', $v['addtime']);
                $m['vid'] = $v['vid'];

                foreach ($resumelist as $k => $v) {
                    parse_str(base64_decode($v['config']), $u);
                    $m = array_merge($m, (array)$u);
                }
                $PHPWord = $this->createWord($PHPWord, $m);
            }
            $objWrite = \PhpOffice\PhpWord\IOFactory::createWriter($PHPWord, 'Word2007');
            $path = './upload/docx/' . $time . '/';
            if (!file_exists($path)) mkdir($path, 0777, true);
            $filename = '基本信息表.docx';
            $objWrite->save($path . $filename);
            unset($PHPWord);
            $zip = new zip();
            file_exists('./upload/zip/') ?: mkdir('./upload/zip/', 0777, true);
            $zipFile = './upload/zip/' . $time . '.zip';
            $zip->zip($zipFile, trim($path));
            $this->ajaxReturn(['status' => 3, 'info' => '打包成功....', 'url' => trim($zipFile, '.')]);
        } else if ($event == 'implement') {
            //执行选中的标签
            $list = M('resumestatus')->where(['rid' => ['in', $ids]])->select();
            $status = I('post.status');
            $typeBll = M('type');
            foreach ($list as $k => $v) {
                $resumestatus = $this->bll->where(['id' => $v['rid']])->getField('status');
                $intype = $typeBll->where(['id' => $resumestatus])->field('id,isend,isreturn,isadd,step')->find();
                $typeinfo = $typeBll->where(['id' => $status[$v['rid']]])->field('id,isend,isreturn,isadd,step')->find();
                if (!$typeinfo['isreturn'] && !$typeinfo['isend']) {
                    $instatus = $typeinfo['isadd'] == 0 ? 8 : $intype['step'];
                    $this->bll->where(['id' => $v['rid']])->save(['status' => $instatus]);
                    M('resumestatus')->where(['rid' => $v['rid']])->delete();
                    $this->bll->where(['id' => $v['rid']])->save(['isread' => 0]);
                    $this->addLog('update', '批量执行标签操作，IDS：' . $v['rid']);
                }
            }
            $this->success('当前标签执行成功....');
        } else if ($event == 'information') {
            //导出基本信息
            $list = $this->bll->where(['id' => ['in', $ids]])->select();
            //echo M()->_sql();die;
            //SELECT * FROM `hh_resumelist` WHERE `id` IN ('6255')
            $cache = M('uremusemessagecache');
            $config = D('system')->where(['name' => 'download'])->getField('value');
            $array = array_filter(explode('|', $config));
            //dd($array);
            $field = [];
            $header = ['申请科室', '申请职位', '志愿', '申请时间'];
            foreach ($array as $k => $v) {
                list($key, $value) = explode(':', $v);
                $field[$key] = $value;
            }

            //dd($field);
            //dd($list);
            $fields = array_keys($field);
            array_push($fields, 'practiceunits', 'workunits', '2htc2bme4k8', 'dpa1ch5bsv', '1xq7r9dpdyg','tutors','jidizhuanyes','quanrizhitype','zaizhitype');
            $nlist = [];
            foreach ($list as $k => $v) {
                $message = $cache->where(['lid' => $v['id']])->field('addtime,' . implode(',', $fields))->find();
                //$message = $cache->where(['lid' => $v['id']])->field('addtime,' . implode(',', array_keys($field)))->find();
                //职位信息
                $depinfo = M('position')->where(['id' => $v['rid']])->find();
                //部门
                $dname = M('department')->where(['id' => $depinfo['depid']])->getField('name');
                $depname = $depinfo['name'];
                $nlist[$k]['dep'] = $dname;
                $nlist[$k]['zhiwei'] = $depname;
                $nlist[$k]['zhiyuan'] = $this->volunteer[$v['vid']];
                foreach ($message as $key => $value) {
                    if ($k == 0 && in_array($key, array_keys($field))) array_push($header, $field[$key]);
                    $nlist[$k][$key] = ' ' . $value;
                    $nlist[$k]['addtime'] = date('Y-m-d', time());

                }
            }

            //zjq -------------------------------------开始
            //志愿、姓名、科室名称、职位名称、性别、生源地/出生地、年龄、学历（科研型or专业型）、毕业学校、专业、导师、实习/工作经历、基地/专业、婚姻状况、手机号
            $header1 = [
                'zhiyuan' => '志愿', 'wwhqywgur0' => '姓名',
                'dep' => '科室名称', 'zhiwei' => '职位名称',
                'xlb0ix53hv' => '性别', 'province' => '生源地',
                'city' => '出生地', '2htc2bme4keq8' => '学历(科研型or专业型)',
                '1wqxq7r9dpdyg' => '毕业学校', 'dpa1eqch5bsv' => '专业',
                'tutors' => '导师', 'practiceunits' => '实习', 'workunits' => '工作经历',
                'jidizhuanyes' => '专业/基地', '1hryxiw6t37' => '婚姻状况',
                '2ywi8z97l7' => '手机号'
            ];

            $header2 = array_values($header1);

            $nlist2 = [];

            foreach ($header1 as $h1 => $v1) {
                foreach ($nlist as $nk => $nv) {
                    if ($h1 == '2htc2bme4keq8') {
                        if ($nv['2htc2bme4keq8'] && $nv['1wqxq7r9dpdyg']) {//在职学历
                            //学历
                            if ($nv['zaizhitype']) {
                                $nlist2[$nk]['2htc2bme4keq8'] = $nv['2htc2bme4keq8'] . "(" . $nv['zaizhitype'] . ")";
                            } else {
                                $nlist2[$nk]['2htc2bme4keq8'] = $nv['2htc2bme4keq8'];
                            }
                            //毕业学校
                            $nlist2[$nk]['1wqxq7r9dpdyg'] = $nv['1wqxq7r9dpdyg'];
                            //专业
                            $nlist2[$nk]['dpa1eqch5bsv'] = $nv['dpa1eqch5bsv'];
                        } else {
                            if ($nv['quanrizhitype']) {
                                $nlist2[$nk]['2htc2bme4keq8'] = $nv['2htc2bme4keq8'] . "(" . $nv['quanrizhitype'] . ")";
                            } else {
                                $nlist2[$nk]['2htc2bme4keq8'] = $nv['2htc2bme4k8'];
                            }
                            $nlist2[$nk]['1wqxq7r9dpdyg'] = $nv['1xq7r9dpdyg'];
                            $nlist2[$nk]['dpa1eqch5bsv'] = $nv['dpa1ch5bsv'];
                        }
                    } else {
                        $nlist2[$nk][$h1] = isset($nv[$h1]) ? $nv[$h1] : '';
                    }
                }
            }

//            dd($nlist2);
            $file = dataToFile($header2, $nlist2);
            //$file = dataToFile($header, $nlist); //old

            //zjq -----------------------------------------------结束
            $this->ajaxReturn(['status' => 3, 'url' => $file]);
        } else if ($event == 'change') {
            //小状态改变
            $status = I('post.changestatus/d');
            $step = I('post.step/d');
            in_array($status, array_keys($this->allstatus)) ?: $this->error('当前状态不存在请刷新重试');
            $data['cstatus'] = $status;
            $step == 0 ?: $data['status'] = $step;
            $this->bll->where(['id' => $ids])->save($data);
            $this->addLog('update', '批量' . $this->allstatus[$status] . '标签操作，IDS：' . $v['rid']);
            $this->success('当前' . $this->allstatus[$status] . '状态执行成功....');
        } else if ($event == 'sendemail') {
            // //检测当前是否运行发送邮件
            // $list=M('emaillist')->where(['rid'=>['in',$ids]])->find();
            // if(count($list)) $this->error('简历已经被发送了！');
            $this->success('当前选择简历允许被发送....', U('/systemv/resume/email', ['ids' => $ids]));
        } else if ($event == 'changestatus') {
            $value = I('post.value');
            if (!isset($this->button[$value])) $this->error('非法状态，请联系管理员！');
            $this->bll->where(['id' => ['in', $ids]])->save(['status' => $value]);
            $this->success('当前状态执行成功....');
        } else if ($event == 'wordlist') {
            $list = $this->bll->field('list.id,list.addtime,list.vid,p.name,dep.name as department')->where(['list.id' => ['in', $ids]])->join('as list left join hh_position as p on list.rid = p.id left join hh_department as dep on p.depid = dep.id')->select();
            $time = time();
            // $phpword=new \PhpOffice\PhpWord\PhpWord();
            foreach ($list as $v) {
                $resumelist = M('rusercache')->where(['rid' => $v['id']])->order('sort desc')->field('config,htmlconfig')->select();
                $m = [];
                $m['gangwei'] = $v['name'];
                $m['department'] = $v['department'];
                $m['addtime'] = date('Y-m-d', $v['addtime']);
                $m['vid'] = $v['vid'];
                foreach ($resumelist as $k => $v) {
                    parse_str(base64_decode($v['config']), $u);
                    $m = array_merge($m, (array)$u);
                }
                $this->createWord(new \PhpOffice\PhpWord\PhpWord(), $m, true, $time);

            }
            $path = './upload/docx/' . $time . '/';
            $zip = new zip();
            file_exists('./upload/zip/') ?: mkdir('./upload/zip/', 0777, true);
            $zipFile = './upload/zip/' . $time . '.zip';
            $zip->zip($zipFile, trim($path));
            $this->ajaxReturn(['status' => 3, 'info' => '打包成功....', 'url' => trim($zipFile, '.')]);
        } else if ($event == 'notice') {
            $this->success('当前选择简历允许被发送....', U('/systemv/resume/notice', ['pid' => $ids]));
        } else if ($event == 'question') {
            $list = M('questionnairee')->field('q.config,c.*,p.name')->where(['r.id' => ['in', $ids]])->join('as q left join hh_resumelist as r on q.uid=r.uid left join hh_uremusemessagecache as c on  r.id=c.lid left join hh_position as p on r.rid=p.id')->select();
            $header = ['姓名', '性别', '年龄', '所在省份', '手机号', 'Email', '毕业学校名称', '最高学历', '应聘岗位'];
            $total = [];
            $resumelist = M('resumelist');
            foreach ($list as $k => $v) {
                $config = json_decode($v['config'], true);
                if ($k == 0) $header = array_merge($header, array_keys($config));
                $footer['name'] = $v['wwhqywgur0'];
                $footer['性别'] = $v['xlb0ix53hv'];
                $footer['年龄'] = date('Y') - substr($v['26jmr8kmsz'], 6, 4) + (date('md') >= substr($v['26jmr8kmsz'], 10, 4) ? 1 : 0);
                $footer['所在省份'] = $v['k7855fgiqp'];
                $footer['手机号'] = $v['2ywi8z97l7'];
                $footer['Email'] = $v['qspywhdn8w'];
                $footer['毕业学校名称'] = $v['1wqxq7r9dpdyg'];
                $footer['最高学历'] = $v['2htc2bme4keq8'];
                $footer['应聘岗位'] = $v['name'];
                $total[$k] = array_merge($footer, array_values($config));

            }
            $this->ajaxReturn(['status' => 3, 'url' => dataToFile($header, $total)]);
        } else if ($event == 'noticedate') {
            $this->success('当前选择简历修改时间....', U('/systemv/resume/date', ['ids' => $ids]));
        } else if ($event == 'sort') {
            $listorders = I('post.sort');
            $ids = ',';
            foreach ($listorders as $id => $listorder) {
                $ids .= $id = (int)$id;
                $listorder = (int)$listorder;
                $this->bll->where(array('id' => $id))->save(['sort' => $listorder]);
            }
            $this->addLog('sort', $this->title . '排序IDS:' . $ids);
            $this->success('编号为“' . $ids . '”排序成功');
        } else if ($event == 'enclosure') {
            $user = M('resumelist')
                ->alias('r')
                ->field('user.id,user.username')
                ->where(['r.id' => ['in', $ids]])
                ->join('left join hh_user as user on r.uid=user.id')
                ->select();

            if (empty($user)) $this->error('当前没有所选择用户');
            $last = [];
            foreach ($user as $k => $v) {
                $last[$k] = $v['id'];
            }
            $file_class = M('file');
            $list = $file_class->where(['uid' => ['in', $last]])->field('filepath')->order('parentid asc,addtime asc')->select();
            if (empty($list)) $this->error('当前没有上传文件！');
            $zip = new zip();
            $zipFile = './down/zip/' . time() . '.zip';
            $data = [];
            foreach ($list as $k => $v) {
                $data[$k] = '.' . $v['filepath'];
            }
            $zip->createZipFiles($zipFile, $data);
            $this->ajaxReturn(['status' => 3, 'info' => '打包成功,马上下载....', 'url' => trim($zipFile, '.')]);
        }
    }

    /**
     * 获取查询条件
     */
    private function getWhere()
    {
        $map = [];
        // $id=I('get.id/d',0);
        $getArr = I('get.');
        !empty($getArr['dep']) ? $map['dep.id'] = $getArr['dep'] : '';
        !empty($getArr['vid']) ? $map['list.vid'] = $getArr['vid'] : '';
        !empty($getArr['pos']) ? $map['pos.id'] = $getArr['pos'] : '';
        !empty($getArr['biaoqian']) ? ($getArr['biaoqian'] == 1 ? $map['status.status'] = ['exp', 'is null'] : $map['status.status'] = ['exp', 'is not null']) : '';
        unset($getArr['id']);
        unset($getArr['dep']);
        unset($getArr['type']);
        unset($getArr['sEcho']);
        unset($getArr['page']);
        unset($getArr['pos']);
        unset($getArr['biaoqian']);
        unset($getArr['vid']);
        foreach ($getArr as $k => $v) $map['message.' . $k] = ['like', "%$v%"];
        $type = I('get.type/d', 1, 'int');
        in_array($type, [4, 5]) ? $map['list.status'] = ['in', '4,5'] : $map['list.status'] = $type;
        return $map;
    }

    /**
     * 获取排序条件
     */
    private function getSort()
    {
        // $sort = I('get.sort/d',-1);
        $str = 'sort desc,list.id desc,list.vid asc ';
        return $str;
    }

    public function prview()
    {
        $pid = I('get.pid/d', 0);
        $this->views($pid);
        $this->display();
    }

    private function views($pid)
    {
        $info = $this->bll->where(['id' => $pid])->find();
        if (empty($info)) $this->error('当前用户投递的简历不存在！');
        $this->bll->where(['id' => $pid])->save(['isread' => 1]);
        $resumelist = M('rusercache')->where(['rid' => $pid, 'uid' => $info['uid']])->order('sort desc')->field('config,htmlconfig')->select();

        $m = [];
        if ($pid == 93) {
            echo $this->bll->_sql();
            print_r($resumelist);
            die;
        }
        $ques = [];
        foreach ($resumelist as $k => $v) {
            parse_str(base64_decode($v['config']), $u);
            $m = array_merge($m, (array)$u);
            $ques[$k] = $v['htmlconfig'];
        }
        //
        $other = $this->bll->where(['uid' => $info['uid'], 'id' => ['neq', $pid], 'status' => ['neq', 8]])->field('rid,bigid,vid')->select();
        $positionClass = M('position');
        $bigtypeclass = M('bigtype');
        foreach ($other as $k => $v) {
            $data[$k]['vol'] = $this->volunteer[$v['vid']];
            $data[$k]['postion'] = $positionClass->where(['id' => $v['rid']])->getField('name');
            $data[$k]['bigtype'] = $bigtypeclass->where(['id' => $v['bigid']])->getField('name');
        }
        $userother = [];
        foreach ($data as $k => $v) {
            $userother[$v['bigtype']] = $v;
        }
        $this->assign('other', $userother);
        $this->assign('info', $info);
        $this->assign('user', $m);
        $this->assign('ques', $ques);
        $this->assign('pid', $pid);
    }

    public function isnext()
    {
        if (!IS_POST) exit();
        $status = I('post.status', '');
        $id = I('post.rid/d', '');
        if (!in_array($status, $this->type)) $this->error('当前状态不存在，请刷新重试！');
        $info = $this->bll->where(['id' => $id])->find();
        if (empty($info)) $this->error('当前简历不存在，请刷新重试！');
        unset($info);
        $info = M('resumestatus')->where(['rid' => $id])->find();
        $m['lasttime'] = time();
        $m['rid'] = $id;
        $m['status'] = $status;
        if (empty($info)) M('resumestatus')->add($m);
        else M('resumestatus')->where(['rid' => $id])->save($m);
        $this->success('当前简历标记成功！');
    }

    public function show()
    {
        // if(!IS_GET) exit();
        $ids = I('get.id');
        $p = I('get.p/d', 0);
        $array = array_filter(explode('_', $ids));
        $info = $this->bll->where(['id' => $array[$p]])->find();
        if (empty($info)) $this->error('当前用户投递的简历不存在！');
        $this->ajaxReturn(['status' => 1, 'id' => $array[$p], 'next' => $array[$p + 1], 'ids' => $ids, 'message' => '当前用户投递存在，实例化页面', 'p' => $p]);
    }

    public function next()
    {
        $id = I('get.id/d');
        $info = $this->bll->where(['id' => $id])->find();
        if (empty($info)) $this->error('当前用户投递的简历不存在！');
        $this->views($id);
        $p = I('get.p/d', 0);
        $ids = I('get.ids');
        $array = array_filter(explode('_', $ids));
        $this->assign('pid', $id);
        $this->assign('islast', ($id == $array[count($array) - 1]) ? true : false);
        $this->assign('ids', $ids);
        $this->assign('p', $p + 1);
        $this->display();
    }

    public function singleSubmit()
    {
        if (!IS_POST) exit();
        $status = I('post.status', '');
        $id = I('post.rid/d', '');
        if (!in_array($status, $this->type)) $this->error('当前状态不存在，请刷新重试！');
        $info = $this->bll->where(['id' => $id])->find();
        if (empty($info)) $this->error('当前简历不存在，请刷新重试！');
        unset($info);
        $info = M('resumestatus')->where(['rid' => $id])->find();
        $m['lasttime'] = time();
        $m['rid'] = $id;
        $m['status'] = $status;
        if (empty($info)) M('resumestatus')->add($m);
        else M('resumestatus')->where(['rid' => $id])->save(['status' => $status]);
        $list = M('resumestatus')->where(['rid' => ['eq', $id]])->select();
        $typeBll = M('type');
        foreach ($list as $k => $v) {
            $resumestatus = $this->bll->where(['id' => $v['rid']])->getField('status');
            $intype = $typeBll->where(['id' => $resumestatus])->field('id,isend,isreturn,isadd,step')->find();
            $typeinfo = $typeBll->where(['id' => $v['status']])->field('id,isend,isreturn,isadd,step')->find();
            if (!$typeinfo['isreturn'] && !$typeinfo['isend']) {
                $instatus = $typeinfo['isadd'] == 0 ? 8 : $intype['step'];
                $this->bll->where(['id' => $v['rid']])->save(['status' => $instatus]);
                M('resumestatus')->where(['rid' => $v['rid']])->delete();
                $this->bll->where(['id' => $v['rid']])->save(['isread' => 0]);
                $this->addLog('update', '批量执行标签操作，IDS：' . $v['rid']);
            }
        }
        $this->success('当前批量操作成功....');
    }

    public function submitresume()
    {
        if (!IS_POST) exit();
        $ids = I('post.ids');
        $status = I('post.status', '');
        $id = I('post.rid/d', '');
        if (!in_array($status, $this->type)) $this->error('当前状态不存在，请刷新重试！');
        $info = $this->bll->where(['id' => $id])->find();
        if (empty($info)) $this->error('当前简历不存在，请刷新重试！');
        unset($info);
        $info = M('resumestatus')->where(['rid' => $id])->find();
        $m['lasttime'] = time();
        $m['rid'] = $id;
        $m['status'] = $status;
        if (empty($info)) M('resumestatus')->add($m);
        else M('resumestatus')->where(['rid' => $id])->save(['status' => $status]);
        //所有的进行
        $ids = trim(str_replace('_', ',', $ids), ',');
        $list = M('resumestatus')->where(['rid' => ['in', $ids]])->select();
        $typeBll = M('type');
        foreach ($list as $k => $v) {
            $resumestatus = $this->bll->where(['id' => $v['rid']])->getField('status');
            $intype = $typeBll->where(['id' => $resumestatus])->field('id,isend,isreturn,isadd,step')->find();
            $typeinfo = $typeBll->where(['id' => $v['status']])->field('id,isend,isreturn,isadd,step')->find();
            if (!$typeinfo['isreturn'] && !$typeinfo['isend']) {
                $instatus = $typeinfo['isadd'] == 0 ? 8 : $intype['step'];
                $this->bll->where(['id' => $v['rid']])->save(['status' => $instatus]);
                M('resumestatus')->where(['rid' => $v['rid']])->delete();
                $this->bll->where(['id' => $v['rid']])->save(['isread' => 0]);
                $this->addLog('update', '批量执行标签操作，IDS：' . $v['rid']);
            }
        }
        $this->success('当前批量操作成功....');
    }

    public function email()
    {
        $list = M('emailuser')->where(['status' => 1, 'isdel' => 0])->select();
        $this->assign('id', I('get.ids'));
        $this->assign('list', $list);
        $this->display();
    }

    public function emaillist()
    {
        if (!IS_POST) exit();
        $post = I('post.');
        if (empty($post['passwd'])) $this->error('请填写访问密码！');
        $m = [];
        $ids = array_filter(explode(',', $post['id']));
        $m['password'] = md5($post['passwd']);
        $m['endtime'] = strtotime($post['btime'] . ' 23:59:59');
        //$m['rid']=','.$post['id'].',';
        //判断当前是否发送过
        $m['addtime'] = time();
        $m['adduid'] = $this->userId;
        $m['touser'] = $post['uid'];
        $m['status'] = 0;
        $id = M('emaillist')->add($m);
        if ($id === false) $this->error('当前简历发送失败，请重新发送');
        else {
            //发送邮件 邮件地址
            $forward = M('rforward');
            foreach ($ids as $k => $value) {
                $em[$k]['eid'] = $id;
                $em[$k]['lid'] = $value;
                //修改当前状态
                $this->bll->where(['id' => $value])->save(['status' => 11]);
            }
            //修改当前的转发
            $forward->where(['rid' => ['in', $ids]])->save(['status' => 1]);
            M('emailuserlist')->addAll($em);
            $href = 'http://' . $_SERVER['HTTP_HOST'] . "/prview/index/id/" . $id;
            $email = M('emailuser')->where(['id' => $post['uid']])->getField('email');
            $result = sendMail($email, '简历消息通知', '您的简历链接为: <a href="' . $href . '">点我去查看</a>,密码为：<span style="color:red;">' . $post['passwd'] . '</span>,请在有效期内查看：' . $post['btime']);
            // $result=sendMail($email,'密码找回','您的密码是：1111');
            if ($result !== true) {
                M('emaillist')->where(['id' => $id])->delete();
                $this->error('当前简历发送邮件失败，失败错误代码:' . $result);
            }
            $this->success('当前简历发送成功！');
        }
    }

    public function notice()
    {
        $pid = I('get.pid', 0);
        $info = $this->bll->where(['id' => ['in', $pid], 'status' => 3])->select();
        if (empty($info)) $this->error('当前简历不支持发送邮件信息！');
        //读取当前简历末
        $list = M('email')->where(['status' => 1, 'isdel' => 0])->field('id,title')->select();
        $this->assign('list', $list);
        $this->assign('pid', $pid);
        $this->display();
    }

    public function noticelist()
    {
        if (!IS_POST) exit();
        $post = I('post.');
        if (empty($post['eid'])) $this->error('请选择邮件模板！');
        $content = M('email')->where(['id' => $post['eid'], 'status' => 1, 'isdel' => 0])->getField('content');
        if (empty($content)) $this->error('当前邮件模板出错，请检查模板！');
        $list = M('resumelist')->field('list.remark,u.username,u.email,p.name,cache.qspywhdn8w,list.status')->where(['list.id' => ['in', $post['pid']]])->join('as list left join hh_user as u on list.uid = u.id left join hh_position as p on list.rid =p.id left join hh_uremusemessagecache as cache on list.id = cache.lid')->select();
        // print_r($info);die;
        // $result=sendMail((empty($info['email']) ? $info['qspywhdn8w'] : $info['email']),'密码找回','您的密码是：1111');
        $typeBll = M('type');
        $fail = M('emailfail');

        $reviewtime = M('resumereviewtime');
        foreach ($list as $k => $v) {
            $sendcontent = str_replace(['${name}', '${position}', '${date}'], [$v['username'], $v['name'], $post['btime']], $content);
            $result = sendMail((empty($v['email']) ? $v['qspywhdn8w'] : $v['email']), '面试通知', $sendcontent);
            $m = [];
            if ($result !== true) {
                // $this->error('当前简历发送邮件失败，失败错误代码:'.$result);
                $m['content'] = $sendcontent;
                $m['email'] = (empty($v['email']) ? $v['qspywhdn8w'] : $v['email']);
                $m['errormsg'] = $result;
                $m['sendtime'] = time();
                $fail->add($m);
            }
            $intype = $typeBll->where(['id' => $v['status']])->field('id,isend,isreturn,isadd,step')->find();
            $data[$k]['rid'] = $v['id'];
            if ($reviewtime->where($data[$k])->count()) {
                $update[$k]['time'] = strtotime($post['btime']);
                $update[$k]['adduid'] = $this->userId;
                $reviewtime->where($data[$k])->save($update[$k]);
            } else {
                $data[$k]['time'] = strtotime($post['btime']);
                $data[$k]['adduid'] = $this->userId;
                $reviewtime->add($data[$k]);
            }
            $this->bll->where(['id' => $v['id']])->save(['status' => $intype['step']]);
        }
        $this->success('当前简历发送成功！');
    }

    public function remark()
    {
        if (!IS_POST) exit();
        $post = I('post.');
        $m['remark'] = $post['info'];
        $this->bll->where(['id' => $post['id']])->save($m);
        $this->success('当前简历备注成功！');
    }

    private function keySearchInArray($key, $array = [])
    {
        $list = [];
        foreach ($array as $k => $v) {
            if (strstr($k, $key) !== false) {
                $ex = explode('_', $k);
                $list[$ex[0] . $ex[1]][] = $v;
            }
        }
        return array_merge($list);
    }


    private function createWord($PHPWord, $m, $isMoreNumber = false, $time = null)
    {
        //dd($m);
        //最高学历
        $highestdegree = '无';
        //毕业院校
        $graduateSchool = '无';
        //毕业专业
        $graduationmajor = '无';
        //最高学位
        $highestxuewei = '';
        if($m['2htc2bme4keq8'] && $m['2htc2bme4keq8']!='无'){//在职
            $highestdegree = $m['2htc2bme4keq8'];
            $graduateSchool = $m['1wqxq7r9dpdyg'];
            $graduationmajor = $m['dpa1eqch5bsv'];
            $highestxuewei = $m['2f2wq303jut3h'];
        }else{
            $highestdegree = $m['2htc2bme4k8'];
            $graduateSchool = $m['1xq7r9dpdyg'];
            $graduationmajor = $m['dpa1ch5bsv'];
            $highestxuewei = $m['2f2303jut3h'];
        }

        $section = $PHPWord->createSection();
        $center = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER];
        $PHPWord->addFontStyle('rStyle', array('bold' => true, 'color' => '000000', 'size' => 16));
        $PHPWord->addParagraphStyle('pStyle', array('align' => 'center'));
        $section->addTextBreak(1);
        $styleTable = array('borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80);
        $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
        $section->addText('应聘职位:' . $m['gangwei'] . '  投递日期:' . $m['addtime']);
        $section->addText('简历信息');
        $section->addTextBreak(1);
        $table = $section->addTable('myOwnTableStyle');
        $fontStyle = array('bold' => true, 'align' => 'center');
        $table->addRow();
        $table->addCell(4000)->addText("姓名", $fontStyle);
        $table->addCell(6000)->addText($m['wwhqywgur0'], $fontStyle);
        //
        $table->addCell(2000, ['gridSpan' => 2, 'vMerge' => 'restart'])->addImage(empty($m['selfpic']) ? './static/index/images/file_bg.jpg' : (!$this->isImg($m['selfpic']) ? './static/index/images/file_bg.jpg' : '.' . str_replace(['https://hr.huashan.org.cn'], [''], trim($m['selfpic']))), ['width' => 130, 'height' => 150, 'align' => 'center']);
        $table->addRow();
        $table->addCell(2000)->addText("性别", $fontStyle);
        $table->addCell(3000)->addText($m['xlb0ix53hv'], $fontStyle);
        $table->addCell(2000, ['gridSpan' => 2, 'vMerge' => 'restart', 'vMerge' => 'continue']);
        $table->addRow();
        $table->addCell(2000)->addText("身份证号", $fontStyle);
        $table->addCell(3000)->addText($m['26jmr8kmsz'], $fontStyle);
        $table->addCell(2000, ['gridSpan' => 2, 'vMerge' => 'restart', 'vMerge' => 'continue']);
        $table->addRow();
        $table->addCell(2000)->addText("出生日期", $fontStyle);
        $table->addCell(3000)->addText($m['2da9x8ajf56'], $fontStyle);
        $table->addCell(2000, ['gridSpan' => 2, 'vMerge' => 'restart', 'vMerge' => 'continue']);
        $table->addRow();
        $table->addCell(2000)->addText("民族", $fontStyle);
        $table->addCell(3000)->addText($m['vpyfqb4s85'], $fontStyle);
        $table->addCell(2000, ['gridSpan' => 2, 'vMerge' => 'restart', 'vMerge' => 'continue']);
        $table->addRow();
        $table->addCell(2000)->addText("政治面貌", $fontStyle);
        $table->addCell(3000)->addText($m['1mb0ylvappl'], $fontStyle);
        $table->addCell(2000)->addText("婚姻状况", $fontStyle);
        $table->addCell(3000)->addText($m['1hryxiw6t37'], $fontStyle);
        $table->addRow();
        $table->addCell(2000)->addText("户口所在地", $fontStyle);
        $table->addCell(3000, ['vMerge' => 'restart'])->addText($m['province'] . '-' . $m['city'], $fontStyle);
        $table->addCell(2000)->addText("住址", $fontStyle);
        $table->addCell(3000)->addText($m['k7855fgiqp'], $fontStyle);
        $table->addRow();
        $table->addCell(2000)->addText("应届生", $fontStyle);
        $table->addCell(3000)->addText($m['2izv9jv98uk'], $fontStyle);
        $table->addCell(4000)->addText("现工作单位", $fontStyle);
        $table->addCell(6000)->addText($m['2izv9jv928ukw'], $fontStyle);
        $table->addRow();
        $table->addCell(2000)->addText("最高学历", $fontStyle);
        //$table->addCell(3000)->addText($m['2htc2bme4keq8'], $fontStyle);
        $table->addCell(3000)->addText($highestdegree, $fontStyle);

        //zjq--------------
        $table->addCell(2000)->addText("最高学位", $fontStyle);
        $table->addCell(3000)->addText($highestxuewei, $fontStyle);

        $table->addRow();

        $table->addCell(2000)->addText("邮编", $fontStyle);
        $table->addCell(3000,['gridSpan'=>3,'vMerge'=>'restart'])->addText($m['2fcppwd33z5'], $fontStyle);
        $table->addRow();

        $table->addCell(2000)->addText("毕业院校", $fontStyle);
        //$table->addCell(3000)->addText($m['1wqxq7r9dpdyg'], $fontStyle);
        $table->addCell(3000)->addText($graduateSchool, $fontStyle);
        $table->addCell(2000)->addText("毕业专业", $fontStyle);
        $table->addCell(3000)->addText($graduationmajor, $fontStyle);
        //$table->addCell(3000)->addText($m['dpa1eqch5bsv'], $fontStyle);
        $table->addRow();
        $table->addCell(2000)->addText("手机", $fontStyle);
        $table->addCell(3000)->addText($m['2ywi8z97l7'], $fontStyle);
        $table->addCell(2000)->addText("Email", $fontStyle);
        $table->addCell(3000)->addText($m['qspywhdn8w'], $fontStyle);




        // $table->addRow();
        // $table->addCell(2000)->addText("有无其它慢性病 传染病",$fontStyle);
        // $table->addCell(3000,['gridSpan'=>3,'vMerge'=>'restart'])->addText($m['es9mn0ww5t'].''.($m['es9mn0ww5t']=='是' ? '/'.$m['wwhqywgur1'] : ''),$fontStyle);

        $table->addRow();
        $table->addCell(2000)->addText("健康情况", $fontStyle);
        $table->addCell(3000)->addText($m['1flzix4x2ln'], $fontStyle);
        $table->addCell(2000)->addText("岗位调剂", $fontStyle);
        $table->addCell(3000)->addText($m['19payq4d2d8'], $fontStyle);
        unset($table);
        $JT = $this->keySearchInArray('JT', $m);
        if ($JT && !empty($JT['JT0'][0])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('家庭主要成员情况', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(2000)->addText("称谓", $fontStyle);
            $table->addCell(3000)->addText('姓名', $fontStyle);
            $table->addCell(2000)->addText("工作单位", $fontStyle);
            $table->addCell(3000)->addText("职务", $fontStyle);
            for ($i = 0; $i < count(current($JT)); $i++) {
                $table->addRow();
                $table->addCell(2000)->addText($JT['JT0'][$i], $fontStyle);
                $table->addCell(3000)->addText($JT['JT1'][$i], $fontStyle);
                $table->addCell(2000)->addText($JT['JT2'][$i], $fontStyle);
                $table->addCell(3000)->addText($JT['JT3'][$i], $fontStyle);
            }
        }

        $QS = $this->keySearchInArray('QS', $m);
        if ($QS && $m['es9mn0ww5t'] == '是') {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('亲属受雇情况', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(2000)->addText("关系", $fontStyle);
            $table->addCell(3000)->addText('姓名', $fontStyle);
            $table->addCell(2000)->addText("所在科室", $fontStyle);
            for ($i = 0; $i < count(current($QS)); $i++) {
                $table->addRow();
                $table->addCell(2000)->addText($QS['QS0'][$i], $fontStyle);
                $table->addCell(3000)->addText($QS['QS1'][$i], $fontStyle);
                $table->addCell(2000)->addText($QS['QS2'][$i], $fontStyle);
            }
        }

        $jy = $this->keySearchInArray('JY', $m);
        if ($jy && !empty($jy['JY0'][0])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('教育背景（从高中起填）', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(2000)->addText("时间", $fontStyle);
            $table->addCell(3000)->addText('学历', $fontStyle);
            $table->addCell(2000)->addText("教学性质", $fontStyle);
            $table->addCell(3000)->addText("导师", $fontStyle);
            $table->addCell(3000)->addText("学校", $fontStyle);
            $table->addCell(2000)->addText("专业", $fontStyle);
            for ($i = 0; $i < count(current($jy)); $i++) {
                $table->addRow();
                $table->addCell(2000)->addText($jy['JY0'][$i], $fontStyle);
                $table->addCell(3000)->addText($jy['JY1'][$i], $fontStyle);
                $table->addCell(2000)->addText($jy['JY2'][$i], $fontStyle);
                $table->addCell(3000)->addText($jy['JY3'][$i], $fontStyle);
                $table->addCell(3000)->addText($jy['JY4'][$i], $fontStyle);
                $table->addCell(3000)->addText($jy['JY5'][$i], $fontStyle);
            }
        }

        // $section->addTextBreak(3);

        // $table->addCell(2000)->addText("住院医生规培经历",$fontStyle);
        // $table->addCell(8000)->addText($m['1etmnffrzs8'],$fontStyle);
        // $table->addRow();

        $GZ = $this->keySearchInArray('GZ', $m);
        if ($GZ && !empty($GZ['GZ0'][0])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('工作经历', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(2000)->addText("时间", $fontStyle);
            $table->addCell(3000)->addText('单位', $fontStyle);
            $table->addCell(2000)->addText("所在部门", $fontStyle);
            $table->addCell(5000)->addText("岗位/职称", $fontStyle);
            $table->addCell(2000)->addText("备注", $fontStyle);
            for ($i = 0; $i < count(current($GZ)); $i++) {
                $table->addRow();
                $table->addCell(2000)->addText($GZ['GZ0'][$i], $fontStyle);
                $table->addCell(3000)->addText($GZ['GZ1'][$i], $fontStyle);
                $table->addCell(2000)->addText($GZ['GZ2'][$i], $fontStyle);
                $table->addCell(2000)->addText($GZ['GZ3'][$i], $fontStyle);
                $table->addCell(2000)->addText($GZ['GZ4'][$i], $fontStyle);
            }
        }

        $sx = $this->keySearchInArray('SX', $m);
        if ($sx && !empty($sx['SX0'][0])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('实习经历', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(2000)->addText("时间", $fontStyle);
            $table->addCell(4000)->addText('单位', $fontStyle);
            $table->addCell(2000)->addText("实习岗位", $fontStyle);
            $table->addCell(4000)->addText("工作内容", $fontStyle);
            $table->addCell(2000)->addText("备注", $fontStyle);
            for ($i = 0; $i < count(current($sx)); $i++) {
                $table->addRow();
                $table->addCell(2000)->addText($sx['SX0'][$i], $fontStyle);
                $table->addCell(4000)->addText($sx['SX1'][$i], $fontStyle);
                $table->addCell(2000)->addText($sx['SX2'][$i], $fontStyle);
                $table->addCell(4000)->addText($sx['SX3'][$i], $fontStyle);
                $table->addCell(2000)->addText($sx['SX4'][$i], $fontStyle);
            }
        }
        if ($m['1etmnffrzs8'] == '已完成') {
            $ZY = $this->keySearchInArray('ZY', $m);
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('住院医师规培', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(2000)->addText("起止年月", $fontStyle);
            $table->addCell(3000)->addText('培训医院', $fontStyle);
            $table->addCell(2000)->addText("培训专业/基地", $fontStyle);
            $table->addCell(5000)->addText("规培结业/预计结业时间", $fontStyle);
            $table->addCell(2000)->addText("是否为四证合一专硕", $fontStyle);
            $table->addCell(2000)->addText("规培证书号码", $fontStyle);
            for ($i = 0; $i < count(current($ZY)); $i++) {
                $table->addRow();
                $table->addCell(2000)->addText($ZY['ZY0'][$i], $fontStyle);
                $table->addCell(3000)->addText($ZY['ZY1'][$i], $fontStyle);
                $table->addCell(2000)->addText($ZY['ZY2'][$i], $fontStyle);
                $table->addCell(2000)->addText($ZY['ZY3'][$i], $fontStyle);
                $table->addCell(2000)->addText($ZY['ZY4'][$i], $fontStyle);
                $table->addCell(2000)->addText($ZY['ZY5'][$i], $fontStyle);
            }
        } elseif ($m['1etmnffrzs8'] == '规培中') {
            $ZY = $this->keySearchInArray('ZY', $m);
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('住院医师规培', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(2000)->addText("起止年月", $fontStyle);
            $table->addCell(3000)->addText('培训医院', $fontStyle);
            $table->addCell(2000)->addText("培训专业/基地", $fontStyle);
            $table->addCell(5000)->addText("规培结业/预计结业时间", $fontStyle);
            $table->addCell(2000)->addText("是否为四证合一专硕", $fontStyle);
            for ($i = 0; $i < count(current($ZY)); $i++) {
                $table->addRow();
                $table->addCell(2000)->addText($ZY['ZY0'][$i], $fontStyle);
                $table->addCell(3000)->addText($ZY['ZY1'][$i], $fontStyle);
                $table->addCell(2000)->addText($ZY['ZY2'][$i], $fontStyle);
                $table->addCell(2000)->addText($ZY['ZY3'][$i], $fontStyle);
                $table->addCell(2000)->addText($ZY['ZY4'][$i], $fontStyle);
            }
        } else {
            $ZY = $this->keySearchInArray('ZY', $m);
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('是否取得主治医师资格', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(2000)->addText("起止年月", $fontStyle);
            $table->addCell(3000)->addText($m['ZHUZ_0'], $fontStyle);
            $table->addCell(2000)->addText("取得时间", $fontStyle);
            $table->addCell(5000)->addText($m['ZHUZ_1'], $fontStyle);
        }
        $section->addTextBreak(3);

        $YUZ = $this->keySearchInArray('YUZ', $m);
        $section->addTextBreak(3);
        $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
        $section->addText('基本技能', 'rStyle');
        $section->addTextBreak(1);
        $table = $section->addTable('myOwnTableStyle');
        $fontStyle = array('bold' => true, 'align' => 'center');
        $table->addRow();
        $table->addCell(3000)->addText("英语", $fontStyle);
        $table->addCell(8000)->addText('分数/备注', $fontStyle);
        for ($i = 0; $i < count(current($YUZ)); $i++) {
            $table->addRow();
            $table->addCell(3000)->addText($YUZ['YUZ0'][$i], $fontStyle);
            $table->addCell(8000)->addText($YUZ['YUZ1'][$i], $fontStyle);
        }


        $section->addTextBreak(3);
        $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
        // $section->addText('最高学分', 'rStyle');
        $table = $section->addTable('myOwnTableStyle');
        $fontStyle = array('bold' => true, 'align' => 'center');
        $table->addRow();
        $table->addCell(2000)->addText("计算机等级", $fontStyle);
        $table->addCell(8000)->addText($m['2226ggmk69m'], $fontStyle);
        $QTPX = $this->keySearchInArray('QTPX', $m);
        if ($QTPX && !empty($QTPX['QTPX0'][0])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('其他培训与证书', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(4000)->addText("时间", $fontStyle);
            $table->addCell(8000)->addText('培训/证书', $fontStyle);
            $table->addCell(8000)->addText("培训/发证机构", $fontStyle);
            $table->addCell(8000)->addText("备注", $fontStyle);
            for ($i = 0; $i < count(current($QTPX)); $i++) {
                $table->addRow();
                $table->addCell(4000)->addText($QTPX['QTPX0'][$i], $fontStyle);
                $table->addCell(8000)->addText($QTPX['QTPX1'][$i], $fontStyle);
                $table->addCell(8000)->addText($QTPX['QTPX2'][$i], $fontStyle);
                $table->addCell(8000)->addText($QTPX['QTPX3'][$i], $fontStyle);
            }
        }

        $FB = $this->keySearchInArray('FB', $m);
        if ($FB && !empty($FB['FB0'][0])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('发表论文', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(5000)->addText("题目", $fontStyle);
            $table->addCell(6000)->addText('论文名称、期刊名称（卷、期、页）', $fontStyle);
            $table->addCell(2000)->addText("影响因子", $fontStyle);
            $table->addCell(2000)->addText("排名", $fontStyle);
            for ($i = 0; $i < count(current($FB)); $i++) {
                $table->addRow();
                $table->addCell(5000)->addText($FB['FB0'][$i], $fontStyle);
                $table->addCell(6000)->addText($FB['FB1'][$i], $fontStyle);
                $table->addCell(2000)->addText($FB['FB2'][$i], $fontStyle);
                $table->addCell(2000)->addText($FB['FB3'][$i], $fontStyle);
            }
        }
        $KT = $this->keySearchInArray('KT', $m);
        if ($KT && !empty($KT['KT0'][0])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('课题', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(3000)->addText('时间', $fontStyle);
            $table->addCell(3000)->addText("课题名称", $fontStyle);
            $table->addCell(2000)->addText("级别", $fontStyle);
            $table->addCell(2000)->addText("经费", $fontStyle);
            $table->addCell(2000)->addText("第几完成人", $fontStyle);
            $table->addCell(2000)->addText("批准部门", $fontStyle);
            for ($i = 0; $i < count(current($KT)); $i++) {
                $table->addRow();
                $table->addCell(2000)->addText($KT['KT0'][$i], $fontStyle);
                $table->addCell(3000)->addText($KT['KT1'][$i], $fontStyle);
                $table->addCell(2000)->addText($KT['KT2'][$i], $fontStyle);
                $table->addCell(2000)->addText($KT['KT3'][$i], $fontStyle);
                $table->addCell(2000)->addText($KT['KT4'][$i], $fontStyle);
                $table->addCell(2000)->addText($KT['KT5'][$i], $fontStyle);
            }
        }
        $HJ = $this->keySearchInArray('JFQK', $m);
        if ($HJ && !empty($HJ['JFQK0'][0])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('奖惩情况', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(8000)->addText("取得时间", $fontStyle);
            $table->addCell(4000)->addText('奖励/荣誉', $fontStyle);
            $table->addCell(8000)->addText('奖惩情况', $fontStyle);
            $table->addCell(8000)->addText('备注', $fontStyle);
            for ($i = 0; $i < count(current($HJ)); $i++) {
                $table->addRow();
                $table->addCell(8000)->addText($HJ['JFQK0'][$i], $fontStyle);
                $table->addCell(4000)->addText($HJ['JFQK1'][$i], $fontStyle);
                $table->addCell(8000)->addText($HJ['JFQK2'][$i], $fontStyle);
                $table->addCell(8000)->addText($HJ['JFQK3'][$i], $fontStyle);
            }
        }
        if (!empty($m['geren1z3u6esgam2'])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText(' 个人特长 ', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(20000, ['gridSpan' => 5, 'vMerge' => 'restart'])->addText($m['geren1z3u6esgam2'], $fontStyle);
        }
        if (!empty($m['1z3u6esgam2'])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('奖惩情况内容', 'rStyle');
            $section->addTextBreak(1);
            $table = $section->addTable('myOwnTableStyle');
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(20000, ['gridSpan' => 5, 'vMerge' => 'restart'])->addText($m['1z3u6esgam2'], $fontStyle);
        }
        if (!empty($m['ufvilpx5hd'])) {
            $section->addTextBreak(3);
            $PHPWord->addTableStyle('myOwnTableStyle', $styleTable);
            $section->addText('期望薪资 月薪（元）', 'rStyle');
            $table = $section->addTable('myOwnTableStyle');
            $section->addTextBreak(1);
            $fontStyle = array('bold' => true, 'align' => 'center');
            $table->addRow();
            $table->addCell(20000, ['gridSpan' => 5, 'vMerge' => 'restart'])->addText($m['ufvilpx5hd'], $fontStyle);
        }

        !$isMoreNumber ? $section->addPageBreak() : '';
        if ($isMoreNumber) {
            $objWrite = \PhpOffice\PhpWord\IOFactory::createWriter($PHPWord, 'Word2007');
            if (!file_exists('./upload/docx/' . $time . '/')) mkdir('./upload/docx/' . $time . '/', 0777, true);
            $userfile = str_replace(['/'], ['或'], $m['wwhqywgur0'] . '-' . $m['gangwei'] . '-' . $m['2htc2bme4k8']);
            $filename = '/upload/docx/' . $time . '/' . $userfile . '.docx';
            $objWrite->save('.' . $filename);
            unset($PHPWord);
        } else  return $PHPWord;
    }

    /**
     * 判断是否为图片格式(jpg/jpeg/gif/png)文件
     *
     * @param string $filePath
     * @return bool|string
     */
    private function isImg($filePath)
    {
        $filePath = '.' . str_replace(['https://hr.huashan.org.cn'], '', $filePath);
        if (!is_file($filePath)) return false;
        $file = fopen($filePath, "rb");
        $bin = fread($file, 2); // 只读2字节
        fclose($file);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'] . $strInfo['chars2']);
        switch ($typeCode) {
            case 255216:
            case 7173:
            case 13780:
                $fileType = true;
                break;
            default:
                $fileType = false;
                break;
        }
        return $fileType;
    }

    public function date()
    {
        $this->assign('id', I('get.ids'));
        $this->display();
    }

    public function datalist()
    {
        if (!IS_POST) exit();
        $ids = I('post.id');
        $data['time'] = strtotime(I('post.btime'));
        $ids = explode(',', $ids);
        $time = M('resumereviewtime');
        foreach ($ids as $v) {
            $time->where(['rid' => $v])->count() ? $time->where(['rid' => $v])->save($data) : $time->add(array_merge($data, ['rid' => $v, 'adduid' => $this->userId]));
        }
        $this->success('当前面试时间已修改');
    }

    public function situation()
    {
        $pid = I('get.pid', 0);
        $info = $this->bll->where(['id' => ['in', $pid], 'status' => 5])->select();
        if (empty($info)) $this->error('当前简历不支持查看面试情况');
        if (IS_AJAX) {
            $pid = I('get.pid/d');
            $page = I('get.p/d', 0);
            $status = I('get.status/d', 0);
            $where['result.viewid'] = $pid;
            empty($status) ?: $where['result.status'] = $status;
            $re = $this->pageData('usernumbertoresult', $page, $where, 'addtime desc', 'result.*,number.number,number.nickname', 'as result left join hh_usernumber as number on number.id=result.numberid');
            exit(json_encode($re, true));
        }
        $this->assign('pid', $pid);
        $this->display();
    }
}
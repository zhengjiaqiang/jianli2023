<?php

namespace Icenter\Controller;

use Icenter\Common\BaseController;

class IndexController extends BaseController
{

    public function index()
    {
        $this->_list();
        $this->assign('id', 0);
        $this->display($this->indexView);
    }

    private function _list($pusharr = [])
    {
        $d = I('get.d/d', 0);
        $pos = I('get.pos/d', 0);
        //读取当前科室
        $depart = M('department')->where(['status' => 1, 'isdel' => 0])->order('sort desc,id asc')->getField('id,name', true);
        //读取职位
        $position = M('position')->where(['status' => 1, 'isdel' => 0])->getField('id,name', true);
        //读取当前职位
        empty($d) ?: $where['depid'] = $d;
        empty($pos) ?: $where['stationid'] = $pos;
        $where['status'] = 1;
        $where['isdel'] = 0;
        !empty($pusharr) ? $where = array_merge($where, (array)$pusharr) : '';
        $where['btime'] = ['elt', time()];
        // $where['etime']=['egt',time()];
        $count = D('position')->where($where)->count();
        $page = new \Org\Util\Page($count, 10);
        $list = D('position')->where($where)->order('sort desc,id desc')->field('id,depid,stationid,etime,name')->limit($page->firstRow . ',' . $page->listRows)->select();
        //读取当前投递过的 
        $isuse = M('resumelist')->where(['uid' => $this->userId, 'status' => ['neq', 8]])->getField('rid,uid', true);
        //判断大类是否关闭
        foreach ($list as $k => $v) {
            $list[$k]['isuse'] = in_array($v['id'], array_keys($isuse)) ? 1 : 0;
            $list[$k]['isend'] = $v['etime'] - time() > 0 ? 0 : 1;
        }
        $this->assign('list', $list);
        $this->assign('show', $page->show());
        $this->assign('position', $position);
        $this->assign('depart', $depart);
        $this->assign('station', M('station')->where(['status' => 1])->getField('id,name'));
        $this->assign('dep', $d);
        $this->assign('pos', $pos);
    }

    public function resume()
    {
        if (!$this->islogin()) exit('请先登录');
        //判断当前的问卷调查是否完成
        // if(!empty($this->qinfo)){
        //   $quinfo=M('questionnairee')->where(['uid'=>$this->userId,'qid'=>$this->qinfo['id']])->find();
        //   if(empty($quinfo))  redirect('questionnairee');
        // }
        $this->display();
    }

    public function addresume()
    {
        //判断当前是否提交
        if (!IS_POST) exit();
        if (!$this->islogin()) $this->error('当前未登陆，请先登录！');
        if (!$this->isSaveQuestion()) $this->error('当前问卷调查未填写，请先完成问卷调查！', '/icenter/index/questionnairee');
        if (!D('Resumeuser')->isDownResume($this->userId)) $this->error('当前简历未提交！');
        if ($this->isUseSubmit(I('post.rid', -1, 'int'))) $this->error('当前职位已经申请！');
        if (!$this->issetResume(I('post.rid', -1, 'int'))) $this->error('当前职位已被关闭！');
        if (!$this->uploaded) $this->error('请先完成附件上传！');
        $data['uid'] = $this->userId;
        $data['rid'] = I('post.rid/d');
        $data['addtime'] = time();
        $data['vid'] = isset($this->volunteer[I('post.vid/d')]) ? I('post.vid/d') : 0;
        $data['status'] = 1;
        $data['bigid'] = I('post.pid/d', 0);
        $data['month'] = date('m');
        $data['year'] = date('Y');
        $data['day'] = date('d');
        //查询是否开启了大类
        $this->bigtypeCheck($data);
        $result = M('resumelist')->add($data);
        unset($data);
        //成功之后将简历复制
        $this->copyResume($result);
        //如果达到当前上限值
        if (C('SYS_SET.iscleanresume')) {
            $resumeCount = M('resumelist')->where(['uid' => $this->userId])->count();
            if ($resumeCount == C('SYS_SET.iscleanresume')) {
                //清空当前必要表
                M('resumeuser')->where(['uid' => $this->userId])->delete();
                M('uremusemessage')->where(['uid' => $this->userId])->delete();
            }
        }
        $this->success('当前职位申请成功！');
    }

    /**
     * 是否填写问卷调查
     */

    private function copyResume($result)
    {
        $list = M('resumeuser')->where(['uid' => $this->userId, 'status' => 1])->select();

        //$s = $list[0]['config'];
        // parse_str(base64_decode($s),$u);;

        //dd($list);
        $config = M('resumeconfig');
        $cache = M('rusercache');
        foreach ($list as $k => $v) {
            $data[$k]['cid'] = $v['cid'];
            $data[$k]['config'] = $v['config'];
            $data[$k]['htmlconfig'] = $v['htmlconfig'];
            $data[$k]['uid'] = $v['uid'];
            $data[$k]['sort'] = $config->where(['id' => $v['cid']])->getField('sort');
            $data[$k]['rid'] = $result;
        }
        //复制当前检索的信息
        $umessage = M('uremusemessage')->where(['uid' => $this->userId])->find();
        $umessage['lid'] = $result;
        //复制当前基本信息表
        $this->copyMessage();
        M('uremusemessagecache')->add($umessage);
        $cache->addAll($data);
    }

    private function copyMessage()
    {
        $usermessage = M('uremusemessage');
        $cache = M('uremusemessagecache');
        $column = '';
        $sql = '';
        $allfields = $usermessage->getDbFields();
        foreach ($allfields as $k => $v) {
            $fields = $cache->getDbFields();
            if (array_search($v, $fields) === false) {
                //不存在添加字段
                $column .= "ADD COLUMN {$v} VARCHAR(255),";
            }
        }

        $sql .= 'ALTER TABLE hh_uremusemessagecache ' . $column;
        if ($cache->execute(trim($sql, ',')) === false) return false;
        return true;
    }

    private function bigtypeCheck($data)
    {
        if (C('SYS_SET.isstartbigtype')) {
            if (empty($data['vid'])) $this->error('请重新选择您的志愿！');
            //判断是否超出了大类限制
            if ((int)$this->isBigNumbers($data['bigid']) >= C('SYS_SET.maxsendresume')) $this->error('当前投递超过系统上线！');
            //判断当前的志愿是否已经申请
            if ((int)$this->issaveVol($data['bigid'], $data['vid'])) $this->error('当前的志愿已经被申请过了，请选择其他志愿！');
        }
    }

    private function issaveVol($bigid, $vid)
    {
        return M('resumelist')->where(['bigid' => $bigid, 'vid' => $vid, 'uid' => $this->userId, 'status' => ['neq', 8]])->count();
    }

    private function isUseSubmit($id)
    {
        return M('resumelist')->where(['uid' => $this->userId, 'rid' => $id, 'status' => ['neq', 8]])->count();
    }

    private function issetResume($id)
    {
        //判断当前时间
        return M('position')->where(['status' => 1, 'btime' => ['elt', time()], 'etime' => ['egt', time()], 'id' => $id])->count();
    }

    private function isBigNumbers($bigid)
    {
        return M('resumelist')->where(['uid' => $this->userId, 'bigid' => $bigid, 'status' => ['neq', 8]])->count();
    }

    public function position()
    {
        if (!C('SYS_SET.isstartbigtype')) exit('当前打开链接错误，请关闭浏览器重试！');
        $id = I('get.id/d', 0, 'int');
        if (empty(D('Bigtype')->isIsset($id))) $this->error('当前已被删除或不存在，请刷新页面重试！');
        $this->_list(['bigtypeid' => $id]);
        $name = M('bigtype')->where(['id' => $id])->getField('name');
        $this->assign('title', $name);
        $this->assign('id', $id);
        $this->display('index');
    }

    public function info()
    {
        $id = I('get.id/d', 0, 'int');
        $info = M('position')->where(['isdel' => 0, 'id' => $id])->find();
        if (empty($info)) $this->error('当前职位不存在，请检查网络链接设置！');
        // print_r($info);
        $info['depname'] = M('department')->where(['id' => $info['depid']])->getField('name');
        $info['stationname'] = M('station')->where(['status' => 1])->getField('name');
        $this->assign('id', $id);
        $this->assign('pid', I('get.pid/d', 0));
        $this->assign('info', $info);
        $this->display();
    }

    public function initresume()
    {
        if (!IS_AJAX) exit();
    }

    public function detail()
    {
        //读取
        $id = I('get.id/d', 0);
        $pid = I('get.pid/d', 0);
        $status = M('resumeuser')->where(['status' => 0, 'uid' => $this->userId])->count();
        $usercount = M('resumeuser')->where(['uid' => $this->userId])->count();
        if (!D('Resumeuser')->isDownResume($this->userId)) exit('您的简历未提交，无法申请岗位！');
        $posinfo = M('position')->where(['id' => $id, 'status' => 1, 'isdel' => 0])->find();
        if (empty($posinfo)) exit('当前岗位不存在，请刷新重试！');
        if (time() < $posinfo['btime'] || time() > $posinfo['etime']) exit('当前岗位不再有效时间内！');

        $resumelist = M('resumeuser')->where(['status' => 1, 'uid' => $this->userId])->field('config,htmlconfig')->select();
        $m = [];
        $ques = [];
        foreach ($resumelist as $k => $v) {
            parse_str(base64_decode($v['config']), $u);
            $m = array_merge($m, (array)$u);
            $ques[$k] = $v['htmlconfig'];
        }
        $this->assign('info', $posinfo);
        // $this->assign('volunteer',$this->volunteer);
        $this->assign('user', $m);
        $this->assign('ques', $ques);
        $this->assign('pid', $pid);
        $this->assign('id', $id);
        $this->display();
    }

    public function volunteer()
    {
        if (!IS_AJAX) exit();
        $pid = I('get.pid/d', 0);
        $id = I('get.id/d', 0);
        $usev = M('rusercache')->where(['bid' => $pid])->field('vid,rid')->select();

    }

    public function checkuse()
    {
        if (!IS_POST) exit();
        $id = I('post.id/d', 0);
        //检查当前岗位是否允许申请
        $posinfo = M('position')->where(['id' => $id, 'status' => 1, 'isdel' => 0, 'btime' => ['elt', time()], 'etime' => ['egt', time()]])->find();
        //查看是否申请
        $isuse = M('resumelist')->where(['uid' => $this->userId, 'rid' => $id])->count();
        //判断当前是否
        if (empty($posinfo) || $isuse > 0) $this->error('当前岗位不存在或已经申请！');
        else $this->success('当前岗位允许被申请！');
    }

    public function logout()
    {
        session('user_auth', null);
        session('user_token', null);
        redirect('/index');
    }

    public function batch()
    {
        $id = I('get.id', 0);
        $pid = I('get.pid/d', 0);
        $status = M('resumeuser')->where(['status' => 0, 'uid' => $this->userId])->count();
        $usercount = M('resumeuser')->where(['uid' => $this->userId])->count();
        if (!D('Resumeuser')->isDownResume($this->userId)) $this->error('您的简历未提交，无法申请岗位！', '/icenter/index/resume'); //exit('您的简历未提交，无法申请岗位！');
        $resumelist = M('resumeuser')->where(['status' => 1, 'uid' => $this->userId])->field('config,htmlconfig')->select();
        $m = [];
        $ques = [];
        foreach ($resumelist as $k => $v) {
            parse_str(base64_decode($v['config']), $u);
            $m = array_merge($m, (array)$u);
            $ques[$k] = $v['htmlconfig'];
        }
        $id = array_filter(explode('_', trim($id, '_')));
        if (empty($id)) $this->error('当前未选择要申请的岗位！');
        $list = [];
        //查看总的提交了多少
        $useinfo = M('resumelist')->where(['uid' => $this->userId, 'bigid' => $pid])->getField('rid,vid', true);

        // print_r($useinfo);die;
        foreach ($id as $k => $v) {
            $posinfo = M('position')->where(['id' => $v, 'status' => 1, 'isdel' => 0, 'bigtypeid' => $pid])->find();
            // $isuse=M('resumelist')->where(['rid'=>$v,'uid'=>$this->userId])->count();
            if (empty($posinfo) || time() < $posinfo['btime'] || time() > $posinfo['etime']) {
                unset($id[$k]);
                continue;
            }
            //查询是否申请过
            $list[$k]['id'] = $v;
            $list[$k]['isuse'] = isset($useinfo[$v]) ? 1 : 0;
            $list[$k]['name'] = $posinfo['name'];
        }
        $this->assign('count', ['ucount' => count($useinfo), 'allcount' => C('SYS_SET.maxsendresume')]);
        $this->assign('list', $list);
        $this->assign('info', $posinfo);
        // $this->assign('volunteer',$this->volunteer);
        $this->assign('bigname', M('bigtype')->where(['id' => $pid, 'status' => 1, 'isdel' => 0])->getField('name'));
        $this->assign('user', $m);
        $this->assign('ques', $ques);
        $this->assign('pid', $pid);
        $this->display();
    }

    public function questionnairee()
    {
        if (empty($this->qinfo)) redirect('index');
        $this->assign('form', str_replace(array("\r\n", "\r", "\n"), '', htmlspecialchars_decode(base64_decode($this->qinfo['formhtml'], true))));
        $this->display();
    }

    public function questionsave()
    {
        if (!IS_POST) exit();
        $post = I('post.');
        $where['uid'] = $m['uid'] = $this->userId;
        $where['qid'] = $m['qid'] = $this->qinfo['id'];
        $ques = M('questionnairee')->where($where)->find();
        $m['ip'] = get_client_ip();
        $m['config'] = json_encode($post, JSON_UNESCAPED_UNICODE);
        if (empty($ques)) {
            $m['addtime'] = time();
            M('questionnairee')->add($m);
        } else M('questionnairee')->where($where)->save($m);
        $this->success('当前“' . $this->qinfo['name'] . '”保存成功,请填写简历...', 'resume');
    }

    public function upload()
    {
        if (!$this->issubmit) $this->error('当前“我的简历”未完成，请先完成！', 'resume');
        $static_parent = C('STATIC_PARENT');
        $static_parent[1]['name'] = '学历学位证书';
        $static_parent[1]['must'] = 1;
        $static_parent[2]['name'] = '规培证';
        $static_parent[5]['name'] = '医师/护士执照';
        unset($static_parent[5]['join']);
        unset($static_parent[5]['value']);
        unset($static_parent[5]['must']);
        $static_parent[6]['name'] = '自制正式简历';
        $static_parent[7]['name'] = '承诺书（下载或自制承诺书后拍照上传）<a href="/static/letter/letters.docx" style="color: red;font-weight: bold">点击下载</a>';

        //$this->assign('images', C('STATIC_PARENT'));
        $this->assign('images', $static_parent);
        $filelist = M('file')->where(['uid' => $this->userId])->order('addtime asc')->select();
        $list = [];
        //获取当前的
        foreach ($filelist as $k => $v) {
            $v['ext'] = array_pop(explode('.',$v['filename']));
            $list[$v['parentid']][] = $v;
        }

        //dd($list);
        $this->assign('filelist', $list);

        //读取我的部分信息
        $this->assign('nead', $this->nead);
        $this->assign('isupload', $this->userInfo['uploadresume']);
        // $this->assign('isstring',$this->userInfo['uploadstring']);
        $this->display();
    }

    public function messageUpload()
    {
        if (!IS_POST) exit();
        $parentid = I('post.id');
        $files = $_FILES['file'];
        $upload = new \Think\Upload();
        $upload->maxSize = 2097152;
        $upload->rootPath = './upload/file/';
        $upload->exts = [
            "png", "jpg", "jpeg", "gif", "bmp", "pdf"
        ];
        $info = $upload->uploadOne($files);
        if (!$info) $this->ajaxReturn(['code' => 0, 'msg' => '当前附件上传失败。失败原因：' . $upload->getError()]);
        $must = [1, 3, 5];
        $data['filename'] = $files['name'];
        $data['uid'] = $this->userId;
        $data['addtime'] = NOW_TIME;
        $data['parentid'] = $parentid;
        $data['filepath'] = '/upload/file/' . $info['savepath'] . $info['savename'];
        $data['isupdate'] = 0;
        //if(in_array($parentid,$must))  M('user')->where(['id'=>$this->userId])->save([C('STATIC_PARENT')[$parentid]['field']=>1]);
        //自制简历
        if ($parentid == 6) {
            M('file')->where(['parentid' => $parentid, 'uid' => $this->userId])->delete();
        }
        $result = M('file')->add($data);
        if ($result === false)
            $this->ajaxReturn(['code' => 0, 'msg' => '当前附件上传失败。失败原因：请检查网络链接设置']);
        else $this->ajaxReturn(['code' => 1, 'msg' => '当前附件上传成功', 'location' => '/uploadfile/file/' . $info['savepath'] . $info['savename'], 'data' => ['src' => '/uploadfile/file/' . $info['savepath'] . $info['savename']]]);
    }

    public function imageDelete()
    {
        if (!IS_POST) exit();
        $id = I('post.id/d');
        $fileClass = M('file');
        $path = $fileClass->where(['id' => $id, 'uid' => $this->userId])->field('filepath,parentid')->find();
        $result = $fileClass->where(['id' => $id, 'uid' => $this->userId])->delete();
        if ($result === false) $this->error('当前图片删除失败，请检查网络链接设置！');
        else {
            unlink('.' . $path['filepath']);
            $this->success('当前图片删除成功');
        }
    }
}
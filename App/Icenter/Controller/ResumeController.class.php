<?php

namespace Icenter\Controller;

use Icenter\Common\BaseController;

class ResumeController extends BaseController
{
    public function form()
    {
        if (!IS_AJAX) return false;
        //读取当前title
        $id = I('get.id/d', 0);
        $title = D('resumeconfig')->getTitle();
        if (!empty($title)) {
            $id = empty($id) ? D('resumeconfig')->getFirstId() : $id;
            $u = D('resumeuser')->getDetail($id, $this->userId);
            $form = !empty($u['htmlconfig']) ? $u['htmlconfig'] : D('resumeconfig')->getDetail($id);
            //读取当前用户提交的表单已经数据
            $this->ajaxReturn(['title' => $title, 'form' => $form, 'user' => $u['config'], 'code' => 0, 'id' => $id]);
        }
    }

    public function usersave()
    {
        if (!IS_POST) return false;
        $post = I('post.');
//      dd($post);
        $select = $post['select'];
        $post = $post['value'];
        if (!$this->issetMysqlData($select)) $this->error('当前数据实例化失败，请刷新重试！');

        //实习单位
        $practiceunits = [];
        //工作单位
        $workunits = [];
        //导师
        $tutors = [];
        //专业基地
        $jidizhuanyes = [];
        if ($post) {
            $values = $post;
            foreach ($values as $key => $item) {
                //工作经历
                if (strpos($key, 'GZ_1') !== false && isset($values['GZ_1'])) {
                    $workunits[] = $item;
                }
                //实习
                if (strpos($key, 'SX_1') !== false && isset($values['SX_1'])) {
                    $practiceunits[] = $item;
                }

                //导师
                if (strpos($key, 'JY_3') !== false && isset($values['JY_3'])) {
                    $tutors[] = $item;
                }

                //专业基地
                if (strpos($key, 'ZY_2') !== false && isset($values['ZY_2'])) {
                    $jidizhuanyes[] = $item;
                }

            }
            if (isset($values['SX_1'])) {
                $select['practiceunits'] = !empty(array_filter($practiceunits)) ? implode(',', $practiceunits) : '';
            }
            if (isset($values['GZ_1'])) {
                $select['workunits'] = !empty(array_filter($workunits)) ? implode(',', $workunits) : '';
            }

            if (isset($values['JY_3'])) {
                $select['tutors'] = !empty(array_filter($tutors)) ? implode(',', $tutors) : '';
            }

            if (isset($values['ZY_2'])) {
                $select['jidizhuanyes'] = !empty(array_filter($jidizhuanyes)) ? implode(',', $jidizhuanyes) : '';
            }
        }

        //修改当前
        $swhere['uid'] = $this->userId;
        $select['addtime'] = time();
        $select['uid'] = $this->userId;

        if ($post['cid'] == 45 && isset($post['quanrizhitype'])) {
            $select['quanrizhitype'] = $post['quanrizhitype'];
        }
        if ($post['cid'] == 45 && isset($post['zaizhitype'])) {
            $select['zaizhitype'] = $post['zaizhitype'];
        }
        $usermessage = M('uremusemessage')->where($swhere)->find();
        if (empty($usermessage)) M('uremusemessage')->add($select);
        else M('uremusemessage')->where($swhere)->save($select);
        $where['uid'] = $data['uid'] = $this->userId;
        $data['htmlconfig'] = base64_encode($post['formhtml']);
        unset($post['formhtml']);
        $where['cid'] = $data['cid'] = $post['cid'];
        $where['isouttime'] = $data['isouttime'] = 0;
        $where['rid'] = 0;
        //查找当前未过期的时候存在
        $user = M('resumeuser');
        $resinfo = $user->where($where)->find();
        $data['config'] = base64_encode(http_build_query($post));
        $data['status'] = 1;
        if (empty($resinfo)) {//追加简历
            $data['addtime'] = time();
            $rs = $user->add($data);
        } else  $rs = $user->where($where)->save($data);
        //要同时满足
        $status = $rs === false ? ['status' => 0, 'message' => '当前提交失败，请检查网络连接设置！', 'error' => ''] : ['status' => 1, 'message' => '当前简历保存成功！'];
        $this->ajaxReturn($status);
    }

    public function layuiUpload()
    {
        if (!IS_POST) exit();
        $files = $_FILES['file'];
        $upload = new \Think\Upload();
        $upload->maxSize = 204800000;
        $upload->rootPath = './upload/file/';
        $upload->exts = [
            "png", "jpg", "jpeg", "gif", "bmp"
        ];// 设置附件上传类型
        $info = $upload->uploadOne($files);
        if (!$info) $this->ajaxReturn(['code' => 0, 'msg' => '当前附件上传失败。失败原因：' . $upload->getError()]);
        else $this->ajaxReturn(['code' => 1, 'msg' => '当前附件上传成功', 'location' => '/upload/file/' . $info['savepath'] . $info['savename'], 'data' => ['src' => '/upload/file/' . $info['savepath'] . $info['savename']]]);
    }

    private function issetMysqlData($select)
    {
        $column = '';
        $sql = '';
        $usermessage = M('uremusemessage');
        foreach ($select as $k => $v) {
            $fields = $usermessage->getDbFields();
            if (array_search($k, $fields) === false) {
                //不存在添加字段
                $column .= "ADD COLUMN {$k} VARCHAR(255),";
            }
        }
        $sql .= 'ALTER TABLE __TABLE__ ' . $column;
        if ($usermessage->execute(trim($sql, ',')) === false) return false;
        return true;
    }
}
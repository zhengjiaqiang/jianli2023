<?php  
namespace Icenter\Model;
use Think\Model;
class BigTypeModel extends model{
    public function isStart(){

    }
    public function getList($where=[],$sort='sort desc',$field='*'){
        $list=$this->where(array_merge(['status'=>1,'isdel'=>0],$where))->order($sort)->field($field)->select();
        foreach($list as $k=>$v){
            //当前下面所有状态是不是都已经结束
            if($v['etime'] < time()) $list[$k]['end']=1;
            else{
                $vlist=M('position')->where(['isdel'=>0,'status'=>1,'btime'=>['elt',time()],'etime'=>['egt',time()],'bigtypeid'=>$v['id']])->limit(4)->field('id,name')->select();
                foreach($vlist as $key=>$val){
                    $list[$k]['zhi'].=$val['name'].' \ ';
                }
                trim($list[$k]['zhi'], '\ ');
                $list[$k]['end']=M('position')->where(['isdel'=>0,'status'=>1,'btime'=>['elt',time()],'etime'=>['egt',time()],'bigtypeid'=>$v['id']])->count();
            }
        }
        return $list;
    }
    public function isIsset($id){
        return $this->where(['isdel'=>0,'id'=>$id,'status'=>1,'btime'=>['elt',time()],'etime'=>['egt',time()]])->find();
    }
}
?>
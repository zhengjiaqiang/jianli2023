<include file="Common:header"/>
<link rel="stylesheet" type="text/css" href="/static/index/css/index.css?v=4">
<style type="text/css">
  .content-container{
    height:650px;
    overflow-y: auto;
    background: #fff;
  }
</style>
  <header></header>
  
    <div id="content">
      <include file="Common:left"/>
      <div id="right">
        <!-- <div class="fulldiv" id="fulldiv"></div> -->
        
        	<div class="people jianli" style="position: relative;">
              <table width="860" border="0" cellspacing="0" cellpadding="0" class="view">
                <tbody>
                    <tr>
                      <th scope="row">招聘有效期</th>
                      <td>{$info['etime']|date="Y-m-d",###}</td>
                    </tr>
                    <tr>
                      <th scope="row">职位名称</th>
                      <td>{$info['name']}</td>
                    </tr>
                    <tr>
                      <th scope="row">所属部门</th>
                      <td>{$info['depname']}</td>
                    </tr>
                    <tr>
                      <th scope="row">招聘人数</th>
                      <td>{$info['number']}</td>
                    </tr>
                    <tr>
                      <th scope="row">工作内容与要求</th>
                      <td>
                        {$info['intro']|htmlspecialchars_decode}
                      </td>
                    </tr>
                    <tr>
                      <th scope="row">备注</th>
                      <td>{$info['remark']}</td>
                    </tr>
                </tbody>
              </table>
        	</div> 
          <div class="personal-bottom" >
            <div class="personal-select">
              <h1>申请岗位：{$info['name']}</h1>
              <if condition="C('SYS_SET.isstartbigtype') neq 0">
                <select name="vid">
                  <foreach name="volunteer" item="v" key="k">
                    <option value="{$k}">{$v}</option>
                  </foreach>
                </select>
              </if>
              <button class="emclick" data-id="{$id}">申请岗位</button>
            </div>
          </div>

      </div>
    </div>	
</div>
  </div>
</body>
<script src="/static/layui/layui.js"></script>
<script>
  
layui.use(['jquery','layer','form'],function(){
  $=layui.jquery,l=layui.layer,form=layui.form,_id={$id},_pid={$pid};
  setTimeout(function(){
      $("#fulldiv").css('display','none');
    },600);
  //初始化简历
  var _index=parent.layer.msg('正在进行实例化简历.....',{icon:16,time:66666});
  form.render();
  $("#strPhoto1").remove();
  $('.emclick').on('click',function(){
    var _id=$(this).data('id'),_vid=$("[name='vid']").val();
    <if condition="C('SYS_SET.isstartbigtype') neq 0">
    if(_vid < 1) {
      layer.msg('请选择志愿！',{icon:2,time:3000});
      return false;
    }
    </if>
    l.confirm('确定投递当前的岗位？',function(){
      layer.msg('正在进行投递简历，请勿关闭浏览器.....',{icon:16,time:66666});
      $.post('/icenter/index/addresume',{'rid':_id,'vid':_vid,'pid':_pid},function(_res){
        if(_res.status){
          layer.msg(_res.info,{icon:1,time:3000},function(){
            parent.location.href='/icenter/resumelist';
          });
          //
        }else layer.msg(_res.info,{icon:2,time:3000},function(){
          CheckIsNullOrEmpty(_res.url) ? location.href=_res.url : '';
        });
      },'JSON');
    });
  });
  function CheckIsNullOrEmpty(value) {
					var reg = /^\s*$/;
					return (value != null && value != undefined && !reg.test(value))
	}
  var script=$('<script src="/static/index/js/pdf.js"><\/script>');
  $('body').append(script);
  parent.layer.close(_index);
})


</script>
</html>
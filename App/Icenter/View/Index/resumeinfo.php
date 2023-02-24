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
                      <td>{$info['stationname']}</td>
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
  
  var script=$('<script src="/static/index/js/pdf.js"><\/script>');
  $('body').append(script);
  parent.layer.close(_index);
})


</script>
</html>
<include file="Common:header"/>
<link rel="stylesheet" type="text/css" href="/static/index/css/index.css?v=5">
<link rel="stylesheet" type="text/css" href="/static/index/css/batch.css">
<style type="text/css">
	.layui-form-item2{width: 868px;}
	.peoson-container{height:400px;background: #fff;}
	.people {
	    overflow: hidden;
	    width: 100%;
	    height: 100%;
	    overflow-y: auto;
	}
	.personal-details{
		width: 930px;
	    margin: 0 auto;
	    margin-bottom: 50px;
	}
	.layui-form-item2{
		padding: 10px 0px;
	}
	.layui-form-label{padding: 10px 15px;}
</style>
		<div class="fulldiv" id="fulldiv"></div>
		<div class="peoson-container">
			<div class="people" style="position: relative;">
				<div class="personal-details layui-form">
					<foreach name="ques" item="v">
						<div class="details ">
						{$v|base64_decode|htmlspecialchars_decode}
						</div>
					</foreach>
				</div>
			</div>
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
<foreach name="user" item="v" key="k">
	<label style="display: none;" class="showtitle" id="{$k}">{$v}</label>
</foreach>
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
				}else layer.msg(_res.info,{icon:2,time:3000});
			},'JSON');
		});
	});
	
	var script=$('<script src="/static/index/js/pdf.js"><\/script>');
	$('body').append(script);
	parent.layer.close(_index);
})


</script>
<script type="text/javascript">
	
</script>
</html>
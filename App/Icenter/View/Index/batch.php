<include file="Common:header"/>
<link rel="stylesheet" type="text/css" href="/static/index/css/index.css?v=7">
<link rel="stylesheet" type="text/css" href="/static/index/css/batch.css">
<style type="text/css">
	.content-container{
		height:650px;
		overflow-y: auto;
		background: #fff;
	}
	.layui-card-text{background: #fafafa;}
	.layui-form-item2{background: #fafafa;}
	.layui-img ul{
		display: block;
	}
	.layui-img ul li{
		width: 100%;
		margin-bottom: 10px;
	}
	/*.layui-img ul li a{display: block;}*/
	.layui-img ul li img{
		width: auto;
		display: block;
		margin:0 auto;
	}
</style>
	<header></header>
	<div class="content-container">
		<div id="content">
			<include file="Common:left"/>
			<div id="right">
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
				
			</div>
		</div>
	</div>
	<div class="personal-bottom" >
		<div style="text-align:center;font-size:14px;margin:0 0 10px 0">当前“{$bigname}”已经投递了<strong class="numgangwei" style="font-size:20px;">{$count['ucount']}</strong>岗位，还可以投递<strong class="numgangweied" style="font-size:20px;color:red;">{$count['allcount']-$count['ucount']}</strong>次岗位！</div>
		<foreach name="list" item="v">
			<div class="personal-select">
				<h1>申请岗位：{$v['name']}</h1>
				<if condition="C('SYS_SET.isstartbigtype') neq 0">
					<select name="vid" <if condition="$v['isuse'] eq 1"> disabled="disabled" class="displayselect" </if>>
						<foreach name="volunteer" item="val" key="k">
							<option value="{$k}">{$val}</option>
						</foreach>
					</select>
				</if>
				<button   data-id="{$v['id']}" class="<if condition='($v["isuse"] eq 0) '>emclick<else/>displayselect</if>"><if condition="$v['isuse'] eq 1">已经申请<else/>申请岗位</if></button>
				<span class="<if condition='($v["isuse"] eq 0) '>remove<else/>remove-true</if>">×</span>
			</div>
		</foreach>
	</div>
	<!-- </div> -->
	<!-- </div> -->
<foreach name="user" item="v" key="k">
	<label style="display: none;" class="showtitle" id="{$k}">{$v}</label>
</foreach>
</div>
</div>	
</body>
<script src="/static/index/js/jquery-3.5.1.min.js"></script>
<script src="/static/layui/layui.js"></script>
<script>
	
layui.use(['jquery','layer','form'],function(){
	$=layui.jquery,l=layui.layer,form=layui.form,_pid={$pid};
	setTimeout(function(){
    	$("#fulldiv").css('display','none');
    },600);
	//初始化简历
	var _index=parent.layer.msg('正在进行实例化简历.....',{icon:16,time:66666});
	form.render();
	$('#strPhoto1').remove();
	$('.close-span').remove();
	$(".layui-img").prev('div').remove();
	$("#aupfile").text('');
	$('.layui-chengluo').remove();
	if(!$("#aupfile").attr('style')) $("#aupfile").remove();
	
	$('.emclick').on('click',function(){
		var _id=$(this).data('id'),_vid=$(this).parents('.personal-select').find('select').val(),_this=$(this);
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
					layer.msg(_res.info,{icon:1,time:3000});
					_this.parents('.personal-select').children('select').addClass('displayselect');
					_this.parents('.personal-select').children('select').attr('disabled','disabled');
					_this.parents('.personal-select').children('button').addClass('displayselect');
					_this.parents('.personal-select').children('button').attr('disabled','disabled');
					$('.numgangwei').text({$count['ucount']}+1);
					$('.numgangweied').text({$count['allcount']-$count['ucount']}-1);
					// $('.remove').remove();
					_this.parents('.personal-select').children('span').remove();
					// $(this).parents('.personal-select').find('[name="vid"]').addClass('displayselect');
					// $(this).parents('.personal-select').find('select').attr('disabled','disabled');
				}else layer.msg(_res.info,{icon:2,time:3000});
			},'JSON');
		});
	});

	$(".remove").on('click',function(){
		$(this).parents('.personal-select').remove();
	});
	var script=$('<script src="/static/index/js/pdf.js?v=3"><\/script>');

	
	$('body').append(script);
	parent.layer.close(_index);
})


</script>

<!-- <script src="/static/index/js/pdf.js"></script> -->
<script type="text/javascript">
	
</script>
</html>
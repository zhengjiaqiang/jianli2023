<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台列表</title>
	<link href="/static/index/css/Reset.css" rel="stylesheet">
	<link href="/static/index/css/style.css" rel="stylesheet">
	<meta name="renderer" content="webkit"/>
	<meta name="force-rendering" content="webkit"/>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1"/>
	<link rel="stylesheet" type="text/css" href="/static/form/magicaldrag/assets/drag/ui/layui/2.5.4/css/layui.css">
</head>
<!--[if lt IE 9]>
	<script type="text/javascript" src="/static/index/js/json2.js"></script>
	<![endif]-->
<body>
<link rel="stylesheet" type="text/css" href="/static/index/css/index.css?v=5">
<link rel="stylesheet" type="text/css" href="/static/index/css/batch.css">
<!-- <link rel="stylesheet" type="text/css" href="/static/index/css/css.css?v=4"> -->
<style type="text/css">
		/*.people{height:710px;}*/
		.personal-bottom{padding-bottom: 60px;}
		.personal-select .displayselect{
			background-color: #ccc;
		}
		.table-list-border{width: 870px !important;}
		.personal-details{margin-bottom: 210px;}
		.layui-card-text{border: 1px solid #c9cadd;padding-top: 10px;box-sizing: border-box;}
		.remove-true{display: none;}
		input[type=radio ] {
		    width: 24px;
		    height: 26px;
		    margin: 0;
		    padding: 0;
		    opacity: 0;
		    filter: "alpha(opacity=0)";
		    cursor: pointer;
		}
		input[type=radio] + label {
		    display: inline-block;
		    margin-left: -28px;
		    padding-left: 28px;
		    background: url(/static/index/images/radio.png) no-repeat 0px 0px;
		    line-height: 26px;
		    cursor: pointer;
		}
		input[type=radio]:checked + label {
		    background: url(/static/index/images/radio.png) no-repeat 0px -52px;
		    cursor: pointer;
		}
		.personal-select{margin-bottom: 20px;}
		.btn-sm, .btn-group-sm > .btn {
		    border-width: 4px;
		    font-size: 13px;
		    padding: 4px 9px;
		    line-height: 1.38;
		    background-image: none !important;
		    border: 5px solid #FFF;
		    border-radius: 0;
		    box-shadow: none !important;
		    display: inline-block;
    
		}
		.btn-yellow, .btn-yellow:focus {
		    background-color: #fee188 !important;
		    border-color: #fee188;
		}
		.btn-purple, .btn-purple:focus {
		    background-color: #9585bf !important;
		    border-color: #9585bf;
		    color: #FFF !important;
		}
		.peoson-container{height:450px;background: #fff;}
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
		.layui-img ul{
			display: block;
		}
		.layui-img ul li{
			width: 100%;
			margin-bottom: 10px;
		}
		.layui-img ul li img{
			width: auto;
			display: block;
			margin:0 auto;
		}
		.otherul{
			width:100%;
			background:#f5f5f5;
		}
		.otherul ul{
			width:870px;
			margin:0 auto;
			padding:15px 0;
		}
		.otherul ul li{
			height:30px;
			line-height:30px;
		}
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
			<ul>
				<foreach name="other" item="v">
					<li>{$v['bigtype']} / {$v['postion']} / {$v['vol']} </li>
				</foreach>
			</ul>
			<if condition="C('SYS_SET.ISSTARTBIGTYPE')">
				<div class="personal-select">
					<h1>当前志愿：<b>{$volunteer[$info['vid']]}</b></h1>
				</div>
			</if>
			<form class="layui-form">
			<input type="hidden" value="{$pid}" readonly name="rid" lay-verify="require|number"/>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  	<tbody>
				  	<tr>
				    	<td align="center" valign="center">
				    		<input type="radio" name="status" title="推荐下一步" lay-verify="req" value="10" >
							<input type="radio" name="status"  title="暂缓执行" lay-verify="req" value="9">
							<input type="radio" name="status"   title="入库操作" lay-verify="req" value="8">
					       
							<if condition="$islast"> <button type="button" class="btn btn-yellow btn-sm" lay-submit lay-filter="submitresume">批量操作</button><button type="button" class="btn btn-purple btn-sm" lay-submit lay-filter="setresume">确定</button><else/><button type="button" class="btn btn-yellow btn-sm" lay-submit lay-filter="nextresume">下一封</button></if>
					        
				    	</td>
				  	</tr>
				  	<tr height="10"></tr>
				</tbody>
			</table>
			</form>
		</div>
	<!-- </div>
	</div> -->
<foreach name="user" item="v" key="k">
	<label style="display: none;" class="showtitle" id="{$k}">{$v}</label>
</foreach>
</div>
</div>	
</body>
<script src="/static/index/js/jquery-3.5.1.min.js"></script>
<script src="/static/layui/layui.js"></script>
<script>
	$(function(){$('.sctx').remove();})
layui.use(['jquery','layer','form'],function(){
	$=layui.jquery,l=layui.layer,form=layui.form,_id={$pid},p={$p},ids='{$ids}';
	setTimeout(function(){
    	$("#fulldiv").css('display','none');
	},600);
	form.verify({
		req: function(value,item){
			var verifyName=$(item).attr('name')
			, verifyType=$(item).attr('type')
			,formElem=$(item).parents('.layui-form')
			,verifyElem=formElem.find('input[name='+verifyName+']')
			,isTrue= verifyElem.is(':checked')
			,focusElem = verifyElem.next().find('i.layui-icon');
			if(!isTrue || !value){
				focusElem.css(verifyType=='radio'?{"color":"#FF5722"}:{"border-color":"#FF5722"});
				focusElem.first().attr("tabIndex","1").css("outline","0").blur(function() {
					focusElem.css(verifyType=='radio'?{"color":""}:{"border-color":""});
				}).focus();
				return '必填项不能为空';
			}
		}
	});
	//初始化简历
	var _index=parent.layer.msg('正在进行实例化简历.....',{icon:16,time:66666});
	form.render();
	// $("#strPhoto1").remove();
	// $('.close-span').remove();
	// $(".layui-img").prev('div').remove();
	// $("#aupfile").text('');
	// $('.layui-chengluo').remove();
	// if(!$("#aupfile").attr('style')) $("#aupfile").remove();
	parent.layer.close(_index);
	var script=$('<script src="/static/index/js/pdf.js"><\/script>');
	$('body').append(script);
	form.on('submit(setresume)', function(data){
		$.post('/systemv/resume/isnext',data.field,function(_res){
			layer.msg(_res.info,{icon:(_res.status== 0 ? 2 : 1),time:2000},function(){
				var index=parent.layer.getFrameIndex(window.name);
				// window.parent.location.reload();
				parent.layer.close(index);
			});
		},'JSON');
		return false;
	});
	form.on('submit(submitresume)', function(data){
		data.field['ids']=ids;
		$.post('/systemv/resume/submitresume',data.field,function(_res){
			layer.msg(_res.info,{icon:(_res.status== 0 ? 2 : 1),time:2000},function(){
				parent.layer.getFrameIndex(window.name)
				var index=parent.layer.getFrameIndex(window.name);
				// window.parent.location.reload();
				parent.layer.close(index);
				// top.document.getElementById('mainFrame').contentWindow.a();
			});
		},'JSON');
		return false;
	});
	
	form.on('submit(nextresume)',function(data){
		$.post('/systemv/resume/isnext',data.field,function(_res){
			layer.msg(_res.info,{icon:(_res.status== 0 ? 2 : 1),time:2000},function(){
				if(_res.status == 1){
					var index=parent.layer.getFrameIndex(window.name);
					// window.parent.location.reload();
					parent.layer.close(index);
				}
			});
		},'JSON');
	});	
})
</script>


</html>
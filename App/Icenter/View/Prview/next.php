<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>上海市胸科医院</title>
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
				<h1>当前志愿：<b>{$volunteer[$info['vid']]}</b></h1>
			</div>
			<form class="layui-form">
			<input type="hidden" value="{$pid}" readonly name="rid" lay-verify="require|number"/>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  	<tbody>
				  	<tr>
				    	<td align="center" valign="center">
				    		<input type="radio" name="status" title="需要" lay-verify="req" value="10" >
							<input type="radio" name="status"   title="不需要" lay-verify="req" value="8">
							<if condition="$islast"><button type="button" class="btn btn-purple btn-sm" lay-submit lay-filter="setresume">确定</button><else/><button type="button" class="btn btn-yellow btn-sm" lay-submit lay-filter="nextresume">下一封</button></if>
					        
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
<script src="/static/layui/layui.js"></script>
<script>
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
	$("#strPhoto1").remove();
	parent.layer.close(_index);
	var script=$('<script src="/static/index/js/pdf.js"><\/script>');
	$('body').append(script);
	form.on('submit(setresume)', function(data){
		$.post('/prview/isnext',data.field,function(_res){
			layer.msg(_res.info,{icon:(_res.status== 0 ? 2 : 1),time:2000},function(){
				parent.layer.close(parent.layer.getFrameIndex(window.name));
			});
		},'JSON');
		return false;
	});
	form.on('submit(nextresume)',function(data){
		$.post('/prview/isnext',data.field,function(_res){
			layer.msg(_res.info,{icon:(_res.status== 0 ? 2 : 1),time:2000},function(){
				if(_res.status == 1){
					parent.layer.close(parent.layer.getFrameIndex(window.name));
				}
			});
		},'JSON');
	});	
})
</script>


</html>
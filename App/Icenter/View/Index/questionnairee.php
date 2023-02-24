<include file="Common:header"/>
<link rel="stylesheet" type="text/css" href="/static/index/css/css.css?v=3">
<style type="text/css">
	.layui-card-body{padding: 10px 0px;}
	.layui-marginTop{margin-top: 20px;}
	#aupfile{cursor: auto;}
	.font-color{display: inline-block;color: red;padding: 0 5px;}
	.layui-chengluo .layui-form-checkbox i{left: 0;border-left: 1px solid #d2d2d2;}
	.layui-chengluo .layui-form-checkbox span{margin-left: 30px;}
	.layui-chengluo .layui-form-checkbox{margin-top: 20px;}
	.layui-form-label{font-weight: bold;width: 100%;text-align: left;float: none;font-size: 16px;}
	.layui-card-header{height: auto;line-height: 32px;}
	.layui-input-block{
		width: 100%;
		float: none;margin-left: 20px;
	}

</style>

<body id="body">
	<header>
	    
	</header>
	<div id="content">
		<include file="Common:left"/>
	    <div id="right">
	    	<div class="title"><h3>{$qinfo['name']}</h3></div>
	        <div class="jianli">
	            <div class="tab_bot">
					<form class="layui-form">
						{$form}
						<div class="step_a">
							<a href="javascript:;" lay-submit class="save" lay-filter="save">提交</a>
						</div>
					</form>
	            </div>
	        </div>
	    </div>
	</div>
<include file="Common:footer"/>
<script type="text/javascript">
	var DEFAULT_VERSION = 8.0;  
    var ua = navigator.userAgent.toLowerCase();  
    var isIE = ua.indexOf("msie")>-1;   
    var safariVersion;  
    if(isIE) alert('为了获得更好的用户体验，请在电脑端使用火狐、谷歌、 360浏览器填写简历。');
</script>
<script> 
	layui.use(['jquery','form','layer'],function(){
		var $=layui.jquery,form=layui.form,layer=layui.layer;
		form.verify({
			errormsg:function(value,item){
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
					return '“'+$(item).parents('.layui-form-item').find('.layui-form-label').html()+'”必填';
					// return typeof($(i).attr('error-message')) != 'undefined' ? $(i).attr('error-message') : (typeof($(i).attr('placeholder')) != 'undefined' ? $(i).attr('placeholder') : '请勾选本人承诺！');
				}
			},
		});
		var fun={
			_init:function(){
				var _this= this;
				_this._save();
			},
			_data:{

			},
			_save:function(){
				var _this = this;
				form.on('submit(save)',function(data){
					$.post('/index/questionsave',data.field,function(res){
						if(!res.status) layer.msg(res.message,{icon:2,time:2000});
						else layer.msg(res.info,{icon:1,time:2000},function(){
							location.href=res.url;
						});
					},'JSON');
				});
				return false;
			}
		};
		fun._init();
	});
</script>	
</body>
</html>
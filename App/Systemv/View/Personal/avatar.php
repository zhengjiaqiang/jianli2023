<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{$ptitle}</title>
		<include file="Common:edittopwin" />
		<style>
			.zdbtn{
				width:200px;
				height:35px;
				background:#f9f9f9;
				position:absolute;
				bottom:44px;
				left:40px;
				z-index: 9999;
			}
		</style>
	</head>

	<body class="diy-css">
		<div class="zdbtn"></div>
		<div style="margin:20px; text-align: center;">
			<div id="altContent">

			</div>
		</div>
		<include file="Common:editfooter" />
		<script type="text/javascript" src="/static/plugin/faustcplus/swfobject.js"></script>
		<script type="text/javascript">
		function uploadevent(status){
			if(typeof(status) == 'string'){
				status = eval("("+status+")");
			}
			if(typeof(status) == 'object'){
				switch(status.status){
					case 1:
						MCMS.alert('头像上传成功');
						parent.layer.isuplaod = true;
						parent.layer.closeAll();
					break;
					default:
						alert('上传失败');
				}
			}
		}

		var flashvars = {
			"jsfunc":"uploadevent",
			"imgUrl":'/static/icenter/static/img/timg.png',//"/upload/avatar/101/<?php echo ($uid.substr(md5($uid),8,16))  ?>.jpg",
			"pid":"75642723",
			"pSize": "300|300|150|150|60|60|40|40",
			"uploadSrc":true,
			"showBrow":true,
			"showCame":true,
			"uploadUrl":"__CONTROLLER__/avatar?action=upload"
		};

		var params = {
			menu: "false",
			scale: "noScale",
			allowFullscreen: "true",
			allowScriptAccess: "always",
			wmode:"transparent",
			bgcolor: "#FFFFFF"
		};

		var attributes = {id:"FaustCplus"};
		swfobject.embedSWF("/static/plugin/faustcplus/FaustCplus.swf", "altContent", "650", "460", "9.0.0", "/static/plugin/faustcplus/expressInstall.swf", flashvars, params, attributes);
		$(function(){
			$("body").mousedown(function(e){
				var x = e.pageX,y = e.pageY;
				if(((x>44 && x <160) || (x>180 && x <299)) && y>23 && y<50){
					$(".zdbtn").hide();
				}
			});
		});
		</script>
	</body>
</html>

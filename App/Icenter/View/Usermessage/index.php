<include file="Common:header"/> 
	<!--头部结束-->
	<!-- 医院公告 -->
	<header></header>
	<div id="content">
	<include file="Common:left"/>
	    <div id="right">
			<div class="title"><h3>修改信息</h3></div>
			<form class="layui-form">
	        <div class="loginmain" style="margin-top: 50px;">
				<div class="login_div" >
					<label>姓名</label>
					<div class="login_user">
						<input type="text" name="username" class="user"  value="{$userinfo['username']}" placeholder="请输入姓名">
					</div>
				</div>
				<div class="login_div" >
					<label>身份证号</label>
					<div class="login_user">
						<input type="text" name="card" disabled value="{$userinfo['card']}" lay-verify="required" placeholder="请输入身份证号"  class="input-val" />
					</div>
				</div>
				<div class="login_div" >
					<label>手机号码</label>
					<div class="login_user">
						<input type="text" name="mobile" value="{$userinfo['mobile']}" placeholder="请输入手机号码" lay-verify="required|phone"  class="input-val" />
					</div>
				</div>
				<div class="login_div" >
					<label>邮箱</label>
					<div class="login_user">
						<input type="text"  name="email" value="{$userinfo['email']}" placeholder="请输入邮箱" lay-verify="required|email"  class="input-val" />
					</div>
				</div>
				<div class="login_div login_divone" >
					<label></label>
					<div class="login_user">
						<a href="javascript:;" lay-submit lay-filter="reset" class="login">确 定</a>
					</div>
				</div>
			</div>
			</form>
	    </div>
	</div>
	<include file="Common:footer"/>
</body>

<script type="text/javascript" src="/static/index/js/BreakingNews.js"></script>
<script type="text/javascript">
$(function(){
	$('#breakingnews2').BreakingNews({
		title: '通知公告',
		titlebgcolor: '#e3e3e3',
		linkhovercolor: '#099',
		border: 'none',
		timer: 4000,
		effect: 'slide'	
	});
});
</script>
<script>
layui.use(['layer','form'],function(){
	var l=layui.layer,f=layui.form;
	f.on('submit(reset)',function(data){
		$.post('/icenter/usermessage/reset',data.field,function(_res){
			l.msg(_res.info,{icon:_res.status,time:2000},function(){
				location.reload();
			},'JSON');
		});
	});
});
// if (!!window.ActiveXObject || "ActiveXObject" in window) {
// 		var r = confirm("为了获得更好的用户体验，请使用Firefox或Chrome浏览器");
// 		if(r == true){
			
// 		}
		
// 	}
</script>

</html>
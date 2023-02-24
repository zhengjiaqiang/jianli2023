<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
<title>{$webtitle}后台列表</title>
	<include file="Common:indextop" />
</head>
<body  class="diy-css {$theme}">
	<div class="diy-navbar">
		<div class="navbar navbar-default">
			<div class="navbar-container" id="navbar-container">
					<div class="navbar-header pull-left">
						<a class="navbar-brand">
							<small>
								<lable style=" font-family:微软雅黑;font-size: 20px; line-height:32px;">{$webtitle}</lable>
							</small>
							
						</a><!-- /.brand -->
					</div><!-- /.navbar-header -->

					<div class="navbar-header pull-right" role="navigation">
						<ul class="nav ace-nav" id="navbar-info">

							<li>
								<a data-toggle="dropdown" data-hover="dropdown" href="#" class="dropdown-toggle">
								
									<span class="user-info">
										<small>欢迎登录</small>
										{$user['nickname']}
									</span>

									<i class="icon-caret-down"></i>
								</a>

								<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
									<li>
										<a href="__MODULE__/Personal/index" target="mainFrame">
											<i class="icon-user"></i>
											个人资料
										</a>
									</li>	
									<li>
										<a href="__MODULE__/Personal/Pwd" target="mainFrame">
											<i class="icon-cog"></i>
											密码设置
										</a>
									</li>

									<li class="divider"></li>

									<li>
										<a href="/ResumeLogin/index/out">
											<i class="icon-off"></i>
											退出
										</a>
									</li>
								</ul>
							</li>
						</ul><!-- /.ace-nav -->
					</div><!-- /.navbar-header -->
				</div>
		</div>
	</div>
	<div class="cnt-bar">
		<div class="page-header pull-left">
			<div class="page-title" id="id-loc-name">管理首页</div>
		</div>
		
		<div class="breadcrumbs">
			<ul class="breadcrumb" id="id-loc-title">
				<li id="id-loc-index">
					<i class="icon-home home-icon"></i>
					<a href="__ROOT__/Systemv/index/main" target="mainFrame">管理中心</a>
				</li>
			</ul><!-- .breadcrumb -->
		</div>
	</div>
	<div class="sb-bar">
		<div class="f-container">
			<a  id="auto-expand" class="hidden-dxs" href="javascript:void(0);" ><i class="icon-reorder"></i></a>
			<div class="theme-select">
				<div>
					<div class="col-md-3"><a style="background-color:#2a3b4c;" data-color="default-skin1"><i class="icon-ok"></i></a></div>
					<div class="col-md-3"><a style="background-color:#1abc9c;" data-color="default-skin"><i></i></a></div>
					<div class="col-md-3"><a style="background-color:#438EB9;" data-color="default-skin2"><i></i></a></div>
					<div class="col-md-3"><a style="background-color:#155abb;" data-color="default-skin3"><i></i></a></div>
				</div>
			</div>
		</div>
	</div>
	<div class="diy-sidebar" id="sidebar">
		<div class="f-container">
			<div class="sidebar">

				<ul class="nav nav-list">
					<li class="active">
						<a href="__ROOT__/Systemv/index/main" target="mainFrame">
							<i class=""></i>
							<span class="menu-text"> 管理首页 </span>
						</a>
					</li>
					<li class="open">
					{$navhtml}
					
					<!-- <li>
						<a href="javascript:void(0);" class="dropdown-toggle">
						<i></i><span class="menu-text"> 栏目配置 </span>
						<b class="arrow icon-angle-down"></b></a>
						<ul class="submenu">
							<li>
								<a href="/Systemv/menu/index" target="mainFrame"><i class="icon-double-angle-right"></i><span class="menu-text"> 栏目列表 </span></a>
							</li>
						</ul>
					</li>

					<li>
						<a href="javascript:void(0);" class="dropdown-toggle">
						<i></i><span class="menu-text"> 分配权限 </span>
						<b class="arrow icon-angle-down"></b></a>
						<ul class="submenu">
							<li>
								<a href="/Systemv/Adminrole/index" target="mainFrame"><i class="icon-double-angle-right"></i><span class="menu-text"> 分配权限 </span></a>
							</li>
						</ul>
					</li>

					<li>
						<a href="javascript:void(0);" class="dropdown-toggle">
						<i></i><span class="menu-text"> 管理员管理 </span>
						<b class="arrow icon-angle-down"></b></a>
						<ul class="submenu">
							<li>
								<a href="/Systemv/admin/index" target="mainFrame"><i class="icon-double-angle-right"></i><span class="menu-text"> 管理员列表 </span></a>
							</li>
						</ul>
					</li>			 -->
				</ul><!-- /.nav-list -->

			</div>
		</div>
	</div>
	<div class="diy-frame">
		<div class="f-container">
			<iframe name="mainFrame" allowtransparency="true" id="mainFrame" src="/Systemv/index/main" style="height: 100%; width: 100%;" frameborder="0" scrolling="auto"></iframe>
		</div>
	</div>

	<include file="Common:indexfooter" />
<script type="text/javascript">
window.onbeforeunload = function(e) {
    e = e || window.event;
    var msg = "您确定要离开此页面吗？";
    e.cancelBubble = true;
    e.returnValue = msg;
    if(e.stopPropagation) {
        e.stopPropagation();
        e.preventDefault();
    }
    return msg;
};
</script>
</body>
</html>
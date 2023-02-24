<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>列表</title>

	<include file="Common:listtop" />
</head>
<body class="diy-css list-fixed" id="layer-open-parent">
	<div class="list-siderbar">
		<div class="opr-btns">
			
			<div class="btn-group">
				
				<div class="btn-group role-obj" role-obj="#id-btn-select-fu1 li" id="id-btn-select-fu1" >
				<input type="hidden"  role-quit="true" data-bind="status"/>
				<a class="btn dropdown-toggle  btn-success"  data-toggle="dropdown" data-click="dropdown">
				全部	<i class="icon-angle-down icon-on-right"></i>
				</a>
				<ul class="dropdown-menu" style="max-height:350px;overflow-y:auto;">
					<li data-value="0"><a>全部</a></li>
					<li data-value="1"><a>通过</a></li>
					<li data-value="2"><a>未通过</a></li>
				</ul>
			</div>
			</div>
		</div>
	</div>
	<form id="search-form" class="search-form" role="searchform" method="get" action="__CONTROLLER__/index">
		<input type="hidden" name="status" value="{$status}"/>
	</form>
	<form class="table-responsive">
		<input type="hidden" name="event"/>
		<table id="main-table-frist" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="left">专家账号</th>
					<th class="left">专家姓名</th>
					<th class="left" align="center">是否通过</th>
					<th class="left">备注</th>
                    <th class="left">提交时间</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</form>
	<include file="Common:listfooter" />
	<script type="text/javascript">
		jQuery(function($) {
            var pid = {$id},del={$delete},up={$update},_pid={$pid};
			MCMS.setTitle([{'title':'<?php echo $title; ?>管理'}]);
			var onePageRow = {$onePageRow},sEcho=1,controller = '__CONTROLLER__';
			MCMS.btnEvent(controller+'/operate');
			MCMS.table.Init(onePageRow,controller+'/situation?pid='+_pid,[
					__i("number",null,false),
					__i("nickname",0,false),
					__i("status",0,false),
					__i("remark",null,false),
					__i("addtime",null,false),
                ],[{   "aTargets": [4],
						"mRender": function (data, type, full) {
                           return MCMS.table.pattern(data,'yyyy-MM-dd HH:mm:ss');
                          },
					},{
						"aTargets": [2],
						"mRender": function (data, type, full) { 
							return '<div class="status-icon red "><i class="icon icon-'+(data=='1' ? 'ok': 'remove')+' bigger-130 "></i></div>';
						}	
					}
				]);
		})
	</script>
</body>
</html>

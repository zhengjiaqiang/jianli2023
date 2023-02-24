<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>{$ptitle}</title>

	<include file="Common:listtop" />
</head>
<body class="diy-css list-fixed">

	<div class="list-siderbar">
		<div class="opr-btns">
			<a class="btn btn-primary" href="__CONTROLLER__/edit"><i class="icon icon-plus"></i> 添加</a>
		</div>
	</div>
	<form class="table-responsive">
		<input type="hidden" name="event"/>
		<table id="main-table-frist" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th width="50">ID</th>
					<th class="left">名称</th>
					<th >操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</form>
	<include file="Common:listfooter" />
	<script type="text/javascript">
		jQuery(function($) {
			MCMS.setTitle([{'title':'<?php echo $ptitle ?>'}]);
			var onePageRow = 15,sEcho=1,controller = '__CONTROLLER__';
			MCMS.btnEvent(controller+'/operate');
			MCMS.table.Init(onePageRow,controller+'/index',[
					__i("id",0,false),
					__i("name",null,false),
					__i("id",null,false)
                ],[{
						"aTargets": [2],
						"mRender": function (data, type, full) {
							if( data !=1) return MCMS.table.oprHtml(controller,data);
							if(data == 1) return '<span style="color:red;">超级管理员初始化权限</span>';
							
						}
					}
				 ]);
		})
	</script>
</body>
</html>

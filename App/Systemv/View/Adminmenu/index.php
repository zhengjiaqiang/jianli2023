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
			<button class="btn btn-primary no-select" data-loading-text="执行中..."  role="event-btn" data-e="order"><i class="icon  icon-cog"></i> 排序</button>
		</div>
	</div>
	<form class="table-responsive">
		<input type="hidden" name="event"/>
		<table id="main-table-frist" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="left">菜单名称(排序)</th>
					<th>状态</th>
					<th>页面类型</th>
					<!--<th >权限</th>
					<th>图标</th>-->
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
			var onePageRow = {$onePageRow},sEcho=1,controller = '__CONTROLLER__';
			MCMS.btnEvent(controller+'/operate');
			////id:绑定数据的列名，cl:class名称 sort:是否排序
			MCMS.table.Init(onePageRow,controller+'/index',[
					__i("name",'order-input',false),
					__i("status",0,false),
					__i("indextype",null,false),
					__i("id",null,false)
                ],[{
						"aTargets": [1],
						"mRender": function (data, type, full) { return full.status == -2 ? '' : MCMS.table.status(data);}
					},{
						"aTargets": [2],
						"mRender": function (data, type, full) { 
							var itype=['栏目','单页','文字列表','图文列表','院报类型']
							return itype[full.indextype];
						}
					},{
						"aTargets": [3],
						"mRender": function (data, type, full) { 
							return  full.isset ==0 &&full.status !=-2&& full.id > 135  ? '<div class="action-buttons"><a class="green" title="编辑" href="/Urm/Adminmenu/edit?id='+full.id+'"><i class="icon-pencil bigger-130"></i>  编辑</a></div>' : (full.status==-2||full.id < 136 ? '': MCMS.table.oprHtml(controller,data));
						}
					}
				 ],{'bInfo':false,'bAutoWidth':true});
		})
	</script>
</body>
</html>

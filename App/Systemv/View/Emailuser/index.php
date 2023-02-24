<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>{$webtitle}后台列表</title>

	<include file="Common:listtop" />
</head>
<body class="diy-css list-fixed" id="layer-open-parent">
	<div class="list-siderbar">
		<div class="opr-btns">
			<if condition="$add">
				<a class="btn btn-primary layer-open-cool" data-w="500px" data-h="500px" title="添加"  shref="__CONTROLLER__/edit/id/{$id}"><i class="icon icon-plus"></i> 添加</a>
			</if>
			<if condition="$delete"> 
				<button type="button" class="btn btn-primary" data-stitle="确定删除当前邮箱？" role-e="delete" data-loading-text="删除中..."><i class="icon icon-remove"></i> 删除已选项</button>
			</if>
            
			<if condition="$pass OR $top">
			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" data-loading-text="执行中...">
					操作选中项
					<i class="icon-angle-down icon-on-right"></i>
				</button>
				<ul class="dropdown-menu" role="event-list"  style="max-height:350px;overflow-y:auto;">
					<if condition="$pass">
						<li class="optgroup">设置审核状态为：</li>
						<li><a data-e="okpass">已审核</a></li>
						<li><a data-e="nopass">未审核</a></li>
					</if>	
				</ul>
			</div>
			</if>
				<div class="pull-right">
					<div class="box-search pull-left">
						<div class="btn-group" id="id-btn-select-fu">
							<input type="hidden"  role-quit="true" data-bind="stype"/>
							<a class="btn dropdown-toggle"  data-toggle="dropdown" data-click="dropdown">
								关键字
								<i class="icon-angle-down icon-on-right"></i>
							</a>
							<ul class="dropdown-menu" style="max-height:350px;overflow-y:auto;">
								<li><a data-id="0">关键字</a></li>
							</ul>
						</div><!-- /btn-group -->
						<input type="text" role-keydown="search-quit-text" value="{$key}" role-quit="true" data-bind="key" placeholder="输入关键字按回车键" class="nav-search-input" autocomplete="off">
						<a class="icon-search" role-click="search-quit-button"></a>
					</div>
					<!-- <a class="btn btn-primary up" id="highsearchbtn"><i class="icon icon-angle-down"></i> 高级搜索</a> -->
				</div>
		</div>
	</div>
	<form id="search-form" class="search-form" role="searchform" method="get" action="__CONTROLLER__/index">
		<div class="oprsbtn highsearch" id="highsearch">
			
			<div class="oprsbtn highsearch" id="highsearch" style="display:none;">
				<div class="box-search">
					<input type="text" name="key" placeholder="输入关键字" class="nav-search-input" autocomplete="off">
				</div>
			</div>
		</div>
	</form>
	<form class="table-responsive">
		<input type="hidden" name="event"/>
		<table id="main-table-frist" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th width="42">
						<label><input type="checkbox" class="ace" /><span class="lbl"></span></label>
					</th>
					<th width="80" desc='1' asc='2' >编号</th>
					<th class="left">姓名</th>
					<th width="120" class="fc">科室</th>
					<th width="60">邮箱</th>
					<th width="120">状态</th>
					<th width="120">操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</form>
	<include file="Common:listfooter" />
	<script type="text/javascript">
		jQuery(function($) {
            var pid = {$id},del={$delete},up={$update};
			MCMS.setTitle([{'title':'<?php echo $title; ?>管理'}]);
			var onePageRow = {$onePageRow},sEcho=1,controller = '__CONTROLLER__';
			MCMS.btnEvent(controller+'/operate');
			MCMS.table.Init(onePageRow,controller+'/index?id='+pid,[
					__i("id",0,false),
					__i("id",0,true),
					__i("name",null,false),
					__i("department",null,false),
					__i("email",null,false),
					__i("status",0,false),
					__i("id",0,false)
                ],[
					{   "aTargets": [0],
						"mRender": function (data, type, full) {return MCMS.table.checkbox(data);},
					},{
						"aTargets": [5],
						"mRender": function (data, type, full) { return MCMS.table.status(data);}	
					},{
						"aTargets": [6],
						"mRender": function (data, type, full) { 
							var _h='';
							if(up){
								_h+='<div class="action-buttons"> <a class="green layer-open-cool"  data-w="550px" data-h="500px" title="编辑" shref="'+controller+'/edit?pid='+full.id+'&id='+pid+'"><i class="icon-pencil bigger-130"></i>  编辑</a>';
							}
							if(del){
								_h+='<a class="red " title="删除" href="javascript:void(0);" data-id="'+full.id+'" data-stitle="确定删除当前邮件？" role="delete"  data-loading-text="删除中..."><i class="icon-trash bigger-130"></i>  删除</a></div>';
							}
                           return _h;
                        }
					}
				]);
		})
	</script>
</body>
</html>

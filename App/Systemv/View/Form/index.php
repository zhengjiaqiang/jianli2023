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
			
			<a class="btn btn-primary layer-open-cool" data-w="85%" data-h="400px" title="添加"  shref="__CONTROLLER__/edit/id/{$id}"><i class="icon icon-plus"></i> 添加</a>
			
            <button type="button" class="btn btn-primary"  role-e="order" data-loading-text="排序中..."><i class="icon icon-sort"></i> 排序</button>
		
			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" data-loading-text="执行中...">
					操作选中项
					<i class="icon-angle-down icon-on-right"></i>
				</button>
				<ul class="dropdown-menu" role="event-list"  style="max-height:350px;overflow-y:auto;">
					<li class="optgroup">设置审核状态为：</li>
					<li><a data-e="okpass">已审核</a></li>
					<li><a data-e="nopass">未审核</a></li>
				</ul>
			</div>
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
					<th width="80" desc='1' asc='2'>编号</th>
					<th>配置名称</th>
					<th>添加时间</th>
					<th>配置状态</th>
					<th>配置排序</th>
					<th width="240">操作</th>
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
					__i("name",null,'center'),
					__i("addtime",null,false),
					__i("status",1,false),
					__i("sort",1,false),
					__i("id",0,false)
                ],[
					{   "aTargets": [0],
						"mRender": function (data, type, full) {return MCMS.table.checkbox(data);},
					},{
						"aTargets": [3],
						"mRender": function (data, type, full) { return MCMS.table.pattern(data,'yyyy-MM-dd HH:mm:ss');}
					},{
						"aTargets": [4],
						"mRender": function (data, type, full) { return MCMS.table.status(data);}	
					},{
						"aTargets": [5],
						"mRender": function (data, type, full) { return '<input type="text" name="sort['+full.id+']" value="'+full.sort+'" style="width:70px;" class="input"/>';}	
					},{
						"aTargets": [6],
						"mRender": function (data, type, full) { 
							var _h='<div class="action-buttons">';
							_h+=' <a class="green layer-open-cool"  data-w="85%" data-h="400px" title="编辑配置" shref="'+controller+'/edit?pid='+full.id+'&id='+pid+'"><i class="icon-edit bigger-130"></i>  编辑</a>';
							_h+=' <a class="green"  title="编辑配置"  target="_blank" href="'+controller+'/set?pid='+full.id+'&id='+pid+'"><i class="icon-edit bigger-130"></i>  简历配置</a>';
							_h+='<a class="red " title="删除" href="javascript:void(0);" data-id="'+full.id+'" role="delete"  data-loading-text="删除中..." data-stitle="确定删除当前的简历配置?"><i class="icon-trash bigger-130"></i>  删除</a></div>';
                          return _h;
                        }
					}
				]);
		})
	</script>
</body>
</html>

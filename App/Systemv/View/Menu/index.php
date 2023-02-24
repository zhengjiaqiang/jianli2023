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
				<a class="btn btn-primary" href="__CONTROLLER__/edit/id/{$id}" ><i class="icon icon-plus"></i> 添加</a>
			</if>
			<if condition="$sort">
			<button type="button" class="btn btn-primary" role-e="order" data-loading-text="排序中..."><i class="icon icon-sort"></i> 排序</button>
			</if>
			<if condition="$delete">
				<button type="button" class="btn btn-primary" role-e="delete"  data-stitle="确定删除当前选中的栏目？"data-loading-text="删除中..."><i class="icon icon-remove"></i> 删除已选项</button>
			</if>
			

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
						<li class="optgroup">设置置顶状态为：</li>
						<li><a data-e="oktop">置顶</a></li>
						<li><a data-e="notop">取消置顶</a></li>
					</if>
				</ul>
				
			</div><!-- /btn-group -->
				<div class="pull-right">
					<div class="box-search pull-left">
						<div class="btn-group" id="id-btn-select-fu">
							<a class="btn dropdown-toggle"  data-toggle="dropdown" data-click="dropdown">
								名称
								<i class="icon-angle-down icon-on-right"></i>
							</a>
							<ul class="dropdown-menu" style="max-height:350px;overflow-y:auto;">
								<li><a data-id="0">名称</a></li>
							</ul>
						</div><!-- /btn-group -->
						<input type="text" role-keydown="search-quit-text" value="" role-quit="true" data-bind="key" placeholder="输入关键字按回车键" class="nav-search-input" autocomplete="off">
						<a class="icon-search" role-click="search-quit-button"></a>
					</div>
					</a>
				</div>
		</div>
	</div>
	<form id="search-form" class="search-form" role="searchform" method="get" action="__CONTROLLER__/index">
		<input type="text" name="key" placeholder="输入关键字" class="nav-search-input" autocomplete="off">
		<input name="depid" type="hidden" value="" />
	</form>
	<form class="table-responsive">
		<input type="hidden" name="event"/>
		<table id="main-table-frist" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th width="42">
						<label><input type="checkbox" class="ace" /><span class="lbl"></span></label>
					</th>
					<th width="80">ID</th>
					<th class="left">栏目名称</th>
					<th class="left" wdith="120">状态</th>
					<th width="120" class="fc">是否删除</th>
					<th width="120" class="fc">排序</th>
					
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
			MCMS.setTitle([{'title':'<?php echo $title; ?>'}]);
			var onePageRow = {$onePageRow},sEcho=1,controller = '__CONTROLLER__';
			MCMS.btnEvent(controller+'/operate');
			MCMS.table.Init(onePageRow,controller+'/index',[
					__i("id",0,false),
					__i("id",0,false),
					__i("name",null,false),
					__i("status",'',false),
					__i("delete",1,false),
					__i("sort",1,false),
					__i("id",0,false)
                ],[
					{   "aTargets": [0],
						"mRender": function (data, type, full) {
							if(full.ifdel == 0 )return MCMS.table.checkbox(data);
							else return '<span style="color:red;">--</span>';},
					},{ "aTargets": [5],
						"mRender": function (data, type, full) {
                            return '<input name="listorders['+full.id+']" type="text" size="4" value="'+full.sort+'" class="input"> ';
                          },
                    },{
						"aTargets": [3,4],
						"mRender": function (data, type, full) { return MCMS.table.status(data);}	
					},{
						"aTargets": [6],
						"mRender": function (data, type, full) { 
							var _h='';
							if(full.ifdel == 0 && up){
							_h='<div class="action-buttons"> <a class="green layer-open-cool"  data-w="550px" data-h="680px" title="编辑" shref="'+controller+'/edit?pid='+full.id+'&id='+pid+'"><i class="icon-pencil bigger-130"></i>  编辑</a>';}
							if(full.ifdel == 0 && del){
                          		 _h+='<a class="red " title="删除" data-stitle="确定删除当前栏目？" href="javascript:void(0);" data-id="'+full.id+'" role="delete"  data-loading-text="删除中..."><i class="icon-trash bigger-130"></i>  删除</a></div>';
							}
                            return _h;
                        }
					}
				],{'bInfo':false,'bAutoWidth':false});
		})
	</script>
</body>
</html>

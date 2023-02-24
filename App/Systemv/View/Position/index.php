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
			<if condition="$add">
				<a class="btn btn-primary layer-open-cool" data-w="85%" data-h="600px" title="添加"  shref="__CONTROLLER__/edit/id/{$id}"><i class="icon icon-plus"></i> 添加</a>
			</if>
            <button type="button" class="btn btn-primary"  role-e="order" data-loading-text="排序中..."><i class="icon icon-sort"></i> 排序</button>
			
			<if condition="$delete">
				<button type="button" class="btn btn-primary" role-e="delete" data-loading-text="删除中..."><i class="icon icon-remove"></i> 删除已选项</button>
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
					<if condition="$top">
						<li class="optgroup">设置置顶状态为：</li>
						<li><a data-e="oktop">置顶</a></li>
						<li><a data-e="notop">取消置顶</a></li>
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
					<th >大类</th>
					<th >职位名称</th>
					<th >发布日期</th>
					<th>招聘人数</th>
					<th>开始时间</th>
					<th>结束时间</th>
					<th>排序</th>
					<th width="60" class="fc">状态</th>
					<th width="300">操作</th>
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
					__i("bigname",0,true),
					__i("name",null,'center'),
					__i("usetime",null,false),
					__i("number",null,false),
					__i("btime",null,false),
					__i("etime",null,false),
					__i("sort",null,false),
					__i("status",1,false),
					__i("id",0,false)
                ],[
					{   "aTargets": [0],
						"mRender": function (data, type, full) {return MCMS.table.checkbox(data);},
					},{
						"aTargets": [4,6,7],
						"mRender": function (data, type, full) { return MCMS.table.pattern(data,'yyyy-MM-dd');}
					},{ "aTargets": [8],
						"mRender": function (data, type, full) {
                            return '<input type="text" name="sort['+full.id+']" value="'+full.sort+'" style="width:70px;" class="input"/>';
                          },
                    },{
						"aTargets": [9],
						"mRender": function (data, type, full) { return MCMS.table.status(data);}	
					},{
						"aTargets": [10],
						"mRender": function (data, type, full) { 
							var _h='<div class="action-buttons">';
							if(up){
								_h+=' <a class="green layer-open-cool" data-w="85%" data-h="600px" title="编辑" shref="'+controller+'/edit?pid='+data+'&id='+pid+'"><i class="icon-pencil bigger-130"></i>  编辑</a>';

								_h+=' <a class="green"  role="event-btn" data-value="'+data+'" data-e="on"><i class="icon-off bigger-130"></i> '+(full.status==1 ? '关闭' : '开启')+'</a>';

								_h+='<a class="green" data-id="'+data+'" role="copy" title="复制项目"><i class="icon-exchange bigger-130"></i>  复制项目</a>';
							}
							if(del){
								_h+='<a class="red " title="删除" href="javascript:void(0);" data-id="'+data+'" data-stitle="确定删除当前职位？" role="delete"  data-loading-text="删除中..."><i class="icon-trash bigger-130"></i>  删除</a></div>';
							}
                          return _h;
                        }
					}
				]);
				$("#main-table-frist").on('click','[role="copy"]',function(){
					var _this = $(this),id=_this.data('id')
					$("[name='event']").val('copy');
					$parent().layer.confirm('确定复制当前的配置？',function(){
						var _index=$parent().layer.msg('当前正在复制当前项目，以及项目配置(请勿关闭浏览器).....',{icon:16,time:false});
						$.post('/systemv/position/operate',{'chkItem':id,'event':'copy'},function(_data){
							$parent().layer.close(_index);
							if(_data.status){
								window.loadTable.fnDraw(false);
							} 
							else layer.msg(_data.info,{icon:2,time:2000});
						},'JSON');
						$parent().layer.close(_index);
						return false;
					});
				});
		})
	</script>
</body>
</html>

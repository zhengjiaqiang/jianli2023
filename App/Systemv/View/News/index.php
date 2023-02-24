<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>列表</title>

	<include file="Common:listtop" />
</head>
<body class="diy-css list-fixed">
	<div class="list-siderbar">
		<div class="opr-btns">
			<if condition="$add">
				<a class="btn btn-primary" href="__CONTROLLER__/edit/id/{$id}"><i class="icon icon-plus"></i> 添加</a>
			</if>
            <if condition="$sort">
				<button type="button" class="btn btn-primary"  role-e="order" data-loading-text="排序中..."><i class="icon icon-sort"></i> 排序</button>
			</if>
			<if condition="$delete">
				<button type="button" class="btn btn-primary" data-stitle="确定删除当前选中新闻？" role-e="delete" data-loading-text="删除中..."><i class="icon icon-remove"></i> 删除已选项</button>
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
					<a class="btn btn-primary up" id="highsearchbtn"><i class="icon icon-angle-down"></i> 高级搜索</a>
				</div>
		</div>
	</div>
	<form id="search-form" class="search-form" role="searchform" method="get" action="__CONTROLLER__/index">
		<div class="oprsbtn highsearch" id="highsearch">
			<label>添加时间：</label><input id="start-date" type="text" name="start" class="input-sm " onfocus="var d5222=$dp.$('end-date');WdatePicker({dateFmt:'yyyy-MM-dd',onpicked:function(){d5222.focus();},maxDate:'#F{ $dp.$D(\'end-date\')}'})" value=""> - <input id="end-date" type="text" name="end" class="input-sm" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'#F{ $dp.$D(\'start-date\')}'})" value="">
			<div class="oprsbtn highsearch" id="highsearch" style="display:none;">
				<div class="box-search">
					<input type="text" name="key" placeholder="输入关键字" class="nav-search-input" autocomplete="off">
				</div>
			</div>
			<label class="one">状态：</label><select name="status" class="small m-wrap">
				<option value="">---状态---</option>
				<option value="1">已审核</option>
				<option value="0" >未审核</option>
			</select>
			<div class="boxbutton">
				<button type="submit" class="btn btn-info"><i class="icon icon-search"></i> 搜索</button>
				<button type="reset" class="btn " role="reset" data-form='#search-form'><i class="icon icon-undo"></i> 重置</button>
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
					<th class="left">标题</th>
					<th class="left" width="120">基本信息</th>
                    <th width="80"  class="fc" desc='7' asc='8'>阅读次数</th>
					<th width="60" class="fc">状态</th>
					<th width="60" class="fc" desc='3' asc='4'>置顶</th>
					<th width="60">排序</th>
					<th width="120">添加时间</th>
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
					__i("title",null,false),
					__i("id",'',false),
                    __i("readnums",0,true),
					__i("status",1,false),
					__i("istop",1,false),
					__i("sort",1,false),
					__i("ownertime",null,false),
					__i("id",0,false)
                ],[
					{   "aTargets": [0],
						"mRender": function (data, type, full) {return MCMS.table.checkbox(data);},
					},{   "aTargets": [3],
						"mRender": function (data, type, full) {
                           var _h= '来源：'+full.source+'<br/>';
                           _h+='作者：'+full.author+'<br/>';
                           return _h;
                          },
					},{ "aTargets": [7],
						"mRender": function (data, type, full) {
                            return '<input type="text" name="sort['+full.id+']" value="'+full.sort+'" style="width:70px;" class="input"/>';
                          },
                    },{
						"aTargets": [5,6],
						"mRender": function (data, type, full) { return MCMS.table.status(data);}	
					},{
						"aTargets": [8],
						"mRender": function (data, type, full) { return MCMS.table.pattern(data,'yyyy-MM-dd hh:mm:ss');}
					},{
						"aTargets": [9],
						"mRender": function (data, type, full) { 
							var _h='';
							if(up){
								_h='<div class="action-buttons"> <a class="green" title="编辑" href="'+controller+'/edit?id='+pid+'&pid='+full.id+'"><i class="icon-pencil bigger-130"></i>  编辑</a>';
							}
							if(del){
								_h+='<a class="red " title="删除" data-stitle="确定删除当前新闻？" href="javascript:void(0);" data-id="'+full.id+'" role="delete"  data-loading-text="删除中..."><i class="icon-trash bigger-130"></i>  删除</a></div>';
							}
                          return _h;
                        }
					}
				]);
		})
	</script>
</body>
</html>

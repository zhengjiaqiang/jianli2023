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
            <if condition="$sort">
				<button type="button" class="btn btn-primary"  role-e="order" data-loading-text="排序中..."><i class="icon icon-sort"></i> 排序</button>
			</if>
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
							<li data-value="0" data-id="0"><a>--全部--</a></li>
								<li data-value="1" data-id="1"><a>留言姓名</a></li>
								<li data-value="2" data-id="2"><a>留言邮箱</a></li>
								<li data-value="3" data-id="3"><a>留言手机</a></li>
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
			<div class="box-search">
				<div class="btn-group">
					<input name="stype" type="hidden" value="{$type}" />
					<a class="btn dropdown-toggle"  data-toggle="dropdown" data-click="dropdown">
						关键字
						<i class="icon-angle-down icon-on-right"></i>
					</a>
					<ul class="dropdown-menu">
								<li data-value="0" data-id="0"><a>--全部--</a></li>
								<li data-value="1" data-id="1"><a>留言姓名</a></li>
								<li data-value="2" data-id="2"><a>留言邮箱</a></li>
								<li data-value="3" data-id="3"><a>留言手机</a></li>
					</ul>
				
				</div><!-- /btn-group -->
				<input type="text" name="key" placeholder="输入关键字" value="{$key}" class="nav-search-input" autocomplete="off">
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
					<th class="left" width="240">基本信息</th>
					<th class="left" width="120">IP</th>
					<th width="60" class="fc">状态</th>
					<th width="120">添加时间</th>
					<th width="240">回复信息</th>
					<th width="120">首页显示</th>
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
			MCMS.table.Init(onePageRow,controller+'/index',[
					__i("id",0,false),
					__i("id",0,true),
					__i("id",0,true),
					__i("ip",null,false),
					__i("status",'',false),
					__i("addtime",null,false),
					__i("addtime",null,false),
					__i("isindex",null,false),
					__i("id",0,false)
                ],[
					{   "aTargets": [0],
						"mRender": function (data, type, full) {return MCMS.table.checkbox(data);},
					},{   "aTargets": [2],
						"mRender": function (data, type, full) {
                           var _h= '姓名：'+full.name+'<br/>';
                           _h+='邮箱：'+full.email+'<br/>';
						   _h+='电话：'+full.mobile+'<br/>';
                           return _h;
                          },
					},{   "aTargets": [6],
						"mRender": function (data, type, full) {
							var _h= '';
							if(full.status ==1){
								_h= '管理ID：'+full.backuid+'<br/>';
								_h+='回复时间：'+MCMS.table.pattern(full.backtime,'yyyy-MM-dd hh:mm:ss')+'<br/>';
								_h+='回复内容：'+full.backcontent+'<br/>';
							}else _h='<span style="color:red;">未回复</span>';
                            return _h;
                          },
					},{
						"aTargets": [4],
						"mRender": function (data, type, full) { return MCMS.table.status(data);}	
					},{
						"aTargets": [7],
						"mRender": function (data, type, full) { return MCMS.table.status(data);}	
					}
					,{
						"aTargets": [5],
						"mRender": function (data, type, full) { return MCMS.table.pattern(data,'yyyy-MM-dd hh:mm:ss');}
					},{
						"aTargets": [8],
						"mRender": function (data, type, full) { 
							var _h='';
							if(up){
								_h='<div class="action-buttons "> <a class="green layer-open-cool" title="回复" href="javascript:;" data-w="500px" data-h="600px" shref="/systemV/Message/edit/id/'+pid+'/pid/'+data+'" ><i class="icon-pencil bigger-130"></i>  回复</a>';
							}
							if(del){
								_h+='<a class="red " title="删除" href="javascript:void(0);" data-id="'+full.id+'" role="odelete"  data-loading-text="删除中..."><i class="icon-trash bigger-130"></i>  删除</a></div>';
							}
                          return _h;
                        }
					}
				]);
		})
	</script>
</body>
</html>

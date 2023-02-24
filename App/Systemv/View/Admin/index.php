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
			<if condition="$add">
				<a class="btn btn-primary" href="__CONTROLLER__/edit/id/{$id}"><i class="icon icon-plus"></i> 添加</a>
			</if>
			<if condition="$pass">
			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" data-loading-text="执行中...">
					操作选中项
					<i class="icon-angle-down icon-on-right"></i>
				</button>
				<ul class="dropdown-menu" role="event-list"  style="max-height:350px;overflow-y:auto;">
						<li class="optgroup">设置状态为：</li>
						<li><a data-e="okpass">启用</a></li>
						<li><a data-e="nopass">禁止</a></li>
				</ul>
			</div>
			</if><!-- /btn-group -->
				<div class="pull-right">
					<div class="box-search pull-left">
						<div class="btn-group" id="id-btn-select-fu">
							<input type="hidden"  role-quit="true" data-bind="stype"/>
							<a class="btn dropdown-toggle"  data-toggle="dropdown" data-click="dropdown">
								关键字
									<i class="icon-angle-down icon-on-right"></i>
							</a>
							<ul class="dropdown-menu" style="max-height:350px;overflow-y:auto;">
								<li data-value="0" data-id="0"><a>关键字</a></li>
								<li data-value="1" data-id="1"><a>姓名</a></li>
								<li data-value="2" data-id="2"><a>ID</a></li>
							</ul>
						</div><!-- /btn-group -->
						<input type="text" role-keydown="search-quit-text" value="{$key}"  role-quit="true" data-bind="key" placeholder="输入关键字按回车键" class="nav-search-input" autocomplete="off">
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
					<input name="stype" type="hidden" value="{$stype}" />
					<a class="btn dropdown-toggle"  data-toggle="dropdown" data-click="dropdown">
						关键字
						<i class="icon-angle-down icon-on-right"></i>
					</a>
					<ul class="dropdown-menu">
						<li data-value="0" data-id="0"><a>关键字</a></li>
						<li data-value="1" data-id="1"><a>姓名</a></li>
						<li data-value="2" data-id="2"><a>ID</a></li>
					</ul>
				</div><!-- /btn-group -->
				<input type="text" name="key" placeholder="输入关键字" value="{$key}" class="nav-search-input" autocomplete="off">
			</div>
			<label class="one">角色：</label><select name="role_id" class="small m-wrap">
				<option value="">---所有角色---</option>
				<?php
					foreach($rolelist as $v){
						if($v['id'] == $role_id) $selected ='selected';
						else $selected ='';
						echo '<option value="'.$v['id'].'" '.$selected.'>'.$v['name'].'</option>';
					}
				 ?>
			</select>
			<label class="one">状态：</label>
			<?php html::select('status',array(''=>'---状态---','1'=>'启用','0'=>'禁用'),$status,array('classs'=>'small m-wrap'));?>
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
					<th width="70">头像</th>
					<th class="left">账号信息</th>
					<th>状态</th>
					<th>登录/次数</th>
					<th>当前权限</th>
					<th width="180">操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</form>
	<include file="Common:listfooter" />
	<script type="text/javascript" src="/static/common/layer2.2/layer.js"></script>

	<script type="text/javascript">
		jQuery(function($) {
			var pid = {$id},del={$delete},up={$update};
			MCMS.setTitle([{'title':'<?php echo $title ?>管理'}]);
			var onePageRow = {$onePageRow},sEcho=1,controller = '__CONTROLLER__'
			,role={'del':'<?php echo $delete  ?>','up':'<?php echo $update  ?>'};
			MCMS.btnEvent(controller+'/operate');
			MCMS.table.Init(onePageRow,controller+'/index',[
					__i("id",0,false),
					__i("id",0,false),
					__i("id",null,false),
					__i("status",0,false),
					__i("addtime",null,false),
					__i("id",null,false),
					__i("id",null,false)
                ],[
					{   "aTargets": [0],
						"mRender": function (data, type, full) {return MCMS.table.checkbox(data);},
					},{   "aTargets": [1],
						"mRender": function (data, type, full) {
							return '<div class="list-photo"><img src="/upload/avatar/101/8fb98ab9159f51fd0.jpg" alt="'+full.nikename+'"></div>';
						}
					},{   "aTargets": [2],
						"mRender": function (data, type, full) {
							var _h = '';
							if(full.name){_h +='账号：'+full.name;}
							if(full.nickname){_h +='<br />姓名：'+full.nickname;}
							return _h;
						}
					},{   "aTargets": [3],
						"mRender": function (data, type, full) {
							var arr ={'1':{'msg':'启用','ctype':'success'},'0':{'msg':'禁用','ctype':'danger'}}
							return MCMS.table.lables({'msg':arr[data].msg,'ctype':arr[data].ctype,'class':'arrowed-right'});
						}
					},{
						"aTargets": [5],
						"mRender": function (data, type, full) {
							return	'网站权限：'+full.rname;
						}
					},{
						"aTargets": [4],
						"mRender": function (data, type, full) {
							return	'最后登录：'+MCMS.table.pattern(full.lasttime,'yyyy-MM-dd HH:mm')+
									'<br />最后登录IP：'+full.lastip+' <span class="text-danger">('+full.loginnum+')</span>';
						}
					},{
						"aTargets": [6],
						"mRender": function (data, type, full) {
							var _h='';
							if(up){
								_h += '<a class="green" title="编辑" href="'+controller+'/edit/id/'+pid+'/pid/'+data+'"><i class="icon-pencil bigger-130"></i>  编辑</a>';
								_h += '<a class="green" role="win" w="700px" h="550px" title="设置头像" href="'+controller+'/photo/id/'+pid+'/pid/'+data+'"><i class="icon-smile bigger-130"></i>  设置头像</a>';
								_h += '<a class="red" role="win" w="600px" h="350px" title="重置密码" href="'+controller+'/pwd/id/'+pid+'/pid/'+data+'"><i class="icon-key bigger-130"></i>  重置密码</a>';
							}
							if(del){
								_h += '<a class="red" title="删除" href="javascript:void(0);" data-id="'+data+'" role="odelete"  data-loading-text="删除中..."><i class="icon-trash bigger-130"></i>  删除</a>';
							}
							
							return '<div class="action-buttons" style="line-height:25px;">'+_h+'</div>'
						}
					}
				 ]);
			
			$("#main-table-frist").on('click','[role="win"]',function(){
				var $this = $(this);
				window.layerid = parent.layer.open({
					type: 2,
					title: $this.text(),
					closeBtn: 1, //不显示关闭按钮
					shade: 0.4,
					shadeClose: false,
					area: [$this.attr('w'), $this.attr('h')],
					content: $this.attr('href'),
					maxmin: false
				});
				return false;
			});

		})
	</script>
</body>
</html>

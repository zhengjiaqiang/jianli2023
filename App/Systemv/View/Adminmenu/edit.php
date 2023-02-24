<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{$ptitle}</title>
		<include file="Common:edittop" />
	</head>

	<body class="diy-css">
		<!--<div id="loading-notice" class="alert alert-danger fade in" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
			<strong>注意！！</strong> 图标css只需设置顶级菜单和最后一级菜单
		</div>-->
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="id" value="<?php echo get_array_val($info,'id') ?>" />
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 父级 </label>
				<div class="col-sm-9">
					<?php html::select('parent_id',$patree,get_array_val($info,'parent_id'),array('class'=>'col-xs-10 col-sm-5'),null,'--添加一级栏目--');?>
					<input type="hidden" name="opid" value="<?php echo get_array_val($info,'parent_id') ?>" />
				</div>
			</div>

			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 菜单名称 </label>
				<div class="col-sm-9">
					<?php html::text('name',get_array_val($info,'name'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'菜单名称')); ?>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 菜单地址 </label>
				<div class="col-sm-9">
					<?php html::text('role_path',get_array_val($info,'role_path'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'菜单地址','readonly'=>'readonly')); ?>
				</div>
			</div>

			
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 页面类型 </label>
				<div class="col-sm-9" id="typelist">
					<input type="radio" class="ace" value="1" name="indextype" <if condition="$info['indextype'] eq 1">checked="checked"</if>/> <span class="lbl">单页</span>
					<input type="radio" class="ace" value="2" name="indextype"  <if condition="$info['indextype'] eq 2">checked="checked"</if>/> <span class="lbl">文字列表</span>
					<input type="radio" class="ace" value="3" name="indextype"  <if condition="$info['indextype'] eq 3">checked="checked"</if>/> <span class="lbl">图片列表</span>
					<input type="radio" class="ace" value="4" name="indextype"  <if condition="$info['indextype'] eq 3">checked="checked"</if>/> <span class="lbl">院报</span>
				</div>
			</div>
			<div class="space-4" ></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 状态 </label>
				<div class="col-sm-9">
					<?php html::select('status',array('1'=>'显示','0'=>'不显示'),get_array_val($info,'status'),array('class'=>'col-xs-10 col-sm-5'));?>
					
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group hide">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> URL </label>
				<div class="col-sm-9">
					<?php html::text('url',get_array_val($info,'url'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'URL')); ?>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group hide">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-1"> 权限 </label>
				<div class="col-sm-9">
					<?php html::select('role_type',$roletype,get_array_val($info,'role_type'),array('class'=>'col-xs-10 col-sm-5'));?>
					<span class="help-inline  col-xs-12 col-sm-7"><span class="middle">  *类型选择“开发”，此处系统自动设置</span></span>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group hide">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 图标 </label>
				<div class="col-sm-9">
					<?php html::text('icon',get_array_val($info,'icon'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'图标')); ?>
					<span class="help-inline  col-xs-12 col-sm-7"><span class="middle">  *只需设置顶级图标和最后一级图标</span></span>
					<div class="help-block"><a href="/notice/icon.html" target="_blank">点击获取图标</a></div>
				</div>
			</div>
			
			<div class="clearfix form-actions">
				<div class="col-md-9">
					<button class="btn btn-info" type="submit" role="submit" data-loading-text="Loading..."><i class="icon-ok bigger-110"></i>提交 </button>
					&nbsp; &nbsp; &nbsp;
					<button class="btn" type="reset"><i class="icon-undo bigger-110"></i> 重置 </button>
				</div>
			</div>
		</form>
		<include file="Common:editfooter" />
		<script src="/static/common/pinyi.js"></script>
		<script>
			$(function(){
				var _val=<?php echo empty($info) ? -1 : $info['indextype'] ?>;
				MCMS.setTitle([{'title':'<?php echo $ptitle ?>管理','url':'__CONTROLLER__/index'},{'title':'<?php echo $ptitle;if(empty($id)) echo '添加'; else echo'编辑';  ?>'}]);
				MCMS.validate("#edit-form-area",{
					name  : { required: true}
				});
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this);
					return false;
				});
				
				var changeVal=function(_v=_val){
					if(_v !=-1 && _v!=0){
						$("#name").unbind();
						$("#typelist").parent().removeClass('hide');
						$.post('__URL__/getpath/',{'id':_v},function(_data){
								$("#role_path").val(_data.role_path);
						},'JSON');
					}else{
						$("#typelist").parent().addClass('hide');
						if($(":input[name='id']").val() ==0){
							$("#role_path").val(' ');
							$("#name").bind("keyup keydown change blur",function (){
								$("#role_path").val(pinyin.getFullChars($(this).val()));
							});
						}
					}
				}
				changeVal();
				$("#parent_id").on('change',function(){
					changeVal($(this).val());
				});
				
				setTimeout(function(){
					$('#loading-notice').slideUp('normal',function(){
						$(this).alert('close');
					});
				},4500);
			});
		</script>
	</body>
</html>

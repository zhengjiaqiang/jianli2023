<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>{$webtitle}后台列表</title>
		<include file="Common:edittop" />
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="pid" value="<?php echo get_array_val($info,'id') ?>" />
			<input type="hidden" name="id" value="{$id}" />
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 类型名称 </label>
				<div class="col-sm-9">
					<?php html::text('name',get_array_val($info,'name'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'类型名称')); ?>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2">Controller </label>
				<div class="col-sm-9">
					<?php html::text('controller',get_array_val($info,'controller'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'对应控制器')); ?>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2">Action </label>
				<div class="col-sm-9">
					<?php html::text('action',get_array_val($info,'action'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'对应Action')); ?>
				</div>
			</div>

			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2">View </label>
				<div class="col-sm-9">
					<?php html::text('view',get_array_val($info,'view'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'对应View')); ?>
				</div>
			</div>

			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 后台地址 </label>
				<div class="col-sm-9">
					
					<?php html::text('system',get_array_val($info,'system'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'后台地址')); ?>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 栏目类型 </label>
				<div class="col-sm-9">
				<?php html::select('mtype',['0'=>'--请选择栏目类型--',1=>'单页类型',2=>'多页类型'],get_array_val($info,'mtype'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'页面类型'),null,null,null,$noshowType); ?><span style="font-size:12px;color:red;">栏目类型无任何权限，单页权限仅有编辑和添加权限，多页则有所有权限。</span>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 状态 </label>
				<div class="col-sm-9">
						<label class="help-inline">
						<if condition="$pass">
							<?php html::checkbox('isshow',get_array_val($info,'isshow',0),array('value'=>'1','class'=>'ace')); ?><span class="lbl">审核</span>	</label>	
						</if>
				</div>
			</div>
			<div class="space-4"></div>

			<div class="clearfix form-actions">
				<div class="col-md-9">
				<if condition="($update AND $info['id'] gt 0) OR ($add AND !isset($info['id']))">
					<button class="btn btn-info" type="submit" role="submit" data-loading-text="Loading..."><i class="icon-ok bigger-110"></i>提交 </button>
				</if>
					&nbsp; &nbsp; &nbsp;
					<button class="btn" type="reset"><i class="icon-undo bigger-110"></i> 重置 </button>
				</div>
			</div>
		</form>
		<include file="Common:editfooter" />
		<script type="text/javascript" src="/ueditor/third-party/webuploader/webuploader.min.js"></script>
		<script type="text/javascript" src="/ckeditor/ckeditor.js?v=3"></script>
		<script>
			$(function(){
				var htype={$type};
				MCMS.setTitle([{'title':'<?php echo $title; ?>管理','url':'javascript:;'},{'title':'<?php echo $title; ?>添加'}]);
				MCMS.validate("#edit-form-area",{
					mtype:{required:true,minlength:1}
				});
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this);
					return false;
				});
				
			});
		</script>
	</body>
</html>

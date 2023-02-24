<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>后台列表</title>
		<include file="Common:edittop" />
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="pid" value="<?php echo get_array_val($info,'id') ?>" />
			<input type="hidden" name="id" value="{$id}" />
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 专家编号 </label>
				<div class="col-sm-9">
					<?php html::text('number',get_array_val($info,'number'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'专家编号')); ?>
				</div>
			</div> 


			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 专家姓名 </label>
				<div class="col-sm-9">
					<?php html::text('nickname',get_array_val($info,'nickname'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'专家姓名')); ?>
				</div>
			</div>


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
		<script>
			$(function(){
				MCMS.setTitle([{'title':'<?php echo $title; ?>管理','url':'javascript:;'},{'title':'<?php echo $title; ?>添加'}]);
				MCMS.validate("#edit-form-area",{
					number  : { required: true},
					nickname:{required:true}
				});
				$("#edit-form-area").submit(function(){
				
					MCMS.commajaxfun(this);
					return false;
				});
			});
		</script>
	</body>
</html>

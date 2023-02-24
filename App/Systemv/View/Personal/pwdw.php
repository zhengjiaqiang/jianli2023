<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{$ptitle}</title>
		<include file="Common:edittop" />
	</head>

	<body class="diy-css">
			<?php if($isone == '0'){
				echo '<div class="alert alert-warning no-margin center">第一次登陆或者系统重置密码时需要设置新密码</div>';
			} ?>
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>

			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 账号 </label>
				<div class="col-sm-9">
					<div class="help-block text-warning bigger-110 orange">  {$info.name} </div>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2">原密码 </label>
				<div class="col-sm-9">
					<?php html::password('oldpwd','',array('class'=>'col-xs-10 col-sm-5','placeholder'=>'原密码')); ?>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2">新密码 </label>
				<div class="col-sm-9">
					<?php html::password('newpwd','',array('class'=>'col-xs-10 col-sm-5','placeholder'=>'新密码')); ?>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2">重复新密码 </label>
				<div class="col-sm-9">
					<?php html::password('rnewpwd','',array('class'=>'col-xs-10 col-sm-5','placeholder'=>'重复新密码')); ?>
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
		<script>
			$(function(){
				MCMS.setTitle([{'title':'<?php echo $ptitle ?>','url':'__CONTROLLER__/index'},{'title':'修改密码'}]);
				MCMS.validate("#edit-form-area",{
					oldpwd:  { required: true,rangelength:[8,16],regexPassword:true}
					newpwd:  { required: true,rangelength:[8,16],regexPassword:true}
					rnewpwd: { required: true,rangelength:[8,16],regexPassword:true,equalTo: "[name=newpwd]"}
				});
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this);
					return false;
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

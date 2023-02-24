<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{$ptitle}</title>
		<include file="Common:edittop" />
		
	</head>

	<body class="diy-css layer-win">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="id" value="<?php echo get_array_val($info,'id') ?>" />
			<div class="form-group">
				<label class="col-xs-3 control-label no-padding-right" for="form-field-2"> 账号 </label>
				<div class="col-xs-9">
					<div class="help-block text-warning bigger-110 orange">  {$info.name} </div>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-xs-3 control-label no-padding-right" for="form-field-2">密码 </label>
				<div class="col-xs-9">
					<?php html::text('password',null,array('class'=>'col-xs-9 col-sm-5','placeholder'=>'密码')); ?>
				</div>
			</div>

			<div class="clearfix form-actions">
				<div class="col-md-9">
					<button class="btn btn-info" type="submit" role="submit" data-loading-text="Loading..."><i class="icon-ok bigger-110"></i>重置密码 </button>
				</div>
			</div>
		</form>
		<include file="Common:editfooter" />
		<script>
			$(function(){
				MCMS.setTitle([{'title':'<?php echo $ptitle ?>','url':'__CONTROLLER__/index'},{'title':'修改密码'}]);
				MCMS.validate("#edit-form-area",{
					password: { required: true,rangelength:[8,16],regexPassword:true}
				});
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this,function(obj){
						if(obj.status){
							MCMS.alert({'msg':obj.info,'class':'alert-success no-margin'});
							setTimeout(function(){
								parent.layer.close(parent.window.layerid);
							},1500);
						}else {
							MCMS.alert(obj.info);
						}
					});
					return false;
				});


			});
		</script>
	</body>
</html>

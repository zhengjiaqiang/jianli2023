<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{$ptitle}</title>
		<include file="Common:edittop" />
		<style>
			.checkbox-div{padding-top:4px}
			.help-inline{margin-right:10px;margin-left:5px;}
			.form-group:hover { background-color:#eee;}
			.form-group { margin-bottom: 4px; }
		</style>
	</head>

	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="id" value="<?php echo get_array_val($info,'id') ?>" />

			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 角色名称 </label>
				<div class="col-sm-9">
					<?php html::text('tname',get_array_val($info,'name'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'角色名称')); ?>
				</div>
			</div>
			<div class="h5 header" style="margin:0 12px;">选择权限</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 全部选择 </label>
				<div class="col-sm-9 checkbox-div">
					<label class="help-inline"><input type="checkbox"  value="0" class="ace role_all_cbk"><span class="lbl"></span>	</label>
				</div>
			</div>
			{$html}
			<div class="space-4"></div>
			<div class="clearfix form-actions">
				<div class="col-md-9">
					<button class="btn btn-info" type="submit" role="submit" data-loading-text="Loading..."><i class="icon-ok bigger-110"></i>提交 </button>
					&nbsp; &nbsp; &nbsp;
					<button class="btn"  type="reset"><i class="icon-undo bigger-110"></i> 重置 </button>
				</div>
			</div>
		</form>
		<include file="Common:editfooter" />
		<script>
			$(function(){
				MCMS.setTitle([{'title':'<?php echo $ptitle ?>管理','url':'__CONTROLLER__/index'},{'title':'<?php echo $ptitle;if(empty($id)) echo '添加'; else echo'编辑';  ?>'}]);
				MCMS.validate("#edit-form-area",{
					name  : { required: true}
				});
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this);
					return false;
				});

				$(".role_one_cbk").click(function(){
					$(this).closest('.form-group').find("[name='chk_ids[]']").prop('checked',this.checked);
				});
				$(".role_all_cbk").click(function(){
					$(this).closest('form').find("[name='chk_ids[]']").prop('checked',this.checked);
				});
			});
		</script>
	</body>
</html>

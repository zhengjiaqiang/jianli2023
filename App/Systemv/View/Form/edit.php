<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>{$webtitle}后台列表</title>
		<include file="Common:edittop" />
		
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__" data-type="{C('ISSTARTBIGTYPE')}">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="pid" value="<?php echo get_array_val($info,'id') ?>" />
			<input type="hidden" name="id" value="{$id}" />
			<legend>项目页配置</legend>
			<fieldset>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="name">配置名称</label>
					<div class="col-sm-4">
						<?php html::text('name',get_array_val($info,'name'),array('class'=>'form-control','placeholder'=>'配置名称')); ?>
					</div>
					<label class="col-sm-2 control-label" for="usetime">配置排序</label>
					<div class="col-sm-4">
						<?php html::text('sort',get_array_val($info,'sort',0),array('value'=>0,'class'=>'form-control','placeholder'=>'配置排序')); ?>
					</div>
				</div>
            </fieldset>   
			<legend>项目状态配置</legend>
			<fieldset>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="name">状态</label>
					<div class="col-sm-8">
							<label class="help-inline">
							<?php html::checkbox('status',get_array_val($info,'status',0),array('value'=>'1','class'=>'ace')); ?><span class="lbl">开启</span>	</label>	
					</div>
				</div>
            </fieldset> 

			<div class="space-4"></div>

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
				var config={};
				config={
					name  : { required: true}
				};
				MCMS.validate("#edit-form-area",config);
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this);
					return false;
				}); 
				
			});
		</script>
	</body>
</html>

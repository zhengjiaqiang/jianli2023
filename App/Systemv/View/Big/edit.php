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
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 大类名称 </label>
				<div class="col-sm-9">
					<?php html::text('name',get_array_val($info,'name'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'大类名称')); ?>
				</div>
			</div>
		
			
			
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-2 control-label" for="btime">开始时间</label>
				<div class="col-sm-4">
					<?php html::text('btime',date('Y-m-d',get_array_val($info,'btime',time())),array('class'=>'form-control date-simple','placeholder'=>'开始时间')); ?>
				</div>
				<label class="col-sm-2 control-label" for="etime">结束时间</label>
				<div class="col-sm-4">
					<?php html::text('etime',date('Y-m-d',get_array_val($info,'etime',time())),array('class'=>'form-control date-simple','placeholder'=>'结束时间')); ?>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 状态 </label>
				<div class="col-sm-9">
						<if condition="$pass">
							<label class="help-inline">
							<?php html::checkbox('status',get_array_val($info,'status',1),array('value'=>'1','class'=>'ace')); ?><span class="lbl">审核</span>	</label>	
						</if>
				</div>
			</div>

			<div class="clearfix form-actions">
				<div class="col-md-9">
				<if condition="($add) || ($update)">
					<button class="btn btn-info" type="submit" role="submit" data-loading-text="Loading..."><i class="icon-ok bigger-110"></i>提交 </button>
				</if>
					&nbsp; &nbsp; &nbsp;
					<button class="btn" type="reset"><i class="icon-undo bigger-110"></i> 重置 </button>
				</div>
			</div>
		</form>
		<include file="Common:editfooter" />
		<script type="text/javascript" src="/static/laydate/laydate.js"></script>
		<script type="text/javascript" src="/ckeditor/ckeditor.js?v=3"></script>
		<script>
			$(function(){
				lay('.date-simple').each(function(){
					laydate.render({
						elem: this
						,trigger: 'click'
					});
				}); 
				MCMS.setTitle([{'title':'科室管理'},{'title':'科室管理'}]);
				MCMS.validate("#edit-form-area",{
					name  : { required: true}
				});
				$("#edit-form-area").submit(function(){
					for(instance in CKEDITOR.instances) { 
						CKEDITOR.instances[instance].updateElement();  
					}
					MCMS.commajaxfun(this);
					return false;
				});
				
			});
		</script>
	</body>
</html>

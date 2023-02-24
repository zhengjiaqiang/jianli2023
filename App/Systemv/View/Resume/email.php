<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>后台列表</title>
		<include file="Common:edittop" />
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="/systemv/resume/emaillist">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="pid" value="<?php echo get_array_val($info,'id') ?>" />
			<input type="hidden" name="id" value="{$id}" />
			<div class="form-group">
				<label class="col-sm-2 control-label" for="bigtypeid">接收邮箱</label>
				<div class="col-sm-4">
					<select class="form-control selectpicker" data-live-search="true" id="uid" name="uid"  title="接收邮箱">
						<option value="-1">请选择接收邮箱</option>
						<foreach name="list" item="v">
							<option data-tokens="{$v['email']}({$v['department']}-{$v['name']})" value="{$v['id']}">{$v['email']}({$v['department']}-{$v['name']})</option>
						</foreach>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 访问密码 </label>
				<div class="col-sm-9">
					<?php html::text('passwd',get_array_val($info,'passwd'),array('class'=>'col-xs-12 col-sm-5','placeholder'=>'访问密码')); ?>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 结束时间</label>
				<div class="col-sm-9">
					<?php html::text('btime',!empty($info['btime']) ? date('Y-m-d',get_array_val($info,'btime')) : '',array('class'=>'form-control date-simple','placeholder'=>'结束时间')); ?>
				</div>
			</div>
			
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
		
		<script type="text/javascript" src="/static/laydate/laydate.js"></script>
		<script type="text/javascript" src="/static/common/select/bootstrap-select.min.js"></script>
		<script type="text/javascript" src="/ckeditor/ckeditor.js?v=3"></script>
		<script>
			$(function(){
				$('.selectpicker').selectpicker({});
				lay('.date-simple').each(function(){
					laydate.render({
						elem: this
						,trigger: 'click'
					});
				}); 
				MCMS.setTitle([{'title':'邮件管理'},{'title':'邮件管理'}]);
				MCMS.validate("#edit-form-area",{
					uid  : { required: true,min:0},
					passwd:{required:true},
					btime:{required:true}
				});
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this,function(_res){
						MCMS.alert(_res.info);
						parent.layer.close(parent.layer.getFrameIndex(window.name));
					});
					
					return false;
				});
				
			});
		</script>
	</body>
</html>

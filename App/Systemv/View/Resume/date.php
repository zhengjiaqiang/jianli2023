<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>后台列表</title>
		<include file="Common:edittop" />
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="/systemv/resume/datalist">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="id" value="{$id}" />
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 面试时间</label>
				<div class="col-sm-9">
					<?php html::text('btime',date('Y-m-d',get_array_val($info,'btime',time())),array('class'=>'col-xs-12 col-sm-5 date-simple','placeholder'=>'面试时间')); ?>
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
						parent.layer.msg(_res.info,{icon:_res.status,time:2000},function(){
							parent.layer.close(parent.layer.getFrameIndex(window.name));
						});
						// MCMS.alert(_res.info);
						// parent.layer.close(parent.layer.getFrameIndex(window.name));
					});
					
					return false;
				});
				
			});
		</script>
	</body>
</html>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>后台列表</title>
		<include file="Common:edittop" />
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__URL__/info">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="id" value="{$id}" />
			<div class="form-group">
				<div class="col-sm-12">
					<?php html::textarea('content',htmlspecialchars_decode(get_array_val($info,'content')),array('class'=>'col-xs-10 col-sm-10','placeholder'=>'详细内容')); ?>
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
		<script type="text/javascript" src="/ckeditor/ckeditor.js?v=3"></script>
		<script type="text/javascript" src="/ckeditor/config.js?v=3"></script>
		<script>
			$(function(){
				CKEDITOR.replace('content',{filebrowserImageUploadUrl:"/ueditor/php/controller.php?action=uploadimage&encode=utf-8"});
				MCMS.setTitle([{'title':'<?php echo $title; ?>管理'}]);
				MCMS.validate("#edit-form-area",{
					content  : { required: true}
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

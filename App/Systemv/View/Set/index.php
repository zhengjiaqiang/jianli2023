<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>后台列表</title>
		<include file="Common:edittop" />
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="/systemv/set/edit">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="pid" value="<?php echo get_array_val($info,'id') ?>" />
			<input type="hidden" name="id" value="{$id}" />
			<foreach name="mlist" item="v">
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> {$v['nickname']} </label>
				<div class="col-sm-9">
				<switch name="v['type']">
					 <case value="1">
						 <?php html::text($v['name'],get_array_val($v,'value'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>$v['nickname'])); ?>
					 </case>		 
					 <case value="2">
						 <?php html::checkbox($v['name'],get_array_val($v,'value',0),array('value'=>'1','class'=>'ace')); ?><span class="lbl">{$v['nickname']}</span>
					 </case>
				</switch>
				</div>
			</div>
			</foreach>
			
			
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
				MCMS.setTitle([{'title':'<?php echo $title; ?>管理','url':'javascript:;'},{'title':'<?php echo $title; ?>添加'}]);
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this);
					return false;
				});
			});
		</script>
	</body>
</html>

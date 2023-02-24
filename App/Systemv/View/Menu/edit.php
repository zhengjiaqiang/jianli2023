<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>{$webtitle}后台列表</title>
		<include file="Common:edittop" />
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="pid" value="<?php echo get_array_val($info,'id') ?>" />
			<input type="hidden" name="id" value="{$id}" />
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-xs-3 control-label no-padding-right">上级栏目</label>
				<div class="col-xs-9">
					<?php html::select('parent_id',$list,get_array_val($info,'pid'),array('class'=>'col-xs-9 col-sm-5'),array('0'=>'---作为一级栏目---'));?>
					<input type="hidden" name="opid" value="{$info['pid']}"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 栏目名称 </label>
				<div class="col-sm-9">
					<?php html::text('title',get_array_val($info,'name'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'标题')); ?>
				</div>
			</div>

			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 是否外链 </label>
				<div class="col-sm-9">
					<?php html::checkbox('islink',get_array_val($info,'islink',0),array('value'=>'1','class'=>'ace')); ?><span class="lbl">外链</span>
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 外链 </label>
				<div class="col-sm-9">
					<?php html::text('link',get_array_val($info,'link'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'链接')); ?>
				</div>
			</div>

			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 排序 </label>
				<div class="col-sm-9">
					<?php html::number('msort',get_array_val($info,'sort'),array('class'=>'col-xs-10 col-sm-5','value'=>0,'placeholder'=>'排序','min'=>0,'step'=>1)); ?>
				</div>
			</div>

			<if condition="!in_array($info['type'],$noshowType)">
				<div class="space-4"></div>
				<div class="form-group">	
					<label class="col-sm-3 control-label no-padding-right" for="form-field-2">类型 </label>
					<div class="col-sm-6">
						
							<?php html::select('type',$webtype,get_array_val($info,'type'),array('class'=>'col-xs-10 col-sm-5','placeholder'=>'类型'),0,'栏目类型',null,$noshowType); ?>
					</div>
				</div>
			<else/><input type="hidden" name="type" id="type" value="{$info['type']}"/>
			</if>

			
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 状态 </label>
				<div class="col-sm-6">
					<?php html::checkbox('status',get_array_val($info,'status',0),array('value'=>'1','class'=>'ace')); ?><span class="lbl">显示</span>	
				</div>
			</div>
			<!-- <div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2">首页选项卡</label>
				<div class="col-sm-6">
					<?php html::checkbox('iszhuanye',get_array_val($info,'iszhuanye',0),array('value'=>'1','class'=>'ace')); ?><span class="lbl">是</span>	
				</div>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
					<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 显示状态[首页] </label>
					<div class="col-sm-6">
					<?php html::checkbox('index',get_array_val($info,'index',0),array('value'=>'1','class'=>'ace')); ?><span class="lbl">不显示</span>
					</div>
			</div> -->
		
			<div class="space-4"></div>
			<div class="clearfix form-actions">
				<div class="col-md-9">
				<if condition="($update And $info['id'] gt 0) || ($add AND !isset($info['id']))">
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
					title  : { required: true},
					info : {required :true},
					ownertime:{required:true}
				});
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this);
					return false;
				});
			});
		</script>
	</body>
</html>

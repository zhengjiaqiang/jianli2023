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
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 用户姓名:{$info['name']} </label>
			</div>
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 用户邮件:{$info['email']} </label>
			</div>
			<div class="space-4"></div>


			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 用户电话:{$info['mobile']} </label>
			</div>

			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 用户内容 </label>
				<div class="col-sm-9">
					{$info['content']}
				</div>
			</div>
			<if condition="!empty($info['link'])">
				<div class="space-4"></div>
				<div class="form-group image-prve">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 用户图片 </label>
					<div class="col-sm-9"><a><img src="{$info['link']}" style="max-height:200px;max-width:200px;"></a>
				</div>
				</div>
			</if>
		
			
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 内容详情 </label>
				<div class="col-sm-9">
					<?php html::textarea('backcontent',htmlspecialchars_decode(get_array_val($info,'backcontent')),array('class'=>'col-xs-10 col-sm-10','placeholder'=>'详情介绍','data-type'=>2)); ?>
				</div>
			</div>

			
			<div class="space-4"></div>
			<div class="form-group">
				<label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 状态 </label>
				<div class="col-sm-9">
						<label class="help-inline">
						<if condition="$pass">
							<?php html::checkbox('status',get_array_val($info,'status',0),array('value'=>'1','class'=>'ace')); ?><span class="lbl">审核</span>	</label>	
						</if>
						<if condition="$top">
							<label class="help-inline"><?php html::checkbox('isindex',get_array_val($info,'isindex',0),array('value'=>'1','class'=>'ace')); ?><span class="lbl">置顶</span>	</label>
						</if>
				</div>
			</div>
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
				var htype={$type};
				CKEDITOR.replace('backcontent',{filebrowserImageUploadUrl:"/ueditor/php/controller.php?action=uploadimage&encode=utf-8",allowedContent:true});
				MCMS.setTitle([{'title':'<?php echo $title; ?>管理','url':'javascript:;'},{'title':'<?php echo $title; ?>添加'}]);
				MCMS.validate("#edit-form-area",{
					title  : { required: true},
					info : {required :true},
					ownertime:{required:true}
				});
				$("#edit-form-area").submit(function(){
					for(instance in CKEDITOR.instances) { 
						CKEDITOR.instances[instance].updateElement();  
					}
					MCMS.commajaxfun(this);
					return false;
				});
				$(".clearImg").on('click',function(){
					$("#titpic").val('');
					$(".image-prve").addClass('hide');
				});
			
				var uploader = WebUploader.create({
					auto: true,
					swf: '/ueditor/third-party/webuploader/js/Uploader.swf',
					server: '/ueditor/php/controller.php?action=uploadimage&encode=utf-8&type=1',
					pick: '#filePicker',
					fileVal:'upload',
					accept: {
						title: 'Images',
						extensions: 'gif,jpg,jpeg,bmp,png',
						mimeTypes: 'image/jpg,image/jpeg,image/png'
					}
				});
				uploader.on('uploadSuccess', function( file,resporse) {
					if(resporse.state =='SUCCESS'){
						var pvObj = $('#filePicker').parent().parent();
						$('#filePicker').prev().val(resporse.url);
						pvObj.prev().prev('.image-prve').remove();
						pvObj.before('<div class="form-group image-prve"><label class="col-sm-3 control-label no-padding-right" for="form-field-2"> 预览 </label><div class="col-sm-9"><a><img src="'+resporse.url+'" style="max-height:100px;max-width:500px;"></a></div></div><div class="space-4"></div>');
					}else{
						layer.alert(resporse.state);
					}		
					
				});
				uploader.on( 'uploadError', function( file ) {
					layer.alert('图片上传出错', {icon: 0});
				});
			});
		</script>
	</body>
</html>

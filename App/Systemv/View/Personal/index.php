<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{$ptitle}</title>
		<include file="Common:edittop" />
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>


			<div class="row">
				<div class="col-sm-6">
					<div class="space-4"></div>
					<div class="form-group txt-span">
						<label>姓名 </label>
						<span class="text-danger"><strong>{$info.nickname}</strong></span>
					</div>
					<div class="space-4"></div>
					<div class="form-group txt-span">
						<label>用户名 </label>
						<span class="text-danger"><strong>{$info.name}</strong></span>
					</div>
					
					<div class="space-4"></div>
					<div class="form-group txt-span">
						<label>角色 </label>
						<span class="text-danger"><strong>{$info.rname}</strong></span>
					</div>
					

				</div>
				<div class="col-sm-6" style="line-height:28px;">
					<a href="javascript:void(0);" class="layer-open-cool" data-type="zc"  shref="__CONTROLLER__/avatar?id={$info.id}" data-w="700px" data-h="550px" style="text-align: center;display:block;width:120px;"><img style="width:120px;" src="{$filedir}<?php echo ($info['id'].substr(md5($info['id']),8,16))  ?>.jpg?" /><br/>点击修改头像</a>
				</div>
			</div>
		</form>


		<include file="Common:editfooter" />
		<script>
			$(function(){
				MCMS.setTitle([{'title':'<?php echo $ptitle ?>'}]);
				MCMS.validate("#edit-form-area",{
					email: { email:true},
					phone: { mobile: true},
					telephone: { mobilePhone: true},
					nickname: { required: true}
				});
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this);
					return false;
				});

				setTimeout(function(){
					$('#loading-notice').slideUp('normal',function(){
						$(this).alert('close');
					});
				},4500);




				var temid =1;
				$(".layer-open-cool").on('click',function(){
					var $this = $(this);
					parent.layer.isuplaod = false;
					window.layerid = parent.layer.open({
						type: 2,
						title: $this.text(),
						closeBtn: 1, //不显示关闭按钮
						shade: 0.4,
						shadeClose: false,
						area: [$this.data('w'), $this.data('h')],
						content: $this.attr('shref'),
						maxmin: false,
						end:function(){
							if($this.data('type') == 'tem'){
								if(parent.layer.isuplaod == true){
									var img = $this.find('img');
									img.attr('src','{$filedir}'+$('[name=filename]').val()+'.jpg');
								}
							}else{
								if(parent.layer.isuplaod == true){
									var img = $this.find('img');temid++;
									img.attr('src',img.attr('src')+temid);
								}
							}
						}
					});
					return false;
				});

			});
		</script>
	</body>
</html>

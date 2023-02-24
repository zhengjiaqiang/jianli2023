
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>后台列表</title>
				<!-- basic styles -->
		<link href="/static/icenter/assets/css/bootstrap.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="/static/icenter/assets/css/font-awesome.min.css" />
		<link rel="stylesheet" href="/static/icenter/assets/css/ace.min.css" />
		<link rel="stylesheet" href="/static/icenter/assets/css/ace-rtl.min.css" />
		<link rel="stylesheet" href="/static/icenter/assets/css/ace-skins.min.css" />
		<!--[if lte IE 8]>
			<link rel="stylesheet" href="/static/icenter/assets/css/ace-ie.min.css" />
		<![endif]-->
		<!--[if lt IE 9]>
		<script src="/static/icenter/assets/js/html5shiv.js"></script>
		<script src="/static/icenter/assets/js/respond.min.js"></script>
		<![endif]-->

		<!-- diy styles -->
		<link href="/static/icenter/static/css/default.css" rel="stylesheet" />
		<link href="/static/icenter/static/pace/themes/red/pace-theme-flash.css"  rel="stylesheet" />	
		<link href="/static/common/select/bootstrap-select.min.css" rel="stylesheet" />
		<script src="/static/icenter/static/pace/pace.js"></script>
	
	</head>
	<body class="diy-css">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="/prview/setforward">
			<input type="hidden" name="ids" value="{$ids}" />
			<div class="form-group">
				<label class="col-sm-2 control-label" for="bigtypeid">推荐接收人</label>
				<div class="col-sm-4">
			    	<select class="form-control selectpicker" data-live-search="true" id="uid" name="uid"  title="接收邮箱">
						<option value="-1">请选择推荐接收人</option>
						<foreach name="list" item="v">
							<option data-tokens="{$v['email']}({$v['department']}-{$v['name']})" value="{$v['id']}">{$v['email']}({$v['department']}-{$v['name']})</option>
						</foreach>
					</select>
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
			<!-- basic scripts -->
	<!--[if lt IE 9]>
	<script type="text/javascript" src="/static/icenter/assets/js/jquery-1.10.2.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
	<script type="text/javascript" src="/static/icenter/assets/js/jquery-2.0.3.min.js"></script>
	<!--<![endif]-->
	<script src="/static/icenter/assets/js/bootstrap.min.js"></script>
	<script src="/static/icenter/assets/js/typeahead-bs2.min.js"></script>
	<script type="text/javascript" src="/static/common/datepicker/WdatePicker.js"></script>	
	<script type="text/javascript" src="/static/common/jquery.validate.js"></script>
	<script src="/static/common/jquery.cookie.min.js"></script>
	<script type="text/javascript" src="/static/common/layer2.2/layer.js"></script>
	<script type="text/javascript" src="/static/icenter/static/js/default.js?v=2"></script>
		
		<script type="text/javascript" src="/static/laydate/laydate.js"></script>
		<script type="text/javascript" src="/static/common/select/bootstrap-select.min.js"></script>
		<script type="text/javascript" src="/ckeditor/ckeditor.js?v=3"></script>
		<script>
			$(function(){
				$('.selectpicker').selectpicker({});
				MCMS.validate("#edit-form-area",{
					uid  : { required: true,min:0},
				});
				$("#edit-form-area").submit(function(){
					MCMS.commajaxfun(this,function(_res){
					    if(_res.status){
                            layer.msg(_res.info,{icon:1,time:2000},function(){
                                parent.layer.close(parent.layer.getFrameIndex(window.name));
                            });
                        }else{
                            MCMS.alert(_res.info);return false;
                        }
					});
					return false;
				});
			});
		</script>
	</body>
</html>
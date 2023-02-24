<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>后台列表</title>
		<include file="Common:edittop" />
		
	</head>
	<body class="diy-css" style="width: 95%;margin:0 auto;">
		<form id="edit-form-area" name="edit-form-area" class="form-horizontal mcms" role="form" method="post" action="__SELF__" data-type="{C('ISSTARTBIGTYPE')}">
			<?php if(!isset($info) || ! is_array($info))$info=array();?>
			<input type="hidden" name="pid" value="<?php echo get_array_val($info,'id') ?>" />
			<input type="hidden" name="id" value="{$id}" />
			<legend>岗位基本配置</legend>
			<fieldset>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="name">职位名称</label>
					<div class="col-sm-4">
						<?php html::text('name',get_array_val($info,'name'),array('class'=>'form-control','placeholder'=>'职位名称')); ?>
					</div>
					<label class="col-sm-2 control-label" for="usetime">发布日期</label>
					<div class="col-sm-4">
						<?php html::text('usetime',date('Y-m-d',get_array_val($info,'usetime',time())),array('class'=>'form-control date-simple','placeholder'=>'发布日期')); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="depid">科室</label>
					<div class="col-sm-4">
						<select class="form-control selectpicker" data-live-search="true" name="depid" id="depid" title="请选择科室">
							<option value="-1">请选择科室</option>
							<foreach name="depart" item="v">
								<option <if condition="$v['id'] eq $info['depid']">selected="selected"</if> data-tokens="{$v['name']} {$v['code']}" value="{$v['id']}">{$v['name']}</option>
							</foreach>
						</select>
					</div>
					<if condition="C('ISSTARTBIGTYPE')">
						<label class="col-sm-2 control-label" for="bigtypeid">批次</label>
						<div class="col-sm-4">
						<select class="form-control selectpicker" data-live-search="true" id="bigtypeid" name="bigtypeid"  title="请选择批次">
							<option value="-1">请选择批次</option>
							<foreach name="bigtype" item="v">
								<option data-tokens="{$v['name']}" <if condition="$v['id'] eq $info['bigtypeid']">selected="selected"</if> value="{$v['id']}">{$v['name']}</option>
							</foreach>
						</select>
					</if>
					</div>
				</div>
            </fieldset>  
		
			<fieldset>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="worktime">工作年限</label>
					<div class="col-sm-4">
					<select class="form-control selectpicker" name="worktime"  id="worktime" title="请选择工作年限">
						<option value="-1">请选择工作年限</option>
						<foreach name="set['工作年限']" item="v">
							<option value="{$v['id']}" <if condition="$v['id'] eq $info['worktime']">selected="selected"</if>>{$v['name']}</option>
						</foreach>
					</select>
					</div>
					
					<label class="col-sm-2 control-label" for="bigtypeid">岗位类别</label>
					<div class="col-sm-4">
						<!-- <foreach name="station" item="v" key="k">
							<label class="help-inline">
							<?php html::checkbox('type[]',in_array($k+1, array_filter(explode(',', get_array_val($info,'type',0)))),array('value'=>$k+1,'class'=>'ace type')); ?><span class="lbl">{$v}</span>	</label>	
						</foreach> -->
						<select class="form-control selectpicker" data-live-search="true" id="stationid" name="stationid"  title="请选择岗位类别">
							<option value="-1">请选择岗位类别</option>
							<foreach name="station" item="v">
								<option data-tokens="{$v['name']}" <if condition="$v['id'] eq $info['stationid']">selected="selected"</if> value="{$v['id']}">{$v['name']}</option>
							</foreach>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="btime">时间范围</label>
					<div class="col-sm-4">
						<?php html::text('btime',empty($info['btime']) ? date('Y-m-d').' ~ '.date('Y-m-d',strtotime('+1 month')) : date('Y-m-d',get_array_val($info,'btime')).' ~ '.date('Y-m-d',get_array_val($info,'etime')),array('class'=>'form-control date-simple range','placeholder'=>'开始时间~结束时间')); ?>
					</div>
				</div>
			</fieldset> 
			<fieldset>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="usetype">用工方式</label>
					<div class="col-sm-4">
					<select class="form-control selectpicker" name="usetype" id="usetype"   title="请选择用工方式">
						<option value="-1">请选择用工方式</option>
						<foreach name="set['用工方式']" item="v">
							<option value="{$v['id']}" <if condition="$v['id'] eq $info['usetype']">selected="selected"</if>>{$v['name']}</option>
						</foreach>
						
					</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="planid">招聘分类</label>
					<div class="col-sm-4">
					<select class="form-control selectpicker" name="planid"  title="请选择招聘分类">
						<option value="-1">请选择招聘分类</option>
						<foreach name="plan" item="v">
							<option data-tokens="{$v['name']}" <if condition="$v['id'] eq $info['planid']">selected="selected"</if> value="{$v['id']}">{$v['name']}</option>
						</foreach>
					</select>
						<!-- <input class="form-control" id="planid" name="planid" type="text" placeholder="开始时间"/> -->
					</div>
					<label class="col-sm-2 control-label" for="number">招聘人数</label>
					<div class="col-sm-4">
						<input class="form-control" id="number" name="number"  value="{$info['number']}"  type="text" placeholder="招聘人数"/>
					</div>
				</div>
            </fieldset> 
			<legend>岗位内容配置</legend>
			<fieldset>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="name">工作内容</label>
					<div class="col-sm-8">
						<?php html::textarea('intro',htmlspecialchars_decode(get_array_val($info,'intro')),array('class'=>'col-xs-8 col-sm-8','placeholder'=>'工作内容','data-type'=>2)); ?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="name">备注</label>
					<div class="col-sm-8">
						<?php html::textarea('remark',htmlspecialchars_decode(get_array_val($info,'remark')),array('class'=>'col-xs-8 col-sm-8','placeholder'=>'备注','data-type'=>2)); ?>
					</div>
				</div>
			</fieldset> 
			

			<div class="space-4"></div>

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
		<script type="text/javascript" src="/ckeditor/ckeditor.js?v=3"></script>
		<script type="text/javascript" src="/static/laydate/laydate.js"></script>
		<script type="text/javascript" src="/static/common/select/bootstrap-select.min.js"></script>
		<script>
			$(function(){
				$isstart=$("#edit-form-area").data('type');
				$('.selectpicker').selectpicker({});
				CKEDITOR.replace('intro',{filebrowserImageUploadUrl:"/ueditor/php/controller.php?action=uploadimage&encode=utf-8",allowedContent:true});
				lay('.date-simple').each(function(){
					laydate.render({
						elem: this
						,trigger: 'click'
						,range: $(this).hasClass('range') ? '~' : false
					});
				}); 
				MCMS.setTitle([{'title':'科室管理'},{'title':'科室管理'}]);
				var config={};
				if($isstart)  config={
						name  : { required: true},
						usetime:{required:true},
						depid:{required:true,minlength:1},
						bigtypeid:{required:true,min:1},
						// worktime:{required:true,min:1},
						// usetype:{required:true,min:1},
						// planid:{required:true,min:1},
						// stationid:{required:true,min:1},
						number:{required:true}
					};
				else config={
						name  : { required: true},
						usetime:{required:true},
						depid:{required:true,min:1},
						worktime:{required:true,min:1},
						usetype:{required:true,min:1},
						planid:{required:true,min:1},
						stationid:{required:true,min:1},
						number:{required:true}
					};
				MCMS.validate("#edit-form-area",config);
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

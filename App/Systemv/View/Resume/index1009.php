<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>后台列表</title>

	<include file="Common:listtop" />
	<style type="text/css">
		.widget-main .row{text-align: left;}
		.widget-main .label-right{
			display: inline-block;
			width: 150px;
		}
		.label-right input{width: 100% !important;height: 30px;}
		.label-right {width: 57%;}
		.label-right select{width: 100%;}
		.widget-main{max-width: 1240px;margin:0 auto;}
		/*.widget-main{max-width: 1370px;margin:0 auto;}*/
		.rowdiv{display: inline-block;margin:5px;}
		.box-search .btn-info{
			display: inline-block;
		    background-color: #9585bf !important;
		    width: 73px;
		    height: 30px;
		    vertical-align: top;
		    color: #fff !important;
		    padding-left: 16px;
		}
		.btn-info i{
			left: 6px; 
			right: auto !important;
			color: #fff !important;
		}
		.dropdown-menu{overflow-y: auto !important;min-height: 200px !important;max-height: 200px !important;}
		.dropdown-menu{top:33px !important;}
		.btn-group>.btn>.caret{margin-top: 0 !important;margin-left: 5px;}
	</style>
</head>
<body class="diy-css list-fixed" id="layer-open-parent">
	<div class="list-siderbar">
		<div class="opr-btns">
			<button type="button" class="btn btn-primary"  role="event-btn" data-e="recommend" data-loading-text="推荐中..."><i class="icon icon-ok"></i> 下一步</button>
			<button type="button" class="btn btn-primary"  role="event-btn" data-e="warehousing" role-e="delete" data-loading-text="入库中..."><i class="icon icon-tasks"></i> 入库</button>
			<if condition="$type eq 2">
			<button type="button" class="btn btn-primary" data-stitle="发送邮件"  role-e="sendemail"  data-loading-text="发送中..."><i class="icon icon-pencil"></i> 发送邮件</button>
			<elseif condition="$type eq 3"/><button type="button" class="btn btn-primary" data-stitle="通知面试"  role-e="notice"  data-loading-text="通知中..."><i class="icon icon-volume-up"></i> 通知面试</button>
			</if>
			<button type="button" class="btn btn-primary "  role-e="show"  data-w="1200px" data-h="700px" data-loading-text="预览中..."><i class="icon icon-eye-open"></i> 预览</button>

			<button type="button" class="btn btn-primary" data-stitle="导出基本信息" role="event-btn" data-e="information"  data-loading-text="导出中..."><i class="icon icon-pencil"></i> 导出基本信息</button>

			<button type="button" class="btn btn-primary" data-stitle="导出Word" role="event-btn" data-e="word"  data-loading-text="导出中..."><i class="icon icon-pencil"></i> 导出Word</button>

			<button type="button" class="btn btn-primary" data-stitle="导出多个文件压缩包" role="event-btn" data-e="wordlist"  data-loading-text="导出中..."><i class="icon icon-pencil"></i> 导出多个简历压缩包</button>

			<!-- <button type="button" class="btn btn-primary" data-stitle="导出问卷调查" role="event-btn" data-e="question"  data-loading-text="导出中..."><i class="icon icon-pencil"></i> 导出问卷调查</button> -->

			<button type="button" class="btn btn-primary"  role="event-btn" data-e="implement" data-loading-text="执行中..."><i class="icon icon-pencil"></i> 执行标签</button>

			<div class="btn-group">
				<button data-toggle="dropdown" class="btn btn-primary dropdown-toggle" data-loading-text="执行中...">
					状态设置
					<i class="icon-angle-down icon-on-right"></i>
				</button>
				<ul class="dropdown-menu" role="event-value-list"  style="max-height:350px;overflow-y:auto;">
					<!-- <li><a data-e="word">导出Word</a></li> -->
					<foreach name="typelist" item="v" key="k">
						<li><a data-e="changestatus" data-value="{$k}">{$v}</a></li>
					</foreach>
					
				</ul>
			</div>
		
			</div>
		</div>
	</div>
	<form id="search-form" class=" " role="searchform" method="get" action="__CONTROLLER__/index">
		<div class="oprsbtn highsearch" id="highsearch">
            <div class="box-search">
				<div class="widget-main">
					<div class="row">
						<div class="rowdiv">
							<label class="labelonly">科室：</label>
							<div class="label-right">
								<select class="form-control selectpicker" name="dep" data-live-search="true">
									<option value="">请选择科室</option>
									<foreach name="dlist" item="v" key="k">
										<option value="{$k}">{$v}</option>
									</foreach>
								</select>
							</div>
						</div>
						<div class="rowdiv">
							<label class="labelonly">职位：</label>
							<div class="label-right">
								<select class="form-control selectpicker" name="pos" data-live-search="true">
									<option value="">请选择职位</option>
									<foreach name="plist" item="v" key="k">
										<option value="{$k}">{$v}</option>
									</foreach>		
								</select>
							</div>
                        </div>
                        <div class="rowdiv">
							<label class="labelonly">志愿：</label>
							<div class="label-right">
								<select class="form-control selectpicker" name="vid" data-live-search="true">
									<option value="">请选择志愿</option>
                                    <option value="1">第一志愿</option>
                                    <option value="2">第二志愿</option>
                                    <option value="3">第三志愿</option>
								</select>
							</div>
						</div>
						<div class="rowdiv">
							<label class="labelonly">姓名：</label>
							<div class="label-right">
								<input name="wwhqywgur0" class="input-sm" type="text" id="name" placeholder="姓名">
							</div>
						</div>
						<div class="rowdiv">
							<label class="labelonly">性别：</label>
							<div class="label-right">
								<select name="xlb0ix53hv">
									<option value="">请选择性别</option>
									<option value="男">男</option>
									<option value="女">女</option>
								</select>
							</div>
						</div>
						<div class="rowdiv">
							<label class="labelonly" class="labelonly">户口地</label>
							<div class="label-right">
								<select name="province">
									<option value="">请选择户口地</option>
								</select>
							</div>
						</div>
					<!-- 	<div class="rowdiv">
							<label class="labelonly" class="labelonly">居住地</label>
							<div class="label-right">
								<select name="province2">
									<option value="">请选择居住地</option>
								</select>
							</div>
						</div> -->
						<div class="rowdiv">
							<label class="labelonly">毕业院校：</label>
							<div class="label-right">
								<input name="1wqxq7r9dpdyg" class="input-sm" type="text" id="major" placeholder="毕业院校">
							</div>
						</div>
						<div class="rowdiv">
							<label class="labelonly">专业：</label>
							<div class="label-right">
								<input name="dpa1eqch5bsv" class="input-sm" type="text" id="major" placeholder="专业">
							</div>
						</div>
						<!-- 
						<div class="rowdiv">
							<label class="labelonly">政治面貌：</label>
							<div class="label-right">
								<select name="1mb0ylvappl">
									<option value="">请选择政治面貌</option>
								</select>
							</div>
						</div> -->
						<div class="rowdiv">
							<label class="labelonly" class="labelonly">婚姻状况：</label>
							<div class="label-right">
								<select name="1hryxiw6t37">
									<option value="">请选择婚姻状况</option>
									<option value="未婚">未婚</option>
									<option value="已婚">已婚</option>
								</select>
							</div>
						</div>
						<!-- <div class="rowdiv">
							<label class="labelonly">民族：</label>
							<div class="label-right">
								<select name="vpyfqb4s85">
									<option value="">请选择民族</option>
								</select>
							</div>
						</div> -->
						<!-- <div class="rowdiv">
							<label class="labelonly">年龄：</label>
							<div class="label-right">
								<input name="2da9x8asadajf56" class="input-sm" type="text" placeholder="年龄">
							</div>
						</div> -->
						<div class="rowdiv">
							<label class="labelonly">应届生：</label>
							<div class="label-right">
								<select name="2izv9jv98uk">
									<option value=""></option>
									<option value="应届生">应届生</option>
									<option value="历届生">历届生</option>
								</select>
							</div>
						</div>
						<!-- <div class="rowdiv">
							<label class="labelonly">岗位调剂：</label>
							<div class="label-right">
								<select name="19payq4d2d8">
									<option value=""></option>
									<option value="可接受">可接受</option>
									<option value="不接受">不接受</option>
								</select>
							</div>
						</div> -->
						<div class="rowdiv">
							<label class="labelonly">最高学历：</label>
							<div class="label-right">
								<select name="2htc2bme4keq8">
									<option value="">请选择最高学历</option>
								</select>
							</div>
						</div>
						<div class="rowdiv">
							<label class="labelonly">是否有亲属受雇于本院：</label>
							<div class="label-right">
								<select name="9nxxxr2odl">
									<option value=""></option>
									<option value="是">是</option>
									<option value="否">否</option>
								</select>
							</div>
						</div>
						<div class="rowdiv">
							<label class="labelonly">简历标签：</label>
							<div class="label-right">
								<select name="biaoqian">
									<option value="">全部</option>
									<option value="1">未标签</option>
									<option value="2">已标签</option>
								</select>
							</div>
							
		          		  	<button type="submit" class="btn btn-info"><i class="icon icon-search"></i> 搜索</button>
		            		<button type="reset" class="btn " role="reset" data-form='#search-form'><i class="icon icon-undo"></i> 重置</button>
						</div> 
					</div>
				</div>
            </div>
        </div>
	</form>

	<form class="table-responsive">
		<input type="hidden" name="event"/>
		<input type="hidden" name="changestatus"/>
		<input type="hidden" name="value"/>
		<table id="main-table-frist" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th width="42">
						<label><input type="checkbox" class="ace" /><span class="lbl"></span></label>
					</th>
					
					<th width="80">志愿</th>
					<th width="50"  class="center">标记</th>
					<th width="80"  class="center">姓名</th>
					<th width="120" class="center">科室名称</th>
					<th width="120" class="center">职位名称</th>
					<th width="50" class="fc">性别</th>
					<th width="80">学历</th>
					<th width="160">户口</th>
					<th width="50">年龄</th>
					<th width="60" class="fc">手机号</th>
					<th width="180" class="fc">邮件</th>
					<th width="120" class="fc">投递时间</th>
					<th width="80" class="fc">状态</th>
					<th width="60" class="fc">备注</th>
					<if condition="$type eq 2">
					<th width="180" class="fc">转发</th></if>
					<th width="120">操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</form>
	<include file="Common:listfooter" />
	<script type="text/javascript" src="/static/index/js/data.js?v=3"></script>
	<script type="text/javascript" src="/static/common/layer2.2/extend/layer.ext.js"></script>
	<script type="text/javascript" src="/static/common/select/bootstrap-select.min.js"></script>
	<script type="text/javascript">
		jQuery(function($) {
			$('.selectpicker').selectpicker({});
            var pid = {$id},del={$delete},up={$update},_type={$type},all={$alltype};
			MCMS.setTitle([{'title':'<?php echo $title; ?>管理'}]);
			var onePageRow = {$onePageRow},sEcho=1,controller = '__CONTROLLER__';
			MCMS.btnEvent(controller+'/operate');
			MCMS.table.Init(onePageRow,controller+'/index?id='+pid+"&type="+_type,[
					__i("id",0,false),
					__i("volunteer",0,false),
					__i("biaoji",'center',false),
					__i("wwhqywgur0",'center',false),
					__i("name",'center',false),
					__i("depname",'center',false),
					__i("xlb0ix53hv",null,false),
					__i("2htc2bme4keq8",null,false),
					__i("province",null,false),
					__i("id",null,false),
					__i("2ywi8z97l7",null,false),
					__i("qspywhdn8w",null,false),
					__i("intimes",null,false),
					__i("show",1,false),
					__i("id",1,false),
					<if condition="$type eq 2">
					__i("id",0,false),</if>
					__i("id",0,false)
                ],[
					{   "aTargets": [0],
						"mRender": function (data, type, full) {return MCMS.table.checkbox(data);},
					},{ "aTargets": [2],
						"mRender": function (data, type, full) {
							var _html=typeof data !='object' ?  '<img src="'+(data == 9 ? '/static/index/images/yellow.png' : (data ==10 ?'/static/index/images/green.png' :  '/static/index/images/red.png'))+'">' : '';
							_html+=typeof data !='object' ? '<input type="hidden" value="'+data+'" name="status['+full.id+']"/>' : '';
							return _html;
						},
					},{ "aTargets": [3],
						"mRender": function (data, type, full) {
							return '<a class="green layer-open-cool"  title="预览" data-w="1000px" data-h="700px"  shref="/systemv/resume/prview/id/'+pid+'/pid/'+full.id+'">'+data+'</a>';
						},
					},{ "aTargets": [8],
						"mRender": function (data, type, full) {
							return data+'<br/>'+full['city'];
						},
					},{ "aTargets": [9],
						"mRender": function (data, type, full) {
							var myDate = new Date(),month = myDate.getMonth() + 1,day = myDate.getDate(),age = myDate.getFullYear() - full['26jmr8kmsz'].substring(6, 10) - 1; 
							if (full['26jmr8kmsz'].substring(10, 12) < month || full['26jmr8kmsz'].substring(10, 12) == month && full['26jmr8kmsz'].substring(12, 14) <= day)  age++; 
							return age;
						},
					},{
						"aTargets": [11],
						"mRender": function (data, type, full) { 
							var _html=!CheckIsNullOrEmpty(full.ename) ? data : data+'<br/>(<span style="color:red;">'+full.ename+'-'+full.useremail+'</span>)';

							return _html;
						}
					},{
						"aTargets": [12],
						"mRender": function (data, type, full) { return MCMS.table.pattern(data,'yyyy-MM-dd HH:mm:ss');}
					},{
						"aTargets": [13],
						"mRender": function (data, type, full) { 
							return full.cstatus == 0 ? data : full.showtitle;
						}
					},{
						"aTargets": [14],
						"mRender": function (data, type, full) { 
							// alert(data.remark);
							return '<a href="javascript:;" data-id="'+data+'" role="remark" >'+(CheckIsNullOrEmpty(full.remark)  ? full.remark : '点击备注')+'</a>';
						}
					},
					<if condition="$type eq 2">{
						"aTargets": [15],
						"mRender": function (data, type, full) { 
							var _html="<div>";
							if(CheckIsNullOrEmpty(full.forward)){
								_html='原:'+full.fromuser['department']+'(<span style="color:red;">'+full.fromuser['email']+'</span>)<br/>';
								_html+='新:'+full.touser['department']+'(<span style="color:green;">'+full.touser['email']+'</span>)';
							}
							return _html+"</div>";
							// // alert(data.remark);
							// return '<a href="javascript:;" data-id="'+data+'" role="remark" >'+(CheckIsNullOrEmpty(full.remark)  ? full.remark : '点击备注')+'</a>';
						}
					},</if>
					{
						"aTargets": [<if condition="$type eq 2">16<else/>15</if>],
						"mRender": function (data, type, full) { 
							var _h='<div class="action-buttons">';
							$.each(full.button,function(i){ 
								var uclass="green "+(CheckIsNullOrEmpty(full.button[i].url) ? 'layer-open-cool' : '')+"";
								var role=CheckIsNullOrEmpty(full.button[i].url) ? '' : ' role="event-btn"';
								var href=CheckIsNullOrEmpty(full.button[i].url) ? full.button[i].url.replace("{id}",pid).replace("{pid}",full.id) : '';
								_h+='<a class="'+uclass+'" data-status="'+full.button[i].status+'" data-value="'+full.id+'" title="预览" '+role+' data-e="'+full.button[i].event+'" data-step="'+full.button[i].step+'"  data-w="500px" data-h="400px"  shref="'+href+'"><i class="icon-pencil bigger-130"></i> '+full.button[i].name+'</a>';
							})
						
                           return _h+'</div>';
                        }
					}
				]);
				function CheckIsNullOrEmpty(value) {
					var reg = /^\s*$/;
					return (value != null && value != undefined && !reg.test(value))
				}
				$("#main-table-frist").on('click','[role="remark"]',function(){
					//判断是否选择
					var _id = $(this).data('id');
					layer.prompt({title: '请输入备注消息', formType: 2}, function(pass, index){
						// alert(pass);
						$.post('/systemv/resume/remark',{'info':pass,'id':_id},function(_res){
							layer.msg(_res.info,{icon:1,time:2000},function(){
								window.loadTable.fnDraw(false);
							});
						},'JSON');
					});
				});
				$("[role-e='show']").on('click',function(){
					//判断是否选择
					var _this=this;
					var chks = $("[name='chkItem[]']:checked");
					if (chks.length < 1) {
						MCMS.alert("请至少选择一项预览"); return false;
					}
					showResume(_checkValue(),0);
				});
				function showResume(ids,p){
					$.getJSON('/systemv/resume/show',{'id':ids,'p':p},function(_res){
						if(_res.status){
							var index=parent.layer.msg(_res.message,{icon:16,time:200000});
							var _href='/systemv/resume/next/id/'+_res.id+'/p/'+_res.p+'/ids/'+_res.ids;
							parent.layer.open({
								skin:'layer-skin',
								type: 2,
								id:'lay-show',
								title:'简历预览',
								closeBtn: 1, 
								shade: [0.4,'#fff'],
								shadeClose: false,
								area: ['1200px', '700px'],
								maxmin:  false,
								content: _href,
								end:function(){
									showResume(ids,p+1);
									
								},
								cancel:function(item){
									ids='';
								}
							});
						}else window.loadTable.fnDraw(false);
					});
				}
				var  _checkValue=function(){
					var chenked=$('#main-table-frist input[name="chkItem[]"]:checked').val([]);
					var value="";
					for(var i=0;i<chenked.length;i++){
						value+= chenked[i].value +"_";
					}
					return $.trim(value);
				}
				
		})
	</script>

	<script type="text/javascript">
		$('.labelonly').each(function(){
			if($(this).text()=='最高学历：'){
				for(var i=0;i<data.xueli.length;i++){
					var opc=document.createElement("option");
					opc.innerHTML=data.xueli[i].value;
					opc.value=data.xueli[i].name;
					$(this).next('div').children('select').append(opc);	
				}
			}
			else if($(this).text()=='民族：'){
				for(var i=0;i<data.nation.length;i++){
					var opcNation=document.createElement("option");
					opcNation.innerHTML=data.nation[i].value;
					opcNation.value=data.nation[i].name;
					$(this).next('div').children('select').append(opcNation);	
				}
			}
			else if($(this).text()=='政治面貌：'){
				for(var i=0;i<data.politic.length;i++){
					var opcPolitic=document.createElement("option");
					opcPolitic.innerHTML=data.politic[i].value;
					opcPolitic.value=data.politic[i].name;
					$(this).next('div').children('select').append(opcPolitic);	
				}
			}
			else if($(this).text()=='户口地'){
				for(var i=0;i<data.province.length;i++){
					var opcProvince=document.createElement("option");
					opcProvince.innerHTML=data.province[i].value;
					opcProvince.value=data.province[i].name;
					$(this).next('div').children('select').append(opcProvince);	
				}
			}
			else if($(this).text()=='居住地'){
				for(var i=0;i<data.province.length;i++){
					var opcProvince2=document.createElement("option");
					opcProvince2.innerHTML=data.province[i].value;
					opcProvince2.value=data.province[i].name;
					$(this).next('div').children('select').append(opcProvince2);	
				}
			}
		})
	</script>

	<script type="text/javascript">
		$('.layui-layer-setwin').click(function(){
			$('.layer-skin').remove();

		})
	</script>
</body>
</html>

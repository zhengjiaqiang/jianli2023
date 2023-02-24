
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>列表</title>

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
	
	<script src="/static/icenter/static/pace/pace.js"></script>
	<style type="text/css">
		.widget-main .row{text-align: left;}
		.widget-main .label-right{
			display: inline-block;
			width: 150px;
		}
		.label-right input{width: 100% !important;height: 30px;}
		.label-right {width: 57%;}
		.label-right select{width: 100%;}
		.widget-main{max-width: 1370px;margin:0 auto;}
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
			/*top:-6px !important;*/
			color: #fff !important;
		}
	</style>
</head>
<body class="diy-css list-fixed" id="layer-open-parent">
	<div class="list-siderbar">
		<div class="opr-btns">
			<button type="button" class="btn btn-primary "  role-e="show"  data-w="1200px" data-h="700px" data-loading-text="预览中..."><i class="icon icon-eye-open"></i> 预览</button>
			<button type="button" class="btn btn-primary" data-stitle="导出基本信息" role="information" data-e="information"  data-loading-text="发送中..."><i class="icon icon-pencil"></i> 导出基本信息</button>

<button type="button" class="btn btn-primary" data-stitle="导出Word" role="word" data-e="word"  data-loading-text="发送中..."><i class="icon icon-pencil"></i> 导出Word</button>

<button type="button" class="btn btn-primary"  role="sendother" data-loading-text="执行中..."><i class="icon icon-share"></i> 转发</button>

			<button type="button" class="btn btn-primary issend" style="display:none;"  role="implement" data-loading-text="执行中..."><i class="icon icon-flag"></i> 发送</button>
			
			</div>
		</div>
	</div>
	<form id="search-form" class=" " role="searchform" method="get" action="/prview/index">
		<div class="oprsbtn highsearch" id="highsearch">
            <div class="box-search">
				<div class="widget-main">
					<div class="row">
						<div class="rowdiv">
							<label class="labelonly">科室：</label>
							<div class="label-right">
								<select class="col-sm" name="dep">
									<option value="">请选择科室</option>
									<foreach name="dlist" item="v" key="k">
										<option value="{$k}">{$v}</option>
									</foreach>		</select>
							</div>
						</div>
					<div class="rowdiv">
						<label class="labelonly">职位：</label>
						<div class="label-right">
						<select class="col-sm" name="pos">
									<option value="">请选择职位</option>
									<foreach name="plist" item="v" key="k">
										<option value="{$k}">{$v}</option>
									</foreach>		</select>
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
						<label class="labelonly">毕业院校：</label>
						<div class="label-right">
							<input name="1xqeq7r9dpdyg" class="input-sm" type="text" id="major" placeholder="专业">
						</div>
					</div>
					<div class="rowdiv">
						<label class="labelonly">专业：</label>
						<div class="label-right">
							<input name="dpa1eqch5bsv" class="input-sm" type="text" id="major" placeholder="专业">
						</div>
					</div>
					<div class="rowdiv">
						<label class="labelonly">政治面貌：</label>
						<div class="label-right">
							<select name="1mb0ylvappl">
								<option value="">请选择政治面貌</option>
							</select>
						</div>
					</div>
					<div class="rowdiv">
						<label class="labelonly" class="labelonly">婚姻状况：</label>
						<div class="label-right">
							<select name="1hryxiw6t37">
								<option value="">请选择婚姻状况</option>
								<option value="未婚">未婚</option>
								<option value="已婚已育">已婚已育</option>
								<option value="已婚未育">已婚未育</option>
								<option value="其他">其他</option>
							</select>
						</div>
					</div>
					<div class="rowdiv">
						<label class="labelonly">民族：</label>
						<div class="label-right">
							<select name="vpyfqb4s85">
								<option value="">请选择民族</option>
							</select>
						</div>
					</div>
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
					<div class="rowdiv">
						<label class="labelonly">岗位调剂：</label>
						<div class="label-right">
							<select name="19payq4d2d8">
								<option value=""></option>
								<option value="可接受">可接受</option>
								<option value="不接受">不接受</option>
							</select>
						</div>
					</div>
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
            		<!-- <button type="reset" class="btn " role="reset" data-form='#search-form'><i class="icon icon-undo"></i> 重置</button> -->
					</div> 
					</div>
				</div>
            </div>
        </div>
	</form>

	<form class="table-responsive">
		<input type="hidden" name="event"/>
		<input type="hidden" name="changestatus"/>
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
					<th width="60" class="fc">性别</th>
					<th width="60" class="fc">手机号</th>
					<th width="60" class="fc">邮件</th>
					<th width="120" class="fc">投递时间</th>
					<th width="120" class="fc">备注</th>
					<th width="60" class="fc">操作</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
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

	<script src="/static/icenter/assets/js/jquery.dataTables.min.js"></script>
	<script src="/static/icenter/assets/js/jquery.dataTables.bootstrap.js"></script>
	<script type="text/javascript" src="/static/common/layer2.2/layer.js"></script>
	<script type="text/javascript" src="/static/common/layer2.2/extend/layer.ext.js"></script>
	<script type="text/javascript" src="/static/common/datepicker/WdatePicker.js"></script>	
	<script src="/static/common/jquery.cookie.min.js"></script>
	<script src="/static/icenter/static/js/default.js?v=37746"></script>
	<script type="text/javascript" src="/static/index/js/data.js?v=2"></script>
	<script type="text/javascript">
		jQuery(function($) {
			var onePageRow = 15,sEcho=1,controller = '/prview/';
			
			var islogin={$islogin},_id={$id};
			if(!islogin){
				var _index=layer.prompt({title: '请输入访问密码', formType: 1}, function(pass, index){
						$.post('/prview/check',{'passwd':pass,'id':_id},function(_res){
							if(!_res.status) layer.msg(_res.info,{icon:2,time:2000},function(){
								location.reload();
							});
							else{
								layer.close(_index);
								init();
							}
						},'JSON');
				});
			}else init();
			$('[role="implement"]').on('click',function(){
				var _this = btn = $(this);
				$parent().layer.confirm('是否继续执行“'+ $.trim(_this.text()) +'”操作', {icon: 3, title:'提示'}, function(index){
					btn.button('loading');
					$.post('/prview/implement',function(_res){
						layer.msg(_res.info,{icon:1,time:2000},function(){
							// location.reload();
							btn.button('reset');
							window.loadTable.fnDraw(false);
						});
					});
				});
			});
			$('[role="word"]').on('click',function(){
				var _this = btn = $(this);
				$parent().layer.confirm('是否继续执行“'+ $.trim(_this.text()) +'”操作', {icon: 3, title:'提示'}, function(index){
					btn.button('loading');
					$.post('/prview/word',function(_res){
						layer.msg(_res.info,{icon:1,time:2000},function(){
							btn.button('reset');
							window.location.href=_res.url;
						});
					});
				});
			});
			$('[role="information"]').on('click',function(){
				var _this = btn = $(this);
				$parent().layer.confirm('是否继续执行“'+ $.trim(_this.text()) +'”操作', {icon: 3, title:'提示'}, function(index){
					btn.button('loading');
					$.post('/prview/excel',function(_res){
						layer.msg(_res.info,{icon:1,time:2000},function(){
							btn.button('reset');
							window.location.href=_res.url;
						});
					});
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
			$("[role='sendother']").on('click',function(){ 
				if ($("[name='chkItem[]']:checked").length < 1) {
					MCMS.alert("请至少选择一项转发"); return false;
				}
				var _href='/prview/forward/ids/'+_checkValue();
				parent.layer.open({
					skin:'layer-skin',
					type: 2,
					id:'lay-show',
					title:'简历转发',
					closeBtn: 1, 
					shade: [0.4,'#fff'],
					shadeClose: false,
					area: ['400px', '300px'],
					maxmin:  false,
					content: _href,
					end:function(){
						// showResume(ids,p+1);
					},cancel:function(){
						// ids='';
					}
				});
				// $.getJSON('/prview/forward',{'id':_checkValue()},function(_res){

				// });
				// if(_checkValue().length ==0 )
			});
			function showResume(ids,p){
				$.getJSON('/prview/show',{'id':ids,'p':p},function(_res){
					if(_res.status){
						var index=parent.layer.msg(_res.message,{icon:16,time:200000});
						var _href='/prview/next/id/'+_res.id+'/p/'+_res.p+'/ids/'+_res.ids;
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
							},cancel:function(){
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
			function init(){
				MCMS.table.Init(onePageRow,controller+'index',[
					__i("id",0,false),
					__i("volunteer",0,false),
					__i("biaoji",'center',false),
					__i("wwhqywgur0",'center',false),
					__i("name",'center',false),
					__i("depname",'center',false),
					__i("xlb0ix53hv",null,false),
					__i("2ywi8z97l7",null,false),
					__i("qspywhdn8w",null,false),
					__i("addtime",null,false),
					__i("remark",null,false),
					__i("id",null,false),
				],[
					{   "aTargets": [0],
						"mRender": function (data, type, full) {
							return MCMS.table.checkbox(data);
						},
					},{ "aTargets": [2],
						"mRender": function (data, type, full) {
							var _html=typeof data !='object' ?  '<img src="'+(data == 9 ? '/static/index/images/yellow.png' : (data ==10 ?'/static/index/images/green.png' :  '/static/index/images/red.png'))+'">' : '';
							_html+=typeof data !='object' ? '<input type="hidden" value="'+data+'" name="status['+full.id+']"/>' : '';
							// console.log(full.istrue);
							if(full.istrue) $(".issend").show();
							return _html;
						},
					},{
						"aTargets": [9],
						"mRender": function (data, type, full) { return MCMS.table.pattern(data,'yyyy-MM-dd HH:mm:ss');}
					},{
						"aTargets": [11],
						"mRender": function (data, type, full) { 
							var _h='<div class="action-buttons">';
							_h+='<a class="green layer-open-cool" title="预览" data-w="1200px" data-h="700px" shref="/prview/resume/id/'+full.id+'"><i class="icon-pencil bigger-130"></i> 预览</a>';
                           return _h+'</div>';
						}
					}
				]);
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
			else if($(this).text()=='技术职称'){
				for(var i=0;i<data.politic.length;i++){
					var opcPolitic=document.createElement("option");
					opcPolitic.innerHTML=data.politic[i].value;
					opcPolitic.value=data.politic[i].name;
					$(this).next('div').children('select').append(opcPolitic);	
				}
			}
		})
	</script>
</body>
</html>
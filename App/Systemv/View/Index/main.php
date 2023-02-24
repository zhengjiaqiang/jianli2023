<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>首页控制台</title>
	<!-- basic styles -->
	<link href="__ROOT__/static/icenter/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="__ROOT__/static/icenter/assets/css/font-awesome.min.css" />
	<link rel="stylesheet" href="__ROOT__/static/icenter/assets/css/ace.min.css" />
	<link rel="stylesheet" href="__ROOT__/static/icenter/assets/css/ace-rtl.min.css" />
	<link rel="stylesheet" href="__ROOT__/static/icenter/assets/css/ace-skins.min.css" />
	<!--[if lte IE 8]>
		<link rel="stylesheet" href="__ROOT__/static/icenter/assets/css/ace-ie.min.css" />
	<![endif]-->
	<!--[if lt IE 9]>
	<script src="__ROOT__/static/icenter/assets/js/html5shiv.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/respond.min.js"></script>
	<![endif]-->

	<!-- diy styles -->
	<link href="__ROOT__/static/icenter/static/css/default.css" rel="stylesheet" />
  	<link href="__ROOT__/static/icenter/static/pace/themes/red/pace-theme-flash.css"  rel="stylesheet" />	
	<script src="__ROOT__/static/icenter/static/pace/pace.js"></script>
	<style type="text/css">
		.eq-select select{
			min-width: 100px;
			height:30px;
			border:1px solid #ccc;
			border-radius: 5px;
		}
		.eq-btn{
			position: absolute;
			top: 88px;
			right: 370px;
			min-width: 100px;
		}
		.eq-btn button{
			display: block;
			border:none;
			background: #fff;
			height:30px;
			padding:3px 4px;
			text-align: center;
			width: 70px;
			border-radius: 5px;
			color: #858585;
		}
	</style>
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
<body  class="diy-css default-skin">
	<div class="page-content">
		<div class="alert alert-block alert-success fade in">
			<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
			<if condition="$issign">
			<i class="icon-ok green"></i>
			当前密码已经90天未更换了
			<strong class="green">
				请及时更换密码
			</strong>
			<else/><i class="icon-ok green"></i>
			欢迎登录信息网站
			<strong class="green">
				（V.0.0.1）
			</strong>
			</if>
		</div>
		
				<div class="space-4"></div>
				<!-- <div class="space-4"></div> -->
				<!-- <div id="message" class="hide">
					<div class="form-group">
						<div class="col-sm-12">
							<label class="col-sm-2 control-label" for="message">  没有查询到相关数据。</label>
						</div>
					</div>	
				</div>	 -->
			</div>
			<div id="container" style="min-width:400px;height:200px"></div>

			<div class="row">

        <div class="col-sm-6">
            <div class="widget-box ">
                <div class="widget-header widget-header-small">
                    <h4 class="widget-title lighter">
                        <i class="ace-icon fa fa-folder-open orange"></i>职位情况
                    </h4>
                    <div class="widget-toolbar no-border">
                        <ul class="nav nav-tabs" id="myTab">
                          <foreach name="zhiwei" item="v" key="k">
                            <li class="<if condition='$k eq 0'>active</if>">
                                <a data-toggle="tab" href="#position{$k}" aria-expanded="true">{$v['name']}</a>
                            </li>
                          </foreach>
                        </ul>
                    </div>
                </div>
                
                <div class="widget-body">
                    <div class="widget-main no-padding">
                        <div class="tab-content">
 
                            <foreach name="zhiwei" item="v" key="k">
                                <div id="position{$k}" class="tab-pane <if condition='$k eq 0'> in  active</if>">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thin-border-bottom">
                                            <tr>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>科室
                                                </th>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>总职位
                                                </th>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>发布中
                                                </th>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>已结束
                                                </th>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>职位简历比
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead class="thin-border-bottom">
                                            <foreach name="v['list']" item="val">
                                                <tr>
                                                    <td><a href="">{$val['name']}</a></td>
                                                    <td>
                                                        <b class="green">{$val['alling']}</b>
                                                    </td>
                                                    <td>
                                                        <b class="blue">{$val['ending']}</b>
                                                    </td>
                                                    <td>
                                                        <b class="red">{$val['sending']}</b>
                                                    </td>
                                                    <td>
                                                        <b class="red">{$val['alling']+$val['ending']+$val['sending']}:{$val['scale']}</b>
                                                    </td>
                                                </tr>
                                            </foreach>
                                            <tr>
                                                <td>汇总</td>
                                                <td>
                                                    <b class="red">{$zhiweicount['allcount']}</b>
                                                </td>
                                                <td>
                                                    <b class="red">{$zhiweicount['endcount']}</b>
                                                </td>
                                                <td>
                                                    <b class="red">{$zhiweicount['sendcount']}</b>
                                                </td>
                                                <td>
                                                    <b class="red">{$zhiweicount['allcount']+$zhiweicount['endcount']+$zhiweicount['sendcount']}:{$zhiweicount['resumecount']}</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </foreach>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="col-sm-6">
            <div class="widget-box ">
                <div class="widget-header widget-header-flat">
                    <h4 class="widget-title lighter">
                        <i class="ace-icon fa fa-folder-open orange"></i>简历情况
                    </h4>
                    <div class="widget-toolbar no-border">
                        
                        <ul class="nav nav-tabs" id="myTab">
                         <foreach name="zhiwei" item="v" key="k">
                            <li class="<if condition='$k eq 0'>active</if>">
                                <a data-toggle="tab" href="#position{$k}" aria-expanded="true">{$v['name']}</a>
                            </li>
                          </foreach>
                        </ul>
                    </div>
                </div>
        
                <div class="widget-body">
                    <div class="widget-main no-padding">
                        <div class="tab-content">
                              <foreach name="zhiwei" item="v" key="k">
                                <div id="position{$k}" class="tab-pane <if condition='$k eq 0'> in  active</if>">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thin-border-bottom">
                                            <tr>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>科室
                                                </th>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>简历总数
                                                </th>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>面试数
                                                </th>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>录用数
                                                </th>
                                                <th>
                                                    <i class="ace-icon fa fa-caret-right blue"></i>已到岗
                                                </th>
                                            </tr>
                                        </thead>
                                        <thead class="thin-border-bottom">
                                            <foreach name="v['resumelist']" item="val">
                                                <tr>
                                                    <td><a href="">{$val['name']}</a></td>
                                                    <td>
                                                        <b class="green">{$val['alling']}</b>
                                                    </td>
                                                    <td>
                                                        <b class="blue">{$val['resuming']}</b>
                                                    </td>
                                                    <td>
                                                        <b class="red">{$val['ing']}</b>
                                                    </td>
                                                    <td>
                                                        <b class="red">{$val['set']}</b>
                                                    </td>
                                                </tr>
                                            </foreach>
                                            <tr>
                                                <td>汇总</td>
                                                <td>
                                                    <b class="red">{$resumecount['allcount']}</b>
                                                </td>
                                                <td>
                                                    <b class="red">{$resumecount['endcount']}</b>
                                                </td>
                                                <td>
                                                    <b class="red">{$resumecount['sendcount']}</b>
                                                </td>
                                                <td>
                                                    <b class="red">{$resumecount['resumecount']}</b>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </foreach>
                            <!-- <div id="resume0" class="tab-pane in active">
                                <table class="table table-bordered table-striped">
                                    <thead class="thin-border-bottom">
                                        <tr>
                                            <th>
                                                <i class="ace-icon fa fa-caret-right blue"></i>科室
                                            </th>
                                            <th>
                                                <i class="ace-icon fa fa-caret-right blue"></i>简历总数
                                            </th>
                                            <th>
                                                <i class="ace-icon fa fa-caret-right blue"></i>面试数
                                            </th>
                                            <th>
                                                <i class="ace-icon fa fa-caret-right blue"></i>录用数
                                            </th>
                                            <th>
                                                <i class="ace-icon fa fa-caret-right blue"></i>已到岗
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="resume15">
                                        <tr>
                                            <td><a href="javascript:Dashboard.showResumeDialog(85,15)">院办</a></td>
                                            <td>
                                                <b class="green">3</b>
                                            </td>
                                            <td>
                                                <b class="blue">0</b>
                                            </td>
                                            <td>
                                                <b class="red">0</b>
                                            </td>
                                            <td>0</td>
                                        </tr>
                                        
                                        
                                    </tbody>
                                </table> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- 
			<div class="clearfix">
				<div class="grid3">
					<span class="grey">
						<i class="ace-icon fa fa-folder-open fa-2x blue"></i>
						&nbsp; 简历数
					</span>
					<h4 class="bigger pull-right" id="total">0</h4>
				</div>

				<div class="grid3">
					<span class="grey">
						<i class="ace-icon fa fa-recycle fa-2x purple"></i>
						&nbsp; 面试数
					</span>
					<h4 class="bigger pull-right" id="interview_sum">0</h4>
				</div>

				<div class="grid3">
					<span class="grey">
						<i class="ace-icon fa fa-group fa-2x red"></i>
						&nbsp; 录用数
					</span>
					<h4 class="bigger pull-right" id="entry_sum">0</h4>
				</div>
			</div> -->
			<!-- <div class="eq-btn" style=""><button class="export">导出</button></div> -->
			<div class="eq-select" style="position: absolute;top: 88px;right: 40px;min-width: 100px;"><select name="type" id="type">
			<option value="1">本月</option>
			<option value="2">本年度</option>
			</select></div>
		</div>
	</div>
	<link rel="icon" href="https://static.jianshukeji.com/hcode/images/favicon.ico">
	<script src="https://code.highcharts.com.cn/highcharts/highcharts.js"></script>
	<script src="https://code.highcharts.com.cn/highcharts/modules/exporting.js"></script>
	<script src="https://code.highcharts.com.cn/highcharts/modules/oldie.js"></script>
	<script src="https://code.highcharts.com.cn/highcharts/themes/avocado.js"></script>
	<!-- basic scripts -->
	<!--[if lt IE 9]>
	<script type="text/javascript" src="__ROOT__/static/icenter/assets/js/jquery-1.10.2.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
	<script type="text/javascript" src="__ROOT__/static/icenter/assets/js/jquery-2.0.3.min.js"></script>
	<!--<![endif]-->
	<script src="__ROOT__/static/icenter/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/jquery.easy-pie-chart.min.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/jquery.sparkline.min.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/flot/jquery.flot.min.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/flot/jquery.flot.pie.min.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/flot/jquery.flot.resize.min.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/bootstrap.min.js"></script>
	<script src="__ROOT__/static/common/jquery.cookie.min.js"></script>
	<script src="__ROOT__/static/icenter/static/js/default.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/ace-elements.min.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/ace.min.js"></script>
 <script>
jQuery(function($) {

	var chat={
		_init:function(){
			
			this._click();
			this._xAxis();
		},
		_click:function(){
			var _this=this;
			$("[name='type']").on('change',function(){
				_this._xAxis($(this).val());
				// console.log();
			});
		},
		_data:function(){
			
		},
		_xAxis:function(type=1){
			var _this=this;
			this._get('/systemv/index/dayArr',{'type':type},function(_res){
				_this._data.day=_res.day;
				_this._data.data=_res.data;
				_this._data.text=_res.text;
				_this._set();
			});
		},
		_get:function(url,data,fun){
			$.getJSON(url,data,function(_res){
				fun(_res);
			});
		},
		_set:function(){
			var chart = new Highcharts.Chart('container', {
				title: {
					text: this._data.text,
					x: -20
				},
				xAxis: {
					categories: this._data.day
				},
				yAxis: {
					title: {
						text: '简历数'
					},
					plotLines: [{
						value: 0,
						width: 1,
						color: '#808080'
					}]
				},
				exporting: {
					enabled:false
				},
				credits: {
					enabled: false
				},
				tooltip: {
					valueSuffix: '份'
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					verticalAlign: 'middle',
					borderWidth: 0
				},
				series: this._data.data
			});
		}
	}
	chat._init();
	
});
</script> 
<script type="text/javascript">
    $("#myTab li").click(function(){
        $(this).addClass('active').siblings().removeClass('active');
        $('.tab-content').find($(this).children('a').attr('href')).css('display','block').addClass('active')
        $('.tab-content').children($(this).children('a').attr('href')).siblings().css('display','none').removeClass('active')
    })
</script>
</body>
</html>
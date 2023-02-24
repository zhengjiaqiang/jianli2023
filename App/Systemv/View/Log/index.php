<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{$webtitle}后台列表</title>

    <include file="Common:listtop" />
</head>

<body class="diy-css list-fixed">
    <div class="list-siderbar">
        <div class="opr-btns">
            <div class="pull-left" style="padding-right:15px;">
                <div class="box-search pull-left">
                    <div class="btn-group" id="id-btn-select-fu">
                        <input type="hidden" role-quit="true" data-bind="stype" />
                        <a class="btn dropdown-toggle" data-toggle="dropdown" data-click="dropdown">
								操作类型
								<i class="icon-angle-down icon-on-right"></i>
							</a>
                        <ul class="dropdown-menu" style="max-height:350px;overflow-y:auto;">
                            <li><a data-value="-1">所有类型</a></li>
                            <foreach name="logtype" item="vo">
                                <li data-value="{$vo['value']}"><a>{$vo['name']}</a></li>
                            </foreach>
                        </ul>
                    </div>
                    <!-- /btn-group -->
                    <input type="text" role-keydown="search-quit-text" role-quit="true" data-bind="key" placeholder="输入关键字按回车键" class="nav-search-input" autocomplete="off">
                    <a class="icon-search" role-click="search-quit-button"></a>
                </div>
                <a class="btn btn-primary up" id="highsearchbtn"><i class="icon icon-angle-down"></i> 高级搜索</a>
            </div>
        </div>
    </div>
    <form id="search-form" class="search-form " role="searchform" method="get" action="__CONTROLLER__/index">
        <div class="oprsbtn highsearch" id="highsearch">
            <div class="box-search">
                <div class="btn-group">
                    <input name="stype" type="hidden" value="{$stype}" />
                    <a class="btn dropdown-toggle" data-toggle="dropdown" data-click="dropdown">
						所有类型
						<i class="icon-angle-down icon-on-right"></i>
					</a>
                    <ul class="dropdown-menu">
                        <li><a data-value="-1">所有类型</a></li>
                        <foreach name="logtype" item="vo">
                            <li data-value="{$vo['value']}"><a>{$vo['name']}</a></li>
                        </foreach>
                    </ul>
                </div>
                <!-- /btn-group -->
                <input type="text" name="key" placeholder="输入关键字" class="nav-search-input" autocomplete="off">
            </div>
            <label>操作时间：</label><input id="start-date" type="text" name="start" class="input-sm " onfocus="var d5222=$dp.$('end-date');WdatePicker({dateFmt:'yyyy-MM-dd',onpicked:function(){d5222.focus();},maxDate:'#F{ $dp.$D(\'end-date\')}'})" value="">            - <input id="end-date" type="text" name="end" class="input-sm" onFocus="WdatePicker({dateFmt:'yyyy-MM-dd',minDate:'#F{ $dp.$D(\'start-date\')}'})" value="">

            <button type="button" class="btn btn-out"><i class="icon icon-search"></i> 导出</button>
            <button type="submit" class="btn btn-info"><i class="icon icon-search"></i> 搜索</button>
            <button type="reset" class="btn " role="reset" data-form='#search-form'><i class="icon icon-undo"></i> 重置</button>
        </div>
    </form>
    <form class="table-responsive">
        <input type="hidden" name="event" />
        <table id="main-table-frist" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="left">管理员id</th>
                    <th class="left">IP</th>
                    <th class="left" style="max-width:250px;">remark</th>
                    <th class="left" width="400" >url</th>
                    <th class="hidden-480 " desc='3' asc='4'>创建时间</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </form>
    <include file="Common:listfooter" />
    <script type="text/javascript">
        jQuery(function($) {
            var pid={$id};
            MCMS.setTitle([{
                'title': '<?php echo $title ?>管理'
            }]);
            var onePageRow = {$onePageRow},
                sEcho = 1,
                controller = '__CONTROLLER__',
                role = {  'up': '<?php echo $update  ?>' };
            MCMS.btnEvent(controller + '/operate');
            MCMS.table.Init(onePageRow, controller + '/index?id='+pid, [
                __i("uid", 0, false),
                __i("ips", 0, false),
                __i("info", null, false),
                __i("url",null, false),
                __i("createtime", 1, true)
            ], [

                {   "aTargets": [2],
                    "mRender": function (data, type, full) {return '<div style="max-width:250px;word-wrap:break-word">'+data+'</div>';},
                },{   "aTargets": [3],
                    "mRender": function (data, type, full) {return '<div style="max-width:250px;word-wrap:break-word">'+data+'</div>';},
                },{
						"aTargets": [4],
						"mRender": function (data, type, full) {return MCMS.table.pattern(data,'yyyy-MM-dd HH:mm');}	
					}
                    
            ]);
        })
        $(".btn-out").on('click',function(){
            var begin=$(":input[name='start']").val(),end=$(":input[name='end']").val(),_type=$(":input[name='stype']").val();
            var index=layer.confirm('确定下载当前选择日期的审计日志？',function(){
                $.post('/Systemv/log/createlog',{'b':begin,'e':end,'t':_type},function(_data){
                    layer.close(index);
                    location.href=_data.url;
                },'JSON')
            });
        });
    </script>
</body>

</html>
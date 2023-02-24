<html><head>
<meta charset="UTF-8">
<title></title>
<link rel="stylesheet" href="/static/layui/css/layui.css">
	<link rel="stylesheet" href="/static/layui_ext/dtree/dtree.css">
	<link rel="stylesheet" href="/static/layui_ext/dtree/font/dtreefont.css">
	<script type="text/javascript" src="/static/layui/layui.js""></script>
    <link rel="stylesheet" href="/static/layui_ext/dtree/font/dtreefont.css">
    <link rel="stylesheet" href="/static/layui_ext/table.css">

	<div class="layui-container">
		<div class="layui-row layui-col-space10">
			<div class="layui-fluid">
				<div class="layui-collapse">
					<div class="layui-colla-item">
						<div class="layui-colla-title">点击左侧需要的配置修改<i class="layui-icon layui-colla-icon"></i></div>
						<div class="layui-colla-content layui-show">
                            <form class="layui-form">
							<div class="layui-form layui-form-panel" lay-filter="show_form">
								<input type="hidden" id="id" name="nodeId" value="{$info['id']}" disab class="layui-input">
								<div class="layui-form-item">
									<label class="layui-form-label">配置名称：</label>
									<div class="layui-input-block">
										<input type="text" id="context" lay-verify="required" name="context" value="{$info['name']}" class="layui-input">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">配置值：</label>
									<div class="layui-input-block">
										<input type="text" id="value" name="value" value="{$info['value']}"  class="layui-input">
									</div>
								</div>
								<div class="layui-form-item">
									<label class="layui-form-label">配置排序：</label>
									<div class="layui-input-block">
										<input type="text" id="sort" name="sort"  value="{$info['sort']}"  class="layui-input">
									</div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-submit lay-filter="set">立即提交</button>
                                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                                    </div>
                                </div>
                            </div>
                            </form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<script type="text/javascript">
	// console.log(parent.window.hell());
	layui.use(['layer', 'form'], function(){
    var form=layui.form,layer=layui.layer,$=layui.$;
    form.on('submit(set)', function(data){
            $.post('/systemv/word/edit',data.field,function(_data){
                if(_data.status == 1){
                    layer.msg(_data.info,{shift:-1,time:2000,icon:1},function(){
						parent.window.refresh();
						return false;
                    });
                }else layer.alert(_data.info,{
                    title:'配置修改失败错误信息',
                    icon:2
                });
                return false;
            },'JSON');
            return false;
        });
    });
</script>
</body></html>
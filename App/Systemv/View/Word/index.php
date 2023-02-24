<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>后台列表</title>
		<link rel="stylesheet" href="/static/layui/css/layui.css">
	<link rel="stylesheet" href="/static/layui_ext/dtree/dtree.css">
	<link rel="stylesheet" href="/static/layui_ext/dtree/font/dtreefont.css">
	<script type="text/javascript" src="/static/layui/layui.js""></script>
	</head>
	<body class="diy-css">
	<div class="layui-container">	
		<div class="layui-row" style="margin-top:30px;">
			<div style="margin-left:18px;">
				<div class="layui-input-inline">
					<input class="layui-input" id="searchInput" value="" placeholder="输入查询节点内容...">
				</div>
				<div class="layui-input-inline">
					<button class="layui-btn layui-btn-normal" id="search_btn">查询</button>
				</div>
			</div>
			<div class="layui-col-lg4">
				<div style="height: 700px;overflow: auto;"><ul id="toolbarTree" class="dtree" data-id="0"></ul></div></div>
				<div class="layui-col-lg8"  style="margin-top:-37px;">
					<div style="height: 900px; width:100%;overflow: auto;">
						<iframe src="/systemv/word/uedit" scrolling="no" id="iframe_content" name="iframe_content" frameborder="0" style="width: 100%;height: 99%;"></iframe>
					</div>
				</div>
			</div>	
		</div>	
	</div>	
<include file="Common:editfooter" />
<script>

var _url = '/systemv/word/',tree;
layui.extend({
	dtree: '/static/layui_ext/dtree/dtree'
}).use(['dtree','layer','jquery','element'], function(){
	var dtree = layui.dtree, layer = layui.layer, $ = layui.jquery,element=layui.element;
    var DTree = tree = dtree.render({
			elem: "#toolbarTree",
			url: _url+"tree",
			initLevel: "4",
			line:true,
			toolbar:true,
			record:true,
			useIframe:true,
			iframeElem: "#iframe_content",
  			iframeUrl: "/systemv/word/uedit",
			iframeLoad: "all",
			dataFormat: "list",
			scroll:"#toolbarDiv",
			toolbarWay:"fixed",
			toolbarShow:["add","delete"],
			iframeDefaultRequest: {nodeId: "id"},
			toolbarBtn:[
				[
					{"label":"值","name":"value","type":"text"},
					{"label":"排序","name":"sort","type":"text"},
				],[
					{"label":"值","name":"value","type":"text"},
					{"label":"排序","name":"sort","type":"text"},
				]
			],
			toolbarStyle: {
				title: "设置",
				area: ["50%", "450px"]
			},
			done: function(data, obj){
				$("#search_btn").unbind("click");
				$("#search_btn").click(function(){
					var value = $("#searchInput").val();
					if(value){
						var flag = DTree.searchNode(value); 
						if (!flag) {layer.msg("该设置不存在！", {icon:5});}
					} else  DTree.menubarMethod().refreshTree(); 
					
				});
			},
			toolbarFun: {
				loadToolbarBefore: function(buttons, param, $div){
					if(!param.leaf) buttons.delToolbar = ""; 
					return buttons;
				},
				addTreeNode: function(treeNode, $div){
					$.ajax({
						type: "post",
						data: treeNode,
						url: _url+"/edit",
						success: function(result){
							DTree.changeTreeNodeAdd("refresh");
						},
						error: function(){
							DTree.changeTreeNodeAdd(false);
						}
					});
				},
				editTreeNode: function(treeNode, $div){
					DTree.changeTreeNodeEdit(true);
					$.ajax({
						type: "post",
						data: treeNode,
						url: _url+"/edit",
						success: function(result){
							DTree.changeTreeNodeEdit(result.param); 
						},
						error: function(){
							DTree.changeTreeNodeEdit(false);
						}
					});
				},
				delTreeNode: function(treeNode, $div){
					$.ajax({
						type: "post",
						data: treeNode,
						url: _url+"delete",
						success: function(result){
							DTree.changeTreeNodeDel(true);
						},
						error: function(){
							DTree.changeTreeNodeDel(false);
						}
					});
				}
			}
		});
		
	});
function refresh(){
	tree.menubarMethod().refreshTree(); 
}
</script>
	</body>
</html>

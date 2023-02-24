<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico"/>
    <title></title>
    <meta name="description" content="表单布局器">
    <meta name="keywords" content="表单设计">
    <!--基础界面样式-->
    <link href="/static/form/magicaldrag/assets/drag/ui/layui/2.5.4/css/layui.css" rel="stylesheet">
    <link href="/static/form/magicaldrag/assets/drag/css/magicalcoder-iframe.css" rel="stylesheet">
    <link href="/static/form/magicaldrag/assets/drag/ui/magicalcoder/1.1.0/magicalcoder.css" rel="stylesheet">
    <script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/lib/json3.js"></script>
    <script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/lib/echarts/4.2.1/echarts.min.js"></script>
    <script type="text/javascript" src="/static/form/magicaldrag/assets/drag/ui/layui/2.5.4/layui.all.js"></script>
 
    <script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/ui/iframe-ui-before.js"></script>
    <script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/ui/layui/2.5.4/iframe-ui.js"></script>
    <script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/ui/iframe-ui-end.js"></script>
    <script type="text/html" id="tableToolbar">
        <div class="layui-inline" lay-event="add"><i class="layui-icon layui-icon-add-1"></i></div>
        <div class="layui-inline" lay-event="update"><i class="layui-icon layui-icon-edit"></i></div>
        <div class="layui-inline" lay-event="delete"><i class="layui-icon layui-icon-delete"></i></div>
    </script>
    <script type="text/html" id="tableColToolbar">
        <a class="layui-btn layui-btn-xs" lay-event="detail">查看</a>
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
</head>
<body id="iframeBody" class="inner_iframe">
<div class="drag-mc-pane layui-form" id="magicalDragScene" magical_-coder_-id="Root">

</div>

</body>

</html>

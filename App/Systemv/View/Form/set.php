<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico"/>
    <title></title>
    <meta name="description" content="表单设计">
    <meta name="keywords" content="表单设计">
    <!--基础界面样式-->
    <link rel="stylesheet" href="/static/form/magicaldrag/assets/drag/js/lib/ztree3/css/zTreeStyle/zTreeStyle.css" media="all">
    <!--layui样式-->
    <link href="/static/form/magicaldrag/assets/drag/layui-v2.4.5/layui/css/layui.css" rel="stylesheet">
    <link href="/static/form/magicaldrag/assets/drag/layui-v2.4.5/layui-ext.css" rel="stylesheet">
    <link href="/static/form/magicaldrag/assets/drag/css/magical-drag-all.css" rel="stylesheet">

    <!--基于开源ztree的dom树插件-->
    <script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/lib/jscore.js"></script>
    <script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/lib/json3.js"></script>
    <script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/lib/jquery-2.0.0.min.js"></script>

</head>
<body class="magicalcoder-edit" style="">

<div id="magicalcoder-page-main">
    <div class="magicalcoder-page-header" >
        <div class="magicalcoder-page-title" title="MagicalCoder可视化拖拽布局器">
            <a style="color: #ffffe6" href="javascript:;">自定义表单</a>
        </div>
        <div style="color: #ffffe6; font-size: 13px; position: absolute; top: 24px; left: 244px;">
           
          
        </div>
        <div class="magicalcoder-page-link">
           

        </div>
    </div>
    <div class="magicalcoder-page-container">
        <div class="layui-row">
            <div class="layui-col-xs2 magicalcoder-hide-left-area">
                <!--左侧 做个能收展的-->
                <div class="layui-row" style="height: 100%;">
                    <div class="layui-col-xs12 el-aside" style="height: 60%;">
                        <!--左侧自定义组件列表-->
                        <div class="magicalcoder-left-config magicalcoder-page-drag-item-list"></div>
                    </div>
                    <div class="layui-col-xs12 el-aside" style="height: 40%;">
                        <!--结构树-->
                        <div id="magical_coder_ztree" class="ztree"></div>
                    </div>
                </div>
            </div>
            <div class="layui-col-xs8 magicalcoder-hide-center-area">
                <!--工作台-->
                <section class="el-container center-container is-vertical">
                    <header class="el-header btn-bar">
                        <div class="layui-row">
                            <div class="layui-col-lg4 layui-col-md4 layui-col-xs12">
                                <div class="el-radio-group">
                                    <label title="会把隐藏的元素以方框形式展示，更容易拖拽和选择元素" data-value="view" class="el-radio-button el-radio-button--small"><span class="el-radio-button__inner el-radio-group-label-active">视图</span></label>
                                    <label title="最大化的精简工作区域，无限接近最终作品" data-value="pretty" class="el-radio-button el-radio-button--small"><span class="el-radio-button__inner ">预览</span></label>
                                    <label title="您可以插入html代码" data-value="code" class="el-radio-button el-radio-button--small"><span class="el-radio-button__inner">HTML</span></label>
                                    <!-- <label title="辅助源码的javascript脚本" data-value="javascript" class="el-radio-button el-radio-button--small"><span class="el-radio-button__inner">脚本</span></label> -->
                                </div>
                            </div>
                            <div class="layui-col-lg4 layui-col-md4 layui-col-xs12">
                                <div class="magicalcoder-header-tools">
                                     <button name="magical_coder_align_left" type="button" class="el-button el-button--text el-button--medium"><i title="左对齐" class="layui-icon layui-icon-align-left"></i></button>
                                    <button name="magical_coder_align_center" type="button" class="el-button el-button--text el-button--medium"><i title="居中" class="layui-icon layui-icon-align-center"></i></button>
                                    <button name="magical_coder_align_right" type="button" class="el-button el-button--text el-button--medium"><i title="右对齐" class="layui-icon layui-icon-align-right"></i></button>
                                    <button name="magical_coder_choose_parent_dom" type="button" class="el-button el-button--text el-button--medium"><i title="聚焦外层结构" class="layui-icon layui-icon-radio"></i></button>
                                    <button name="magical_coder_move_to_prev" type="button" class="el-button el-button--text el-button--medium"><i title="向前移动(Ctrl+ <-)" class="layui-icon layui-icon-up"></i></button>
                                    <button name="magical_coder_move_to_next" type="button" class="el-button el-button--text el-button--medium"><i title="向后移动(Ctrl+ ->)" class="layui-icon layui-icon-down"></i></button>
                                    <button name="magical_coder_undo" type="button" class="el-button el-button--text el-button--medium"><i title="撤销(Ctrl+Z)" class="layui-icon layui-icon-refresh-1"></i></button>
                                    <button name="magical_coder_redo" type="button" class="el-button el-button--text el-button--medium"><i title="重做(Ctrl+Y)" class="layui-icon layui-icon-refresh"></i></button>
                                    <button name="magical_coder_format_code" type="button" class="el-button el-button--text el-button--medium layui-hide"><i title="格式化代码" class="layui-icon layui-icon-fonts-clear"></i></button>
                                    <!-- <button name="magical_coder_debug_javascript" type="button" class="el-button el-button--text el-button--medium"><i title="开启调试JS模式" class="layui-icon layui-icon-circle-dot"></i></button> -->
                                </div>
                            </div>
                            <div class="layui-col-lg4 layui-col-md4 layui-col-xs12">
                                <div>
                                    <button id="magical_coder_clear" type="button" class="el-button el-button--text el-button--medium"><img class="iconfont" src="/static/form/magicaldrag/assets/drag/img/top/delete1.png"><span>重置</span></button>
                                    <button id="magical_coder_save" type="button" class="el-button el-button--text el-button--medium"><img class="iconfont" src="/static/form/magicaldrag/assets/drag/img/top/save1.png"><span>保存</span></button>
                                   
                                </div>
                            </div>
                        </div>
                    </header>
                    <main class="magicalcoder-workground el-main widget-empty layui-form" lay-filter="magicalcoderDemoFilter">
                        <iframe id="dropInnerIframe" class="layui-hide" src="/systemv/form/html/id/267"></iframe>
                        <div id="magicalcoderCodeEdit" class="magicalcoder-demo-code magicalcoder-hide" style="font-size: 15px;height: 100%;">
                        </div>
                        <div id="magicalcoderJavascriptEdit" class="magicalcoder-demo-javascript magicalcoder-hide" style="font-size: 15px;height: 100%;">
                        </div>
                    </main>
                    <footer class="el-footer">
                        <div class="magicalcoder-hide-area">
                            <!--右键菜单-->
                            <ul id="magicalcoderRightMenu" class="layui-table right-menu">
                            </ul>
                            <!--资源加载中-->
                            <div id="loading" class="layui-progress layui-progress-big" lay-filter="loadingFilter">
                                <div class="layui-progress-bar" lay-percent="99%"></div>
                            </div>
                            <!--样式工具箱-->
                            <div class="layui-fluid magical-coder-mstyle" style="display: none">
                                <form class="layui-form " lay-filter="magicalCoderMstyleForm">
                                    <div class="layui-tab" lay-filter="magicalcoder-mstyle-lay-tab-change">
                                        <ul class="layui-tab-title">
                                            <li class="layui-this" lay-id="magicalcoder-mstyle-font">文字</li>
                                            <li class="" lay-id="magicalcoder-mstyle-layout">布局</li>
                                            <li class="" lay-id="magicalcoder-mstyle-background">背景</li>
                                        </ul>
                                        <div class="layui-tab-content">
                                            <div class="layui-tab-item layui-show">
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="color">文本颜色</label>
                                                    <div class="layui-input-block">
                                                        <div name="color" class="magicalcoder-color-picker layui-inline" mc-attr-predefine="true" mc-attr-alpha="true"></div>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="text-indent">文本缩进</label>
                                                    <div class="layui-input-block">
                                                        <div mc-style-dw="px" name="text-indent" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="1000"></div>
                                                        <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="text-align">水平对齐</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle" name="text-align" class=""><option selected="" value="">请选择</option><option value="left">靠左</option><option value="right">靠右</option><option value="center">居中</option><option value="justify">两端对齐</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="word-spacing">文字间距</label><div class="layui-input-block">
                                                    <div mc-style-dw="px" name="word-spacing" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="1000"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="letter-spacing">字母间距</label><div class="layui-input-block">
                                                    <div mc-style-dw="px" name="letter-spacing" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="100"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="text-transform">大小写</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="text-transform" class=""><option selected="" value="">请选择</option><option value="uppercase">大写</option><option value="lowercase">小写</option><option value="capitalize">大写字母开头</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="text-decoration">文本装饰</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="text-decoration" class=""><option selected="" value="">请选择</option><option value="underline">下划线</option><option value="line-through">中划线</option><option value="overline">上划线</option><option value="blink">闪烁</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="text-decoration">文本方向</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="direction" class=""><option selected="" value="">请选择</option><option value="ltr">从左至右</option><option value="rtl">从右至左</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="text-decoration">空白处理</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="white-space" class=""><option selected="" value="">请选择</option><option value="pre-line">合并空白保留换行</option><option value="nowrap">br换行</option><option value="pre">保留空白</option><option value="pre-wrap">保留空白保留换行</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <hr class="">
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="font-family">字体名称</label><div class="layui-input-block">
                                                    <input type="text" autocomplete="off" class="magicalcoder-text layui-input" name="font-family">
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="font-style">字体风格</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="font-style" class=""><option selected="" value="">请选择</option><option value="italic">斜体</option><option value="oblique">倾斜</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="font-size">字体大小</label><div class="layui-input-block">
                                                    <div mc-style-dw="px" name="font-size" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="100"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="font-weight">字体加粗</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="font-weight" class=""><option selected="" value="">请选择</option><option value="lighter">极细</option><option value="bold">加粗</option><option value="bolder">更粗</option><option value="100">100</option><option value="200">200</option><option value="300">300</option><option value="400">400</option><option value="500">500</option><option value="600">600</option><option value="700">700</option><option value="800">800</option><option value="900">900</option></select>
                                                </div>
                                                </div>

                                            </div>
                                            <div class="layui-tab-item">
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="width">宽度</label><div class="layui-input-block">
                                                    <div mc-style-dw="%" name="width" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="height">高度</label><div class="layui-input-block">
                                                    <div mc-style-dw="px" name="height" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="max-width">最大宽度</label><div class="layui-input-block">
                                                    <div mc-style-dw="%" name="max-width" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="max-height">最大高度</label><div class="layui-input-block">
                                                    <div mc-style-dw="px" name="max-height" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="min-width">最小宽度</label><div class="layui-input-block">
                                                    <div mc-style-dw="%" name="min-width" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="min-height">最小高度</label><div class="layui-input-block">
                                                    <div mc-style-dw="px" name="min-height" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="line-height">行高</label><div class="layui-input-block">
                                                    <div mc-style-dw="px" name="line-height" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="100"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <hr class="">
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="float">浮动</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  class="" name="float"><option selected="" value="">请选择</option><option value="left">向左</option><option value="right">向右</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <hr class="">
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="position">定位</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  class="" name="position"><option selected="" value="">请选择</option><option value="absolute">绝对定位-针对父元素</option><option value="fixed">固定定位-针对浏览器</option><option value="relative">相对定位-自身</option><option value="static">顺序定位</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="top,right,bottom,left">偏移</label>
                                                    <div class="layui-input-block">
                                                        <div class="layui-row layui-col-space5">
                                                            <div class="layui-col-md12">
                                                                <p>上</p>
                                                                <div mc-style-dw="px" name="top" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="-1000" mc-attr-max="1000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>下</p>
                                                                <div mc-style-dw="px" name="bottom" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="-1000" mc-attr-max="1000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>左</p>
                                                                <div mc-style-dw="px" name="left" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="-1000" mc-attr-max="1000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>右</p>
                                                                <div mc-style-dw="px" name="right" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="-1000" mc-attr-max="1000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <hr class="">
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="top,right,bottom,left">内边距</label>
                                                    <div class="layui-input-block">
                                                        <div class="layui-row layui-col-space5">
                                                            <div class="layui-col-md12">
                                                                <p>上</p>
                                                                <div mc-style-dw="px" name="padding-top" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>下</p>
                                                                <div mc-style-dw="px" name="padding-bottom" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>左</p>
                                                                <div mc-style-dw="px" name="padding-left" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>右</p>
                                                                <div mc-style-dw="px" name="padding-right" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="2000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="top,right,bottom,left">外边距</label>
                                                    <div class="layui-input-block">
                                                        <div class="layui-row layui-col-space5">
                                                            <div class="layui-col-md12">
                                                                <p>上</p>
                                                                <div mc-style-dw="px" name="margin-top" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="-1000" mc-attr-max="1000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>下</p>
                                                                <div mc-style-dw="px" name="margin-bottom" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="-1000" mc-attr-max="1000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>左</p>
                                                                <div mc-style-dw="px" name="margin-left" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="-1000" mc-attr-max="1000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>右</p>
                                                                <div mc-style-dw="px"  name="margin-right" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="-1000" mc-attr-max="1000"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="layui-form-item">
                                                    <hr class="">
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="border-style">边框样式</label>
                                                    <div class="layui-input-block">
                                                        <div class="layui-row layui-col-space5">
                                                            <div class="layui-col-md12">
                                                                <select lay-filter="magical-coder-filter-mstyle"  name="border-top-style" class=""><option selected="" value="">选择上边框</option><option value="dotted">点状.......</option><option value="dashed">虚线------</option><option value="solid">实线_____</option><option value="double">双线</option><option value="groove">3D 凹槽</option><option value="ridge">3D 垄状</option><option value="inset">3D inset</option><option value="outset">3D outset </option><option value="hidden">无，兼容冲突</option></select>
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <div class="layui-row layui-col-space5">
                                                                    <div class="layui-col-md6">
                                                                        <select lay-filter="magical-coder-filter-mstyle"  name="border-left-style" class=""><option selected="" value="">左边框</option><option value="dotted">点状</option><option value="dashed">虚线</option><option value="solid">实线</option><option value="double">双线</option><option value="groove">3D 凹槽</option><option value="ridge">3D 垄状</option><option value="inset">3D inset</option><option value="outset">3D outset </option><option value="hidden">无，兼容冲突</option></select>
                                                                    </div>
                                                                    <div class="layui-col-md6">
                                                                        <select lay-filter="magical-coder-filter-mstyle"  name="border-right-style" class=""><option selected="" value="">右边框</option><option value="dotted">点状</option><option value="dashed">虚线</option><option value="solid">实线</option><option value="double">双线</option><option value="groove">3D 凹槽</option><option value="ridge">3D 垄状</option><option value="inset">3D inset</option><option value="outset">3D outset </option><option value="hidden">无，兼容冲突</option></select>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <select lay-filter="magical-coder-filter-mstyle"  name="border-bottom-style" class=""><option selected="" value="">选择下边框</option><option value="dotted">点状</option><option value="dashed">虚线</option><option value="solid">实线</option><option value="double">双线</option><option value="groove">3D 凹槽</option><option value="ridge">3D 垄状</option><option value="inset">3D inset</option><option value="outset">3D outset </option><option value="hidden">无，兼容冲突</option></select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="top,right,bottom,left">边框宽度</label>
                                                    <div class="layui-input-block">
                                                        <div class="layui-row layui-col-space5">
                                                            <div class="layui-col-md12">
                                                                <p>上</p>
                                                                <div mc-style-dw="px" name="border-top-width" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="30"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>下</p>
                                                                <div mc-style-dw="px" name="border-bottom-width" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="30"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>左</p>
                                                                <div mc-style-dw="px" name="border-left-width" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="30"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                            <div class="layui-col-md12" style="height: 20px">
                                                            </div>
                                                            <div class="layui-col-md12">
                                                                <p>右</p>
                                                                <div mc-style-dw="px" name="border-right-width" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="30"></div>
                                                                <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="border-color">边框颜色</label><div class="layui-input-block">
                                                    <div class="layui-row layui-col-space5">
                                                        <div class="layui-col-md12 layui-col-xs12">
                                                            <div class="magicalcoder-color-picker layui-inline" style="margin-left: 40px;" mc-attr-predefine="true" mc-attr-alpha="true" name="border-top-color" title="border-top-color"></div>
                                                        </div>
                                                        <div class="layui-col-md12 layui-col-xs12">
                                                            <div class="layui-row layui-col-space5">
                                                                <div class="layui-col-md6 layui-col-xs6">
                                                                    <div class="magicalcoder-color-picker layui-inline" mc-attr-predefine="true" mc-attr-alpha="true" name="border-left-color" title="border-left-color"></div>
                                                                </div>
                                                                <div class="layui-col-md6 layui-col-xs6">
                                                                    <div class="magicalcoder-color-picker layui-inline" mc-attr-alpha="true" mc-attr-predefine="true" name="border-right-color" title="border-right-color"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="layui-col-md12 layui-col-xs12">
                                                            <div class="magicalcoder-color-picker layui-inline" style="margin-left: 40px;" mc-attr-alpha="true" mc-attr-predefine="true" title="border-bottom-color" name="border-bottom-color"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="layui-tab-item">
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="background-color">背景颜色</label>
                                                    <div class="layui-input-block">
                                                        <div class="magicalcoder-color-picker layui-inline" mc-attr-predefine="true" name="background-color" mc-attr-alpha="true"></div>
                                                    </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="background-image">背景图片</label><div class="layui-input-block">
                                                    <input  type="text" name="background-image" class="magicalcoder-text layui-input" value=""  placeholder="url('http://....jpg')或url('')">
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="background-repeat">背景重复</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="background-repeat" class=""><option selected="" value="">请选择</option><option value="repeat-x">水平重复</option><option value="repeat-y">垂直重复</option><option value="no-repeat">无</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="background-position">背景定位</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="background-position" class=""><option selected="" value="">请选择</option><option value="center center">居中</option><option value="left top">靠上靠左</option><option value="center top">靠上居中</option><option value="right top">靠下靠右</option><option value="left center">靠左居中</option><option value="right center">靠右居中</option><option value="left bottom">靠下靠左</option><option value="center bottom">靠下居中</option><option value="right bottom">靠下靠右</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="background-attachment">背景连动</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="background-attachment" class=""><option selected="" value="">请选择</option><option value="scroll">跟随滚动</option><option value="fixed">固定</option></select>
                                                </div>
                                                </div>
                                                <div class="layui-form-item">
                                                    <hr class="">
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="opacity">透明度</label><div class="layui-input-block">
                                                    <select lay-filter="magical-coder-filter-mstyle"  name="opacity" class=""><option selected="" value="">请选择</option><option value="0.1">0.1</option><option value="0.2">0.2</option><option value="0.3">0.3</option><option value="0.4">0.4</option><option value="0.5">0.5</option><option value="0.6">0.6</option><option value="0.7">0.7</option><option value="0.8">0.8</option><option value="0.9">0.9</option><option value="1">1</option></select>
                                                </div>
                                                <div class="layui-form-item">
                                                    <label class="layui-form-label" title="line-height">层级关系</label><div class="layui-input-block">
                                                    <div mc-style-dw="" name="z-index" class="magicalcoder-slider" mc-attr-tips="true" mc-attr-input="true" mc-attr-min="0" mc-attr-max="99999"></div>
                                                    <i class="layui-icon layui-icon-set-sm icon-tmp"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <!--ace 区域-->
                            <div id="aceHelperHideDiv" class="magicalcoder-hide" style="font-size: 15px;width:100%;height: 100%">
                            </div>
                        </div>
                    </footer>
                </section>
            </div>
            <div class="layui-col-xs2 magicalcoder-hide-right-area">
                <!--右侧 做个能收展的-->
                <aside class="el-aside magicalcoder-page-right-config-container">
                    <section class="el-container is-vertical layui-form" id="magicalcoder_right_side" lay-filter="rightConfigForm">
                        <header class="el-header" style="height: 45px;"></header>
                        <main class="el-main config-content demo-right-config"></main>
                    </section>
                </aside>
            </div>
        </div>
        <!--左右隐藏-->
        <!--<div class="magicalcoder-hide-left"><i class="layui-icon layui-icon-prev"></i></div>-->
        <div class="magicalcoder-hide-right"><i class="layui-icon layui-icon-next"></i></div>
        <input type="hidden" name="pid" value="{$pid}" readonly/>
    </div>
</div>

<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/min/magicalcoder-jsg.js"></script>

<!--[if IE 9]>
<script type="text/javascript">
    alert("亲爱的用户，IE9无法使用拖拽操作，但是您可以通过点击左侧控件来加入工作区间，然后通过复制粘贴等快捷键来操作。推荐使用Chrome获取最佳体验")
</script>
<![endif]-->
<!--[if lt IE 9]>
<script type="text/javascript">
    alert("亲爱的用户，IE8及之前版本现已被互联网大部分网站抛弃，我们不再兼容。推荐使用Chrome获取最佳体验")
</script>
<![endif]-->
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/min/magicalcoder-sn.js"></script>

<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/ace.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/ext-beautify.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/ext-elastic_tabstops_lite.js"></script>
<!--这个千万别引入啊 一旦引入 tab失效-->
<!--<script src="assets/drag/js/lib/ace-1.4.3/ext-emmet.js"></script>-->
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/ext-error_marker.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/ext-keybinding_menu.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/ext-modelist.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/ext-static_highlight.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/ext-searchbox.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/ext-language_tools.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/mode-html.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/worker-html.js"></script>
<script src="/static/form/magicaldrag/assets/drag/js/lib/ace-1.4.3/theme-monokai.js"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/min/magicalcoder-ht.js"></script>
<script type='text/javascript' src="/static/form/magicaldrag/assets/drag/layui-v2.4.5/layui/layui.all.js"></script>
<!--dom树-->
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/lib/ztree3/js/jquery.ztree.all.js"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/application-env.js?v=<?php echo rand(1000,89999); ?>"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/key.js"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/api.js"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/callback.js"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/ui/layui/2.5.4/constant.js?v=<?php echo rand(1000,89999); ?>"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/min/magical-coder-drag-all.js"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/lowcode-constant.js?v=<?php echo rand(1000,89999); ?>"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/lowcode-util.js?v=<?php echo rand(1000,89999); ?>"></script>
<script type="text/javascript" src="/static/form/magicaldrag/assets/drag/js/user/form.js?v=<?php echo rand(1000,89999); ?>"></script>
<script>
$(function(){
    var _pid=$(":input[name='pid']").val();
    MagicalCallback.prototype.save_html = function (root,html) {
        $.post('/systemv/form/save',{'h':root,'pid':_pid},function(_data){
            layer.msg(_data.info,{icon:_data.status,time:2000});
        },'JSON');
    }
    MagicalCallback.prototype.before_drop_left_to_center=function(dragItem,targetElem){
       dragItem.attr('name',guid());
       return true;
    }
    setTimeout(function () {
        var api = new MagicalApi();
        api.insertHtml('{$html}');
    },1000)
    function guid() {
        return Number(Math.random().toString().substr(3, 3) + Date.now()).toString(36);
    }
})

</script>
</body>
</html>

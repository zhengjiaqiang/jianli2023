<include file="Common:header"/>
<link rel="stylesheet" type="text/css" href="/static/index/css/css.css?v=3">
<style type="text/css">
    .layui-card-body {
        padding: 10px 0px;
    }

    .layui-marginTop {
        margin-top: 20px;
    }

    #aupfile {
        cursor: auto;
    }

    .font-color {
        display: inline-block;
        color: red;
        padding: 0 5px;
    }

    .layui-chengluo .layui-form-checkbox i {
        left: 0;
        border-left: 1px solid #d2d2d2;
    }

    .layui-chengluo .layui-form-checkbox span {
        margin-left: 30px;
    }

    .layui-chengluo .layui-form-checkbox {
        margin-top: 20px;
    }

    .layui-card-header {
        background: #eee;
        border-left: 2px solid #F60;
    }

    .step-parent {
        position: fixed;
        bottom: 30px;
        text-align: center;
        width: 860px;
        background: #fff;
    }

    .layui-form-radioed > i {
        color: #5FB878 !important;
    }

    .jianli .tab_bot {
        margin-bottom: 50px;
    }

    .layui-form-select dl {
        right: 0;
        left: auto;
    }

    .myeducation {
        /*margin-left: 0 !important;*/
        text-align: left !important;
    }

    .myeducation-parent {
        margin-bottom: -12px !important;
    }
    .myeducation4{
        margin-bottom: -12px !important;
    }
</style>

<body id="body">
<header>

</header>
<!--头部结束-->
<!-- <div class="banner" id="banner"></div> -->
<div id="content">
    <include file="Common:left"/>
    <div id="right">
        <div class="title"><h3></h3></div>
        <div class="jianli">
            <ul class="tab_top">

            </ul>
            <div class="tab_bot">
                <form class="layui-form">
                    <input type="hidden" name="cid" value="" readonly/>
                    <div class="content"></div>
                    <div class="step-parent">
                        <div class="step_a" id="step_a"></div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
<include file="Common:footer"/>
<script type="text/javascript" src="/static/index/js/data.js?v={:rand(1000,9999)}"></script>
<script type="text/javascript" src="/static/mods/xueli.js?v={:rand(1000,9999)}"></script>
<script type="text/javascript">
    var DEFAULT_VERSION = 8.0;
    var ua = navigator.userAgent.toLowerCase();
    var isIE = ua.indexOf("msie") > -1;
    var safariVersion;
    if (isIE) {
        alert('为了获得更好的用户体验，请在电脑端使用火狐、谷歌、 360浏览器填写简历。');
    }
</script>
<script>
    layui.config({
        base: '/static/mods/'
        , version: '3.0'
    }).use(['jquery', 'form', 'layer', 'util', 'laydate', 'upload', 'element', 'layarea'], function () {
        var $ = layui.jquery, form = layui.form, layer = layui.layer, laydate = layui.laydate, index,
            upload = layui.upload, imgMax = {$max};
        form.verify({
            errormessage: function (v, i) {
                if (v.length == 0 || v == 0 || v == '请选择专业大类' || v == '请选择专业' || v == '请选择院校省份' || v == '请选择毕业院校') {
                    // return typeof($(i).attr('error-message')) != 'undefined' ? $(i).attr('error-message') :);
                    return typeof ($(i).attr('error-message')) != 'undefined' ? $(i).attr('error-message') : (typeof ($(i).attr('placeholder')) != 'undefined' ? $(i).attr('placeholder') : '当前为必填项！');
                }
            },
            errorRadio: function (value, item) {
                // var verifyName=$(item).attr('name')
                // , verifyType=$(item).attr('type')
                // ,formElem=$(item).parents('.layui-form')
                // ,verifyElem=formElem.find('input[name='+verifyName+']')
                // ,isTrue= verifyElem.is(':checked')
                // ,focusElem = verifyElem.next().find('i.layui-icon');
                // if(!isTrue || !value){
                // 	focusElem.css(verifyType=='radio'?{"color":"#FF5722"}:{"border-color":"#FF5722"});
                // 	focusElem.first().attr("tabIndex","1").css("outline","0").blur(function() {
                // 		focusElem.css(verifyType=='radio'?{"color":""}:{"border-color":""});
                // 	}).focus();
                // 	return typeof($(i).attr('error-message')) != 'undefined' ? $(i).attr('error-message') : (typeof($(i).attr('placeholder')) != 'undefined' ? $(i).attr('placeholder') : '请勾选本人承诺！');
                // }
                if (!$(i).next('div').is('.layui-form-radioed')) {
                    return typeof ($(i).attr('error-message')) != 'undefined' ? $(i).attr('error-message') : (typeof ($(i).attr('placeholder')) != 'undefined' ? $(i).attr('placeholder') : '请勾选本人承诺！');
                }
            },
            radioParent: function (v, i) {
                if ($(i).find('div').hasClass('layui-form-checked')) {
                } else {
                    return typeof ($(i).attr('error-message')) != 'undefined' ? $(i).attr('error-message') : (typeof ($(i).attr('placeholder')) != 'undefined' ? $(i).attr('placeholder') : '请勾选本人承诺！');
                }
            },
        });


        var layarea = layui.layarea;

        var fun = {
            _init: function () {
                var _this = this;
                _this._list();
                _this._save();
                // _this._end();
            },
            _data: {},
            _set: {},
            _list: function () {

                var _this = this;
                _this._data.index = [];
                _this._data.layermsg = layer.msg('正在进行实例化简历.....', {icon: 16, time: 66666});


                $.getJSON('/icenter/resume/form', {'id': _this._data.id}, function (data) {
                    if (data.code != 0) {
                        layer.msg('当前实例化失败，请刷新重试', {icon: 2, time: 2000});
                        return false;
                    }
                    var titleHtml = '';
                    _this._data.id = data.id;
                    _this._data.maxid = data.title[data.title.length - 1].id;
                    _this._data.minid = data.title[0].id;
                    $(":input[name='cid']").val(data.id);
                    for (var i = 0; i < data.title.length; i++) {
                        _this._data.index[i] = data.title[i].id;
                        data.id == data.title[i].id ? _this._data.i = i : '';
                        var ischeck = data.id == data.title[i].id ? 'class="current"' : '';
                        titleHtml += '<li ' + ischeck + ' data-id="' + data.title[i].id + '">' + data.title[i].name + '<em></em><i></i></li>';
                    }
                    //console.log(titleHtml)
                    //console.log(data.form)

                    $('.tab_top').html(titleHtml);
                    $('.content').html(data.form);
                    $('.step_a').html('\<a href="javascript:;" class="save prevstep" >上一步</a><a href="javascript:;" lay-submit class="save nextstep" lay-filter="save">保存并下一步</a>');
                    $('.tab_top li').each(function (index) {
                        if ($(this).hasClass('current')) {
                            if ($(this).index() == 0) {
                                $('.step_a .pre').hide()
                            }
                        }
                    })
                    _this._issubmitshow();
                    _this._layuiIdentity();
                    _this._titleClick();
                    _this._date();
                    _this._upload();
                    _this._order();
                    _this._pre();
                    _this._addTr();
                    _this._reduceTr();
                    _this._inputValSum();
                    _this._orderNumber();
                    _this._radioClicked();
                    _this._select();

                    _this._imgClose();
                    _this._spantext();
                    _this._prevstep();
                    var i = 0;
                    $.each(data.user, function (k, v) {
                        if ($("[name='" + k + "']").is('select')) {
                            $("[name='" + k + "']").parent().children('select').attr("data-value", v);
                            $("[name='" + k + "'] option[value='" + v + "']").attr("selected", "selected");
                        } else if ($("[name='" + k + "']").is('input')) {
                            var type = $("[name='" + k + "']").attr('type');
                            switch (type) {
                                case 'text':
                                    $("[name='" + k + "']").val(v);
                                    break;
                                case 'number':
                                    $("[name='" + k + "']").val(v);
                                    break;
                                case 'radio':
                                case 'checkbox':
                                    $("[name='" + k + "'][value='" + v + "']").prop("checked", true);
                                    break;
                            }
                        } else if ($("[name='" + k + "']").is('textarea')) {
                            if (typeof ($("[name='" + k + "']").attr('data')) != "undefined") {
                                $("[name='" + k + "'][data='layui-editor']").text(v);
                                i++;
                            } else $("[name='" + k + "']").val(v);
                        }
                    })
                    layarea.render({
                        elem: '#area-picker',
                        change: function (res) {
                        }
                    });
                    layarea.render({
                        elem: '#area-picker2',
                        change: function (res) {
                        }
                    });

                    _this._listRender();
                    $('.selfpic').attr('lay-verify', 'errormessage').attr('placeholder', '请上传证件照');
                    $('[name="wwhqywgur0"]').val('{$uinfo["username"]}');
                    $('[name="26jmr8kmsz"]').val('{$uinfo["card"]}');
                    $('[name="qspywhdn8w"]').val('{$uinfo["email"]}');
                    $('[name="2ywi8z97l7"]').val('{$uinfo["mobile"]}');
                    $('[name="2izv9jv928ukw"]').attr('lay-verify', 'errormessage');
                    $('[name="JY_1"]').attr('lay-filter', 'jiaoyu');
                    form.render();
                    _this._xueli();
                    _this._xuewei();
                    _this._yuanxiao();

                });
                layer.close(_this._data.layermsg);
            },
            _layuiIdentity: function () {
                $('.layui-identity').val('{$uinfo["card"]}')
                var identityValue = $('.layui-identity').val();
                var sd = new Date();
                var nowYear = sd.getFullYear();
                if ($(".layui-identity").length > 0) {
                    if (identityValue.length == 18) {
                        var layuiYear = identityValue.substr(6, 4);
                        var layuiMonth = identityValue.substr(10, 2);
                        var layuiDay = identityValue.substr(12, 2);

                        $('.layui-csdate').val(layuiYear + "-" + layuiMonth + "-" + layuiDay);
                        $('.layui-csyear').val(nowYear - layuiYear);
                        var layuiSex = parseInt(identityValue.substr(16, 1)) % 2 == 0 ? "女" : "男";
                        $('.layui-sex input').each(function () {
                            $(this).removeAttr('disabled')
                            if ($(this).val() == layuiSex) {
                                $(this).click();
                            }


                        })
                    } else if (identityValue.length == 15) {
                        birthday = "19" + identityValue.substr(6, 6);
                        var layuiSex2 = parseInt(identityValue.substr(14, 1)) % 2 == 0 ? "男" : "女";

                    }
                }

            },
            _issubmitshow: function () {
                var _this = this;
                if (_this._data.id == _this._data.maxid) $('.nextstep').text('保存');
                else $('.nextstep').text('保存并下一步');
            },
            _order: function () {
                var len = $(".table-list2 tbody tr").length;
                $('.table-list2 tbody tr').each(function (index) {
                    for (var i = 0; i < len; i++) {
                        $(this).children('td:first').text(index + 1);
                    }
                })
            },
            _titleClick: function () {
                var _this = this;
                // $(".tab_top li").on('click',function(){
                // 	_this._data.id=$(this).data('id');
                // 	_this._list();
                // });
            },
            _save: function () {
                var _this = this;
                form.on('submit(save)', function (data) {

                    //全日制学历学历类型
                    var quanrizhitype = data.field.quanrizhitype;
                    //在职学历类型
                    var zaizhitype = data.field.zaizhitype;
                    //全日制学历类型非空校验
                    if(data.field['2htc2bme4k8']=='硕士研究生'||data.field['2htc2bme4k8']=='博士研究生'){
                        if(!data.field['quanrizhitype']){
                            layer.msg('全日制教育学历硕士或博士专业型或科研型不能为空');
                            return false;
                        }
                    }else {
                        quanrizhitype = '';
                    }

                    //在职学历类型非空校验
                    if(data.field['2htc2bme4keq8']=='硕士研究生'||data.field['2htc2bme4keq8']=='博士研究生'){
                        if(!data.field['zaizhitype']){
                            layer.msg('在职教育学历硕士或博士专业型或科研型不能为空');
                            return false;
                        }
                    }else {
                        zaizhitype = '';
                    }

                    var __this = $(this);
                    __this.prop('disabled', true).removeAttr('lay-submit');
                    $.post('/icenter/resume/usersave', {
                        "value": _this._serializeToKeyValue(),
                        "select": _this._serializeSelectToKeyValue(),
                        'title': _this._serializeTitleToKeyValue(),
                        'quanrizhitype':quanrizhitype,
                        'zaizhitype':zaizhitype,
                    }, function (res) {
                        __this.removeAttr('disabled').attr('lay-submit', '');
                        if (!res.status) layer.msg(res.message, {icon: 2, time: 2000});
                        else layer.msg(res.message, {icon: 1, time: 2000}, function () {
                            if (_this._data.i < _this._data.index.length - 1) {
                                // _this._data.id=_this._data.index[_this._data.i++];
                                // console.log(_this._data.i++);
                                // console.log(_this._data.index);
                                _this._data.id = _this._data.index[++_this._data.i];

                                _this._list();
                            } else {
                                location.href = "/icenter/index"
                            }

                        });
                    }, 'JSON');
                });
            },
            _prevstep: function () {
                var _this = this;
                if (_this._data.id == _this._data.minid) $('.prevstep').css('display', 'none');
                else $('.prevstep').css('display', 'block');
                $('.prevstep').click(function () {
                    if (_this._data.i > 0) {
                        _this._data.id = _this._data.index[--_this._data.i];
                        _this._list();
                    }
                })
            },
            _serializeSelectToKeyValue: function () {
                var o = {};
                var _this = this;
                $.each($('.layui-form [data-select="1"]').serializeArray(), function () {
                    // if (o[this.name] !== undefined) {
                    // 	if (!o[this.name].push) {
                    // 		o[this.name] = [o[this.name]];
                    // 	}
                    // 	o[this.name].push(this.value || '');
                    // } else {
                    // 	o[this.name] = this.value || '';
                    // }
                    if (_this._check(this.name)) o[this.name] = this.value || '';
                });
                return o;
            },
            _serializeTitleToKeyValue: function () {
                var _this = this;
                var o = {};
                $.each($('.layui-form [data-select="1"]'), function () {
                    if (_this._check(this.name)) o[this.name] = $(this).data('title');
                });
                return o;
            },
            _serializeToKeyValue: function () {
                var _this = this;
                var o = {};
                $.each($('.layui-form').serializeArray(), function () {

                    if (_this._check(this.name)) {
                        if (o[this.name] !== undefined) {
                            if (!o[this.name].push) {
                                o[this.name] = [o[this.name]];
                            }
                            o[this.name].push(this.value || '');
                        } else {
                            o[this.name] = this.value || '';
                        }
                    }
                });
                o.formhtml = $('.layui-form .content').html();
                return o;
            },
            _check: function (name) {
                //这里判断是否合法
                // var pattern = /^[A-Za-z0-9]+$/;
                var patrn = /[`~!@#$%^&*()\-+=<>?:"{}|,.\/;'\\[\]·~！@#￥%……&*（）——\-+={}|《》？：“”【】、；‘'，。、]/im;

                if (!patrn.test(name)) return true;
                else return false;

            },
            _pre: function () {
                var _this = this;

                $(".pre").on('click', function () {
                    _this._data.id = _this._data.index[(_this._data.i - 1)];
                    _this._list();
                });
            },
            _date: function () {
                var _t = this;
                $(".magicalcoder-laydate").each(function (idx, item) {
                    $(this).removeAttr('lay-key');
                    laydate.render(_t._iteratorAttr({
                        elem: item
                    }, item));
                })
            },
            _iteratorAttr: function (renderConfig, dom) {
                var attrs = dom.attributes;
                for (var i = 0; i < attrs.length; i++) {
                    var attr = attrs[i];
                    var name = attr.name;
                    var value = attr.value;
                    if (name.indexOf("mc-") === 0) {
                        name = name.replace("mc-attr-", '');
                        name = name.replace("mc-event-", '');
                        if (name.indexOf('str-') === 0) {
                            name = name.replace('str-', '');
                        } else if (name.indexOf('bool-') === 0) {
                            name = name.replace('bool-', '');
                            value == 'true' || value === '' ? value = true : value = value;
                            value == 'false' ? value = false : value = value;
                        } else if (name.indexOf('num-') === 0) {
                            name = name.replace('num-', '');
                            if (value !== '' && !isNaN(value)) {
                                value = parseFloat(value);
                            }
                        } else if (name.indexOf('json-') === 0) {
                            name = name.replace('json-', '');
                            if (value !== '') {
                                value = JSON.parse(value);
                            }
                        }
                        renderConfig[this._htmlAttrNameToTuoFeng(name)] = value;
                    }
                }
                return renderConfig;
            },
            _htmlAttrNameToTuoFeng: function (name) {
                var arr = name.split("-");
                var newArr = []
                for (var i = 0; i < arr.length; i++) {
                    if (i != 0) {
                        if (arr[i] != '') {
                            newArr.push(this.firstCharToUpLower(arr[i]));
                        }
                    } else {
                        newArr.push(arr[i]);
                    }
                }
                return newArr.join('');
            },
            _upload: function () {
                var _this = this;
                $(".magicalcoder-layupload").each(function (idx, item) {
                    $(this).text('上传文件');
                    upload.render(_this._iteratorAttr({
                        elem: $(item),
                        accept: 'images',
                        progress: function (n, elem) {
                            var percent = n + '%';
                            layer.msg('上传' + percent + '中，请勿关闭浏览器....', {time: 200000, icon: 16});
                        },
                        done: function (_data) {

                            layer.closeAll();
                            if (_data.code == 0) layer.msg(_data.msg, {icon: 2, time: 2000});
                            else layer.msg(_data.msg, {icon: 1, time: 2000}, function () {

                                var imgLength = $('.layui-img ul li').length;
                                if (imgLength >= imgMax) {
                                    layer.msg('图片最多可上传5张', {icon: 2, time: 2000})
                                    return false
                                } else _this._createText($(item).attr('name'), _data.data.src, item);
                            });
                        }
                    }, item));
                })
            },
            _createText: function (name, url, t) {
                var _this = this, _web = window.location.protocol + "//" + window.location.host, href = _web + url;
                _html = $(t).parent().html();
                if ($(t).parent().attr('id') == 'aupfile') {
                    $(t).parent().prev('input').attr('value', href);
                    $(t).parent().prev('input').removeAttr('style');
                    $(t).parent().css('background', "url(" + href + ")no-repeat center center").css('background-size', 'cover');
                }

                // else $(t).parent().html("<input type='text' class='layui-input' readonly name='"+name+"' value='<a target='_blank' href="+_web+url+">"+_web+url+"</a>' /><button type='button' class='layui-btn clearImg'>重新上传</button>");
                else {
                    var liLength = $('.layui-img ul').find('li').length;
                    $('.layui-img ul').append('<li><input type="hidden" value=' + _web + url + ' hidden name="ZS_' + liLength + '" /><a href=' + _web + url + ' name="ZS_' + liLength + '" target="_blank"><img src=' + _web + url + ' /></a><span class="close-span">✖</span></li>');
                    $(t).parent().find('button').removeAttr('lay-verify');
                }
                _this._clearVal(t, _html);
                _this._imgClose();

            },
            _listRender: function () {
                var _this = this,
                    html = '<button name="' + _this._generateMixed(16) + '" class="magicalcoder-layupload layui-btn" type="button" mc-attr-str-url="/icenter/Resume/layuiUpload">上传文件</button>';
                $('.clearImg').on('click', function () {
                    $('.clearImg').parent().html(html);
                    _this._upload();
                });
            },
            _clearVal: function (_html) {
                var _this = this;
                $('.clearImg').on('click', function () {
                    $('.clearImg').parent().html(_html);
                    _this._upload();
                });
            },
            _imgClose: function () {

                $('.close-span').click(function () {
                    $(this).parent().remove();
                    var imgLength = $('.layui-img ul li').length;
                    if (imgLength >= imgMax) $('.layui-img').prev().find('button').css('display', 'none');
                    else $('.layui-img').prev().find('button').css('display', 'block');

                })
                var imgLength = $('.layui-img ul li').length;
                if (imgLength >= imgMax) $('.layui-img').prev().find('button').css('display', 'none');
            },
            _spantext: function () {
                $('.layui-span').html('添加一项');
            },
            _clearInputValue: function (inputstring, length) {
                var _this = this;
                inputstring.find('div.span-reduce').addClass('span-reduce-block');
                inputstring.find('input[type=text]').val('');
                inputstring.find('input[type=number]').val('');
                inputstring.find('input').each(function () {
                    $(this).attr('name', $(this).attr('name') + '_' + length);
                })
                inputstring.find('td').each(function () {
                    var name_radio = $(this).find("input[type=radio]").eq(0).attr('name');
                })
                inputstring.find('input[type=radio]').removeAttr('checked');
                inputstring.find('input[type=checkbox]').removeAttr('checked');
                inputstring.find('select').each(function () {
                    $(this).attr('name', $(this).attr('name') + '_' + length);
                })
                inputstring.find('select>option').removeAttr('selected');

                return inputstring
            },
            _getMaxnumber: function (_this) {
                var length = _this.parent().find('tbody').children('tr').length;
                var nameArr = _this.parent().find('tbody').children('tr').eq(length - 1).find('input').attr('name');
                var nameArrtwo = _this.parent().find('tbody').children('tr').eq(length - 1).find('select').attr('name');

                if (nameArr == undefined) {
                    return nameArrtwo.split('_').length == 2 ? 1 : parseInt(nameArrtwo.split('_')[nameArrtwo.split('_').length - 1]) + 1;
                } else {
                    return nameArr.split('_').length == 2 ? 1 : parseInt(nameArr.split('_')[nameArr.split('_').length - 1]) + 1;
                }

            },
            _addTr: function () {
                var _this = this;
                $(".layui-span").on('click', function () {
                    var cloneTable = $(this).parent().find('tbody').children('tr').eq(0).clone(true);
                    var cloneTableparent = $(this).parent().find('tbody');
                    var lastTable = _this._clearInputValue(cloneTable, _this._getMaxnumber($(this)));

                    lastTable.appendTo($(this).prev('table'));
                    _this._orderNumber();
                    form.render();

                });
                $(".layui-span2").on('click', function () {
                    var cloneTable = $(this).parent().children('table').find('tr').eq(0).clone(true);
                    var lastTable = _this._clearInputValue(cloneTable, $(this));
                    lastTable.appendTo($(this).prev('table'));
                    _this._orderNumber();
                    form.render();

                });

            },
            _orderNumber: function () {
                var _this = this;
                var lenthree = $('.magicalcoder-laydate').length;
                $('.magicalcoder-laydate').each(function (index) {
                    for (var i = 0; i < lenthree; i++) {
                        $(this).removeAttr('lay-key');
                    }
                })
                _this._date();
            },
            _reduceTr: function () {
                var _this = this;
                var cloneTable = $(this).parents().find('tbody').children('tr').eq(0).clone(true);
                $(".span-reduce").on("click", function () {
                    $(this).parent('td').parent('tr').remove();
                    // _this._clearInputValue(cloneTable,$(this));
                })
            },
            _generateMixed: function (n) {
                var res = '';
                const chars = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
                var _this = this;
                for (var i = 0; i < n; i++) {
                    var id = Math.ceil(Math.random() * 36);
                    res += chars[id];
                }
                return res;
            },

            _select: function () {

                $('.layui-form-label').each(function () {
                    if ($(this).text() == '*最高学历') {
                        for (var i = 0; i < data.xueli.length; i++) {
                            var opc = document.createElement("option");
                            opc.innerHTML = data.xueli[i].value;
                            opc.value = data.xueli[i].name;
                            $(this).next('div').children('select').append(opc);
                        }
                    }
                        // else if($(this).text()=='*民族'){
                        // 	for(var i=0;i<data.nation.length;i++){
                        // 		var opcNation=document.createElement("option");
                        // 		opcNation.innerHTML=data.nation[i].value;
                        // 		opcNation.value=data.nation[i].name;
                        // 		$(this).next('div').children('select').append(opcNation);
                        // 	}
                    // }
                    else if ($(this).text() == '民族') {
                        $(this).next('div').children('select').html('');
                        for (var i = 0; i < data.nation.length; i++) {
                            var opcNation = document.createElement("option");
                            opcNation.innerHTML = data.nation[i].value;
                            opcNation.value = data.nation[i].name;
                            $(this).next('div').children('select').append(opcNation);
                        }
                    }
                        // else if($(this).text()=='*政治面貌'){
                        // 	for(var i=0;i<data.politic.length;i++){
                        // 		var opcPolitic=document.createElement("option");
                        // 		opcPolitic.innerHTML=data.politic[i].value;
                        // 		opcPolitic.value=data.politic[i].name;
                        // 		$(this).next('div').children('select').append(opcPolitic);
                        // 	}
                    // }
                    else if ($(this).text() == '政治面貌') {
                        $(this).next('div').children('select').html('');
                        for (var i = 0; i < data.politic.length; i++) {
                            var opcPolitic = document.createElement("option");
                            opcPolitic.innerHTML = data.politic[i].value;
                            opcPolitic.value = data.politic[i].name;
                            $(this).next('div').children('select').append(opcPolitic);
                        }
                    } else if ($(this).text() == '*技术职称') {
                        for (var i = 0; i < data.politic.length; i++) {
                            var opcPolitic = document.createElement("option");
                            opcPolitic.innerHTML = data.politic[i].value;
                            opcPolitic.value = data.politic[i].name;
                            $(this).next('div').children('select').append(opcPolitic);
                        }
                    }
                })
                for (var i = 0; i < data.professional.length; i++) {
                    var opcprofessional = document.createElement("option");
                    opcprofessional.innerHTML = data.professional[i].value;
                    opcprofessional.value = data.professional[i].name;
                    $('.zhicheng').append(opcprofessional);
                }
                for (var i = 0; i < data.post.length; i++) {
                    var opcpost = document.createElement("option");
                    opcpost.innerHTML = data.post[i].value;
                    opcpost.value = data.post[i].name;
                    $('.zhiwupost').append(opcpost);
                }
                form.on('select(jiaoyu)', function (data) {
                    if (data.value == '高中') {
                        $(data.elem).parent().parent().find('input[placeholder="导师"]').val('无')
                        $(data.elem).parent().parent().find('input[placeholder="专业"]').val('无')
                    } else {
                        $(data.elem).parent().parent().find('input[placeholder="导师"]').val('')
                        $(data.elem).parent().parent().find('input[placeholder="专业"]').val('')
                    }
                });


                form.render();
            },
            _radioClicked: function () {
                $('.layui-form-item2').each(function () {
                    if ($(this).find('.layui-form-radioed').prev().val() == '有') {
                        $(this).parent().next().css('display', 'block');
                    } else if ($(this).find('.layui-form-radioed').prev().val() == '已完成') {
                        $(this).parent().next().css('display', 'block');
                    } else if ($(this).find('.layui-form-radioed').prev().val() == '规培中') {
                        $(this).parent().next().css('display', 'block');
                    } else if ($(this).find('.layui-form-radioed').prev().val() == '是') {
                        $(this).parent().next().css('display', 'block');
                    } else $(this).parent().next().css('display', 'none');
                })
                $('.layui-form-itemyj').on('click', function () {
                    if ($(this).find('.layui-form-radioed').prev().val() == '是') {
                        $('[name="2izv9jv928ukw"]').attr('readonly', 'readonly').removeAttr('lay-verify').val('无');
                    } else {
                        $('[name="2izv9jv928ukw"]').removeAttr('readonly').attr('lay-verify', 'errormessage').val('');
                    }
                })
                $('.layui-form-item2').on('click', function () {
                    if ($(this).find('.layui-form-radioed').prev().val() == '有') {
                        $(this).parent().next().css('display', 'block');
                        $(this).parent().next().find('input').removeAttr('disabled');
                        $(this).parent().next().find('select').removeAttr('disabled');
                    } else if ($(this).find('.layui-form-radioed').prev().val() == '已完成') {
                        $(this).parent().next().css('display', 'block');
                        $(this).parent().next().find('input').removeAttr('disabled');
                        $(this).parent().next().find('select').removeAttr('disabled');
                        $('.guipeisf').show();
                    } else if ($(this).find('.layui-form-radioed').prev().val() == '规培中') {
                        $(this).parent().next().css('display', 'block');
                        $(this).parent().next().find('input').removeAttr('disabled');
                        $(this).parent().next().find('select').removeAttr('disabled');
                        $('.guipeisf').hide()
                    } else if ($(this).find('.layui-form-radioed').prev().val() == '是') {
                        $(this).parent().next().css('display', 'block');
                        $(this).parent().next().find('input').removeAttr('disabled');
                        $(this).parent().next().find('select').removeAttr('disabled');
                    } else {
                        $(this).parent().next().css('display', 'none');
                        $(this).parent().next().find('input').attr('disabled', 'disabled');
                        $(this).parent().next().find('select').attr('disabled', 'disabled');
                        $('.guipeisf').show();
                    }
                })

                $('.layui-form-item3').each(function () {
                    if ($(this).find('.layui-form-radioed').prev().val() == '有') {
                        $(this).parent().find('.qingtian').css('display', 'block');
                    } else if ($(this).find('.layui-form-radioed').prev().val() == '是') {
                        $(this).parent().find('.qingtian').css('display', 'block');
                    } else $(this).parent().find('.qingtian').css('display', 'none')
                })

                $('.layui-form-item3').on('click', function () {
                    if ($(this).find('.layui-form-radioed').prev().val() == '有') {
                        $(this).parent().find('.qingtian').css('display', 'block');
                    } else if ($(this).find('.layui-form-radioed').prev().val() == '是') {
                        $(this).parent().find('.qingtian').css('display', 'block');
                    } else $(this).parent().find('.qingtian').css('display', 'none')
                })
            },
            _inputValSum: function () {
                $(".table-list3 tr td input").blur(function () {
                    $(this).attr('value', $(this).val())
                    $('.table-list3 .tr_one').each(function () {
                        var num1 = parseFloat($(this).children("td").eq(1).children('input').val());
                        var num2 = parseFloat($(this).children("td").eq(2).children('input').val());
                        if (!isNaN(num1) && !isNaN(num2)) {
                            $(this).children("td").eq(3).text((num1 + num2).toFixed(2));
                        }

                        var num3 = parseFloat($(this).children("td").eq(4).children('input').val());
                        var num4 = parseFloat($(this).children("td").eq(5).children('input').val());
                        if (!isNaN(num3) && !isNaN(num4)) {
                            $(this).children("td").eq(6).text((num3 + num4).toFixed(2));
                        }

                        var num5 = parseFloat($(this).children("td").eq(7).children('input').val());
                        var num6 = parseFloat($(this).children("td").eq(8).children('input').val());
                        if (!isNaN(num5) && !isNaN(num6)) {
                            $(this).children("td").eq(9).text((num5 + num6).toFixed(2));
                        }
                    })
                })
                $(".table-list3 tr td ").find('input').attr('type', "number");
            },
            _yuanxiao: function () {
                var zlxuewei1 = $(".zlxuewei1");
                var zlxuewei2 = $(".zlxuewei2");
                zlxuewei1.html('');
                var zlxueweiValue = zlxuewei1.attr('data-value');
                var zlxueweiValueTwo = zlxuewei2.attr('data-value');
                $.each(bigdata.yuanxiao[zlxueweiValue], function (i, index) {
                    var zlopcc1 = document.createElement("option");
                    zlopcc1.innerHTML = index;
                    zlopcc1.value = index;
                    zlxuewei2.append(zlopcc1);
                    zlxuewei2.children("option[value='" + zlxueweiValueTwo + "']").attr("selected", "selected");
                })

                zlxuewei1.innerHTML = " <option value=''>请选择院校省份</option>";
                $.each(bigdata.yuanxiao, function (i, index) {
                    var zlopcc = document.createElement("option");
                    zlopcc.innerHTML = i;
                    zlopcc.value = i;
                    zlxuewei1.append(zlopcc);
                    zlxuewei1.children("option[value='" + zlxueweiValue + "']").attr("selected", "selected");
                })

                form.on('select(zlxuewei1)', function (data) {
                    if (data.value == '国外') $(this).parents('td').children('.layui-block').css('display', 'block');
                    else $(this).parents('td').children('.layui-block').css('display', 'none');
                    zlxuewei2.html('');
                    zlxuewei2.innerHTML = " <option value=''>请选择院校</option>";
                    $.each(bigdata.yuanxiao[data.value], function (i, index) {
                        var zlopcc1 = document.createElement("option");
                        zlopcc1.innerHTML = index;

                        zlopcc1.value = index;
                        zlxuewei2.append(zlopcc1)
                    })
                    form.render();
                })
                form.on('select(zlxuewei2)', function (data) {
                    if (data.value == '其他') $(this).parents('td').children('.layui-block').css('display', 'block');
                    else $(this).parents('td').children('.layui-block').css('display', 'none');
                })
                form.render();
            },
            _xueli: function () {
                var form1 = $("#form1");
                var form2 = $("#form2")
                var form3 = $("#form3");
                var form4 = $("#form4")
                var form5 = $("#form5")
                var form6 = $("#form6");


                form1.html('');
                form2.html('');
                form3.html('');
                form4.html('');
                form5.html('');
                form6.html('');
                form1.prepend("<option value=''>请选择第一学历</option>");
                form2.prepend("<option value=''>请选择专业大类</option>");
                form3.prepend("<option value=''>请选择专业</option>");
                form4.prepend("<option value=''>请选择第一学历</option>");
                form5.prepend("<option value=''>请选择专业大类</option>");
                form6.prepend("<option value=''>请选择专业</option>");

                $('#form2').attr('data-value');
                $('#form3').attr('data-value')
                var valueOne = $('#form1').attr('data-value');
                var valueOneTwo = $('#form2').attr('data-value');
                var valueOneThree = $('#form3').attr('data-value');
                var valueTwo = $('#form4').attr('data-value');
                var valueTwoTwo = $('#form5').attr('data-value');
                var valueTwoThree = $('#form6').attr('data-value');

                $.each(bigdata.xueli, function (i, index) {
                    var opc = document.createElement("option");
                    opc.innerHTML = i;
                    opc.value = i;
                    form1.append(opc);
                    $("#form1 option[value='" + valueOne + "']").attr("selected", "selected");
                })
                $.each(bigdata.xueli[valueOne], function (i, index) {
                    var opc1 = document.createElement("option");
                    opc1.innerHTML = i;
                    opc1.value = i;
                    form2.append(opc1);
                    $("#form2 option[value='" + valueOneTwo + "']").attr("selected", "selected");
                })
                if (valueOneTwo) {
                    $.each(bigdata.xueli[valueOne][valueOneTwo], function (i, index) {
                        var opc2 = document.createElement("option");
                        opc2.innerHTML = index;
                        opc2.value = index;
                        form3.append(opc2);
                        $("#form3 option[value='" + valueOneThree + "']").attr("selected", "selected");
                    })
                }
                $.each(bigdata.xueli, function (i, index) {
                    var oppc = document.createElement("option");
                    oppc.innerHTML = i;
                    oppc.value = i;
                    form4.append(oppc);
                    $("#form4 option[value='" + valueTwo + "']").attr("selected", "selected");
                })
                $.each(bigdata.xueli[valueTwo], function (i, index) {
                    var opc1 = document.createElement("option");
                    opc1.innerHTML = i;
                    opc1.value = i;
                    form5.append(opc1);
                    $("#form5 option[value='" + valueTwoTwo + "']").attr("selected", "selected");
                })
                if (valueTwoTwo) {
                    $.each(bigdata.xueli[valueTwo][valueTwoTwo], function (i, index) {
                        var opc2 = document.createElement("option");
                        opc2.innerHTML = index;
                        opc2.value = index;
                        form6.append(opc2);
                        $("#form6 option[value='" + valueTwoThree + "']").attr("selected", "selected");
                    })
                }


                var isshow = false;
                form.on('select(form1)', function (data) {
                    form2.html('');
                    form3.html('');
                    if (data.value == '无') {
                        $('#form3').html("<option value='无'>无</option>");
                    }
                    if (data.value == '中专及以下') {
                        form3.html("<option value='无'>无</option>");
                    }
                    //console.log(data.elem)
                    //学历板块中：硕士/博士增加必填项：专业型或科研型
                    //console.log(data.value)
                    //console.log(isshow)
                    if (data.value =='硕士研究生' || data.value=='博士研究生') {
                        let quanrizhiStr = `<div class="layui-form-item myeducation-parent">
                                                   <!--<label class="layui-form-label">单选框</label>-->
                                                   <div class="layui-input-block myeducation">
                                                   <input type="radio" name="quanrizhitype" value="专业型" title="专业型">
                                                   <input type="radio" name="quanrizhitype" value="科研型" title="科研型">
                                                   </div>
                                                   </div>`;
                        if (isshow === false) {
                            $(data.elem).parents('.layui-form-item').append(quanrizhiStr);
                            isshow = true;
                        }
                    }else {
                        $(data.elem).parents('.layui-form-item').find('.myeducation-parent').remove();
                        isshow = false
                    }


                    $.each(bigdata.xueli[data.value], function (i, index) {
                        var opc1 = document.createElement("option");
                        opc1.innerHTML = i;
                        opc1.value = i;
                        form2.append(opc1)
                    })
                    crea(bigdata.xueli[data.value], data.value);
                    form.render();
                })
                var dataxueli = $('#form1').find("option[selected='selected']").val();

                function crea(array, value) {
                    form.on('select(form2)', function (data) {
                        if (data.value == '其他') $(this).parents('td').children('.layui-block').css('display', 'block');
                        else $(this).parents('td').children('.layui-block').css('display', 'none');
                        form3.html('');
                        $.each(bigdata.xueli[value][data.value], function (i, index) {
                            var opc2 = document.createElement("option");
                            opc2.innerHTML = index;
                            opc2.value = index;
                            form3.append(opc2);
                        })
                        form.render();
                    })
                }

                crea(bigdata.xueli[dataxueli], dataxueli);
                form.on('select(form3)', function (data) {
                    if (data.value == '其他') $(this).parents('td').children('.layui-block').css('display', 'block');
                    else $(this).parents('td').children('.layui-block').css('display', 'none');
                })

                var isshow4 = false;
                form.on('select(form4)', function (data) {
                    form5.html('');
                    form6.html('');
                    if (data.value == '无') {
                        $('#form6').html("<option value='无'>无</option>");
                    }
                    if (data.value == '中专及以下') {
                        form6.html("<option value='无'>无</option>");
                    }


                    if (data.value =='硕士研究生' || data.value=='博士研究生') {
                        let quanrizhiStr = `<div class="layui-form-item  myeducation-parent">
                                                   <!--<label class="layui-form-label">单选框</label>-->
                                                   <div class="layui-input-block myeducation">
                                                   <input type="radio" name="zaizhitype" value="专业型" title="专业型">
                                                   <input type="radio" name="zaizhitype" value="科研型" title="科研型">
                                                   </div>
                                                   </div>`;

                        if (isshow4 === false) {
                            $(data.elem).parents('.layui-form-item').append(quanrizhiStr);
                            isshow4 = true;
                            //console.log(isshow4)
                        }
                    }else {
                        $(data.elem).parents('.layui-form-item').find('.myeducation-parent').remove();
                        isshow4 = false;
                        //console.log($(data.elem).parents('.layui-form-item').find('.myeducation-parent'))
                    }

                    $.each(bigdata.xueli[data.value], function (i, index) {
                        var opc4 = document.createElement("option");
                        opc4.innerHTML = i;
                        opc4.value = i;
                        form5.append(opc4)
                    })
                    creat(bigdata.xueli[data.value], data.value);
                    form.render();
                })
                var dataxueli2 = $('#form4').find("option[selected='selected']").val();

                function creat(array, value) {
                    form.on('select(form5)', function (data) {
                        if (data.value == '其他') $(this).parents('td').children('.layui-block').css('display', 'block');
                        else $(this).parents('td').children('.layui-block').css('display', 'none');
                        form6.html('');
                        $.each(bigdata.xueli[value][data.value], function (i, index) {
                            var opc5 = document.createElement("option");
                            opc5.innerHTML = index;
                            opc5.value = index;
                            form6.append(opc5);
                        })
                        form.render();
                    })
                }

                creat(bigdata.xueli[dataxueli2], dataxueli2);
                form.on('select(form6)', function (data) {
                    if (data.value == '其他') $(this).parents('td').children('.layui-block').css('display', 'block');
                    else $(this).parents('td').children('.layui-block').css('display', 'none');
                })

                form.render();

            },
            _xuewei: function () {
                var xuewei1 = $(".xuewei1");
                var xuewei2 = $(".xuewei2");
                var xuewei3 = $(".xuewei3");
                var xuewei4 = $(".xuewei4");
                var xuewei5 = $(".xuewei5");
                var xuewei6 = $(".xuewei6");


                xuewei1.html('');
                xuewei2.html('');
                xuewei3.html('');
                xuewei4.html('');
                xuewei5.html('');
                xuewei6.html('');
                xuewei1.prepend("<option value=''>请选择第一学位</option>");
                xuewei2.prepend("<option value=''>请选择院校省份</option>");
                xuewei3.prepend("<option value=''>请选择毕业院校</option>");
                xuewei4.prepend("<option value=''>请选择第一学位</option>");
                xuewei5.prepend("<option value=''>请选择院校省份</option>");
                xuewei6.prepend("<option value=''>请选择毕业院校</option>");

                var xueweiValue = xuewei1.attr('data-value');
                var xueweiOneTwo = xuewei2.attr('data-value');
                var xueweiOneThree = xuewei3.attr('data-value');
                var xueweiValue2 = xuewei4.attr('data-value');
                var xueweiTwoTwo = xuewei5.attr('data-value');
                var xueweiTwoThree = xuewei6.attr('data-value');

                $.each(bigdata.xuewei, function (i, index) {
                    var opcc = document.createElement("option");
                    opcc.innerHTML = i;
                    opcc.value = i;
                    xuewei1.append(opcc);
                    xuewei1.children("option[value='" + xueweiValue + "']").attr("selected", "selected");
                })

                $.each(bigdata.xuewei[xueweiValue], function (i, index) {
                    var opca1 = document.createElement("option");
                    opca1.innerHTML = i;
                    opca1.value = i;
                    xuewei2.append(opca1);
                    xuewei2.children("option[value='" + xueweiOneTwo + "']").attr("selected", "selected");
                })
                if (xueweiOneTwo) {
                    $.each(bigdata.xuewei[xueweiValue][xueweiOneTwo], function (i, index) {
                        var opcb2 = document.createElement("option");
                        opcb2.innerHTML = index;
                        opcb2.value = index;
                        xuewei3.append(opcb2);
                        // $("#xuewei3 option[value='"+xueweiOneThree+"']").attr("selected","selected");
                        xuewei3.children("option[value='" + xueweiOneThree + "']").attr("selected", "selected");
                    })
                }

                $.each(bigdata.xuewei, function (i, index) {
                    var opcc = document.createElement("option");
                    opcc.innerHTML = i;
                    opcc.value = i;
                    xuewei4.append(opcc);
                    xuewei4.children("option[value='" + xueweiValue2 + "']").attr("selected", "selected");
                })
                $.each(bigdata.xuewei[xueweiValue2], function (i, index) {
                    var opcd1 = document.createElement("option");
                    opcd1.innerHTML = i;
                    opcd1.value = i;
                    xuewei5.append(opcd1);
                    // $("#xuewei5 option[value='"+xueweiTwoTwo+"']").attr("selected","selected");
                    xuewei5.children("option[value='" + xueweiTwoTwo + "']").attr("selected", "selected");
                })
                if (xueweiTwoTwo) {
                    $.each(bigdata.xuewei[xueweiValue2][xueweiTwoTwo], function (i, index) {
                        var opcf2 = document.createElement("option");
                        opcf2.innerHTML = index;
                        opcf2.value = index;
                        xuewei6.append(opcf2);
                        // $("#xuewei6 option[value='"+xueweiTwoThree+"']").attr("selected","selected");
                        xuewei6.children("option[value='" + xueweiTwoThree + "']").attr("selected", "selected");
                    })
                }
                form.on('select(xuewei1)', function (data) {
                    xuewei2.html('');
                    xuewei3.html('');
                    if (data.value == '无') {
                        $('.xuewei2').attr('lay-verify', '');
                        $('.xuewei3').attr('lay-verify', '');
                        xuewei3.html("<option value='无'>无</option>");
                    }

                    $.each(bigdata.xuewei[data.value], function (i, index) {
                        var opcc1 = document.createElement("option");
                        opcc1.innerHTML = i;
                        opcc1.value = i;
                        xuewei2.append(opcc1)
                    })
                    creaC(bigdata.xuewei[data.value], data.value);
                    form.render();
                })
                var datavalue = $('.xuewei1').find("option[selected='selected']").val();

                function creaC(array, value) {
                    form.on('select(xuewei2)', function (data) {
                        if (data.value == '国外') $(this).parents('td').children('.layui-block').css('display', 'block');
                        else $(this).parents('td').children('.layui-block').css('display', 'none');
                        xuewei3.html('');
                        $.each(bigdata.xuewei[value][data.value], function (i, index) {
                            var opcc2 = document.createElement("option");
                            opcc2.innerHTML = index;
                            opcc2.value = index;
                            xuewei3.append(opcc2);
                        })
                        form.render();
                    })
                }

                creaC(bigdata.xuewei[datavalue], datavalue);
                form.on('select(xuewei3)', function (data) {
                    // if(data.value=='其他') $(this).parents('td').children('.layui-block').css('display','block');
                    // else $(this).parents('td').children('.layui-block').css('display','none');
                    if (data.value == '其他') $(this).parents('td').children('.layui-block').css('display', 'block');
                    else $(this).parents('td').children('.layui-block').css('display', 'none');
                })

                form.on('select(xuewei4)', function (data) {
                    xuewei5.html('');
                    xuewei6.html('');
                    if (data.value == '无') {
                        $('.xuewei5').attr('lay-verify', '');
                        $('.xuewei6').attr('lay-verify', '');
                        xuewei6.html("<option value='无'>无</option>");
                    }

                    $.each(bigdata.xuewei[data.value], function (i, index) {
                        var opcc4 = document.createElement("option");
                        opcc4.innerHTML = i;
                        opcc4.value = i;
                        xuewei5.append(opcc4)
                    })
                    creaa(bigdata.xuewei[data.value], data.value);
                    form.render();
                })
                var datavalue2 = $('.xuewei4').find("option[selected='selected']").val();

                function creaa(array, value) {
                    form.on('select(xuewei5)', function (data) {
                        if (data.value == '国外') $(this).parents('td').children('.layui-block').css('display', 'block');
                        else $(this).parents('td').children('.layui-block').css('display', 'none');
                        xuewei6.html('');
                        $.each(bigdata.xuewei[value][data.value], function (i, index) {
                            var opcc5 = document.createElement("option");
                            opcc5.innerHTML = index;
                            opcc5.value = index;
                            xuewei6.append(opcc5);
                        })
                        form.render();
                    })
                }

                creaa(bigdata.xuewei[datavalue2], datavalue2);

                form.on('select(xuewei6)', function (data) {
                    if (data.value == '其他') $(this).parents('td').children('.layui-block').css('display', 'block');
                    else $(this).parents('td').children('.layui-block').css('display', 'none');
                })


                form.render();
            }

        };
        fun._init();
    });
</script>
</body>
</html>
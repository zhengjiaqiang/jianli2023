//id:绑定数据的列名，cl:class名称 sort:是否排序
function __i(id,cl,sort){
    if(typeof(cl) == 'number'){
        var cls = ['center',"center hidden-480"];
        cl = cls[cl];
    }
    return { "mData": id, "sClass": cl,"bSortable": sort};
}
$(":input[name='link']").parent().parent().addClass('hide');
$(":checkbox[name='islink']").is(':checked')== false  ? '' : $(":input[name='link']").parent().parent().removeClass('hide');
$(":checkbox[name='islink']").on('click',function(){
    if($(this).is(':checked')){
        $(":input[name='link']").parent().parent().removeClass('hide')
    }else{
        $(":input[name='link']").parent().parent().addClass('hide')
    }
}); 

$(function(){
     if (window.history && window.history.pushState) {
        $(window).on('popstate', function () {
            window.history.pushState('forward', null, ''); 
            window.history.forward(1);
        });
    }
    window.history.pushState('forward', null, '');  //在IE中必须得有这两行
    window.history.forward(1);
})
function $parent(){
    if(parent == window) return window;
    var qian = parent,hou = window;

    while(qian != hou){ 
        hou = qian;
        qian = qian.parent;
    }
    return qian;
}
function get_obj_val(arr,key,def){
    if(!def) def ='';
    if(arr[key]) return arr[key]
    else return def
}
/**      
 * 对Date的扩展，将 Date 转化为指定格式的String      
 * 月(M)、日(d)、12小时(h)、24小时(H)、分(m)、秒(s)、周(E)、季度(q) 可以用 1-2 个占位符      
 * 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)      
 * eg:      
 * (new Date()).pattern("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423      
 * (new Date()).pattern("yyyy-MM-dd E HH:mm:ss") ==> 2009-03-10 二 20:09:04      
 * (new Date()).pattern("yyyy-MM-dd EE hh:mm:ss") ==> 2009-03-10 周二 08:09:04      
 * (new Date()).pattern("yyyy-MM-dd EEE hh:mm:ss") ==> 2009-03-10 星期二 08:09:04      
 * (new Date()).pattern("yyyy-M-d h:m:s.S") ==> 2006-7-2 8:9:4.18      
 */        
Date.prototype.pattern=function(fmt) {         
    var o = {         
    "M+" : this.getMonth()+1, //月份         
    "d+" : this.getDate(), //日         
    "h+" : this.getHours()%12 == 0 ? 12 : this.getHours()%12, //小时         
    "H+" : this.getHours(), //小时         
    "m+" : this.getMinutes(), //分         
    "s+" : this.getSeconds(), //秒         
    "q+" : Math.floor((this.getMonth()+3)/3), //季度         
    "S" : this.getMilliseconds() //毫秒         
    };         
    var week = {         
    "0" : "/u65e5",         
    "1" : "/u4e00",         
    "2" : "/u4e8c",         
    "3" : "/u4e09",         
    "4" : "/u56db",         
    "5" : "/u4e94",         
    "6" : "/u516d"        
    };         
    if(/(y+)/.test(fmt)){         
        fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));         
    }         
    if(/(E+)/.test(fmt)){         
        fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "/u661f/u671f" : "/u5468") : "")+week[this.getDay()+""]);         
    }         
    for(var k in o){         
        if(new RegExp("("+ k +")").test(fmt)){         
            fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));         
        }         
    }         
    return fmt;         
}   
 

// bootstrap响应式导航条
;(function($, window, undefined) {
    // outside the scope of the jQuery plugin to
    // keep track of all dropdowns
    var $allDropdowns = $();
    // if instantlyCloseOthers is true, then it will instantly
    // shut other nav items when a new one is hovered over
    $.fn.dropdownHover = function(options) {

        // the element we really care about
        // is the dropdown-toggle's parent
        $allDropdowns = $allDropdowns.add(this.parent());

        return this.each(function() {
            var $this = $(this).parent(),
                defaults = {
                    delay: 300,
                    instantlyCloseOthers: true
                },
                data = {
                    delay: $(this).data('delay'),
                    instantlyCloseOthers: $(this).data('close-others')
                },
                options = $.extend(true, {}, defaults, options, data),
                timeout;

            $this.hover(function() {
                if(options.instantlyCloseOthers === true)
                    $allDropdowns.removeClass('open');

                window.clearTimeout(timeout);
                $(this).addClass('open');
            }, function() {
                timeout = window.setTimeout(function() {
                    $this.removeClass('open');
                }, options.delay);
            });
        });
    };
    $.fn.dropdownClick = function(options){
        return this.each(function(){
             var $this = $(this).parent().find('.dropdown-menu'),
                $p = $(this).parent(),
                locthis = $(this);
             $this.on('click','li',function(){
                 locthis.data('value',$(this).data('value'));
                 var tem = locthis.children();
                 locthis.text($(this).text()).append(tem);
                 tem =null;
                 $p.find('input').val($(this).data('value')).data('dw',$(this).text());
                 $($this).find('li').removeClass('active');
                 $(this).addClass('active');
                 $(this).trigger('autoE');
             });
        });
    }

    $('[data-click="dropdown"]').each(function(){
        var _lis = $(this).parent().find('.dropdown-menu li'),
        $p = $(this).parent(),
        _active = _lis.eq(0),
        _this = $(this);
        _lis.each(function(){
            if($(this).hasClass('active')){
                _active = $(this);
            }
        });
        $p.find('input').val(_active.data('value')).data('dw',_active.text());
        var tem = _this.children();
        _this.text(_active.text()).append(tem);
        _active.addClass('active');
    });



    $('[data-hover="dropdown"]').dropdownHover();
    $('[data-click="dropdown"]').dropdownClick();

})(jQuery, this);
$(function(){
    $('.table-responsive table th input:checkbox').on('click' , function(){
        var that = this;
        $(this).closest('table').find('tr > td:first-child input:checkbox')
        .each(function(){
            this.checked = that.checked;
            if(that.checked == true){
                $(this).closest('tr').addClass('selected');
            }else{$(this).closest('tr').removeClass('selected');}
        });
            
    });
    $('.table-responsive table td input:checkbox').on('click' , function(){
        if(this.checked)  $(this).closest('tr').addClass('selected');
        else $(this).closest('tr').removeClass('selected');
    });
    $("#highsearchbtn").on('click',function(){
        $('html,body').scrollTop(0);
        var icon = $(this).find('.icon');
        if(icon.hasClass('icon-angle-down')){
            icon.removeClass('icon-angle-down').addClass('icon-angle-up');
            $("#search-form").slideDown();
        }else{
            $("#search-form").slideUp();
            icon.removeClass('icon-angle-up').addClass('icon-angle-down');
        }
    });
    //快捷输入区事件
    var quitbind = function(){
        $('[role="searchform"]')[0].reset();
        $('[role-quit="true"]').each(function(){
            // console.log();
            var $goel = $('[name="'+$(this).data('bind')+'"]');
            $goel.val($(this).val());
            if($(this).data('dw')){ 
                 var $bx = $goel.next(),tem = $bx.children();
                 $bx.text($(this).data('dw')).append(tem);
            }
        });//
    }

    $('[role-click="search-quit-button"]').on('click',function(){
   
        quitbind();
        $('[role="searchform"]').submit();
    });
    $('[role-keydown="search-quit-text"]').on('keydown',function(e){
        var ev = document.all ? window.event : e;
        if(ev.keyCode==13){ 
            quitbind();
            $('[role="searchform"]').submit();
            return false;
       }
    });

    $(".role-obj").each(function(){
        $($(this).attr('role-obj')).on('autoE', function(event,arg1,arg2) {
            quitbind();
            $('[role="searchform"]').submit();
        });
    });
    
    $('[role="searchform"]').submit(function(){
        window.loadTable.fnPageChange(0);   
        return false;
    });
    $('.nav-list').on('click','li',function(){ 
        if($(this).children('ul').length == 0){
            $('.nav-list li').removeClass('curr').removeClass('active');
            $(this).addClass('active');
            $(this).parents('li').each(function(){
                if($(this).parent().hasClass('nav-list'))$(this).addClass('curr');
            });
        }
    });
    var theme_cookie = $.cookie('theme_cookie');
    $('.theme-select a').each(function(){
        var cr = $(this).data('color');
        if(cr == theme_cookie){
            $('body').removeClass('default-skin');
            $('.theme-select i').removeClass('icon-ok');
            $(this).find('i').addClass('icon-ok');
            if($('body').hasClass(theme_cookie) == false){
                $('body').addClass(theme_cookie);
            }
        }
        $(this).click(function(){
            var theme_cookie = $.cookie('theme_cookie');
            var cr = $(this).data('color');
            $('.theme-select a i').removeClass('icon-ok');
            $(this).find('i').addClass('icon-ok');
            $('body').removeClass(theme_cookie);
            $('body').addClass(cr);
            $.cookie('theme_cookie', cr, { expires: 30 }); 
            theme_cookie =cr;
        });
    });
	$(".allow-image-path").each(function(){
		if($(this).val() != ''){
			var pvObj = $(this).parent().parent();
		    pvObj.prev('.image-prve').remove();	
            pvObj.before('<div class="form-group image-prve"><label class="col-sm-3 control-label no-padding-right"> 预览 </label><div class="col-sm-9"><a><img src="'+$(this).val()+'" style="max-height:100px;max-width:500px;"></a></div></div>');
		}
	});
    $(".dropdown-menu").each(function(){
        if($(this).find('li').length ==0){
            $(this).parent().remove();
        }
	});
    
    $('#layer-open-parent').on('click','.layer-open-cool',function(){ 
        var $this = $(this),_win = window,isend = true;
        var layerid = parent.layer.open({
            skin:'layer-skin',
            type: 2,
            title: $this.data('title') ? $this.data('title') : $this.text(),
            closeBtn: 1, //不显示关闭按钮
            shade: [0.4,'#fff'],
            shadeClose: false,
            area: [$this.data('w'), $this.data('h')],
            maxmin: $this.data('max') | false,
            content: $this.attr('shref'), //iframe的url，no代表不显示滚动条
            end:function(){
                parent.window.layerReleaseFun(true);
            }
        });
        parent.window.layerReleaseFun = function(r){
            switch(typeof(r)){
                case 'function':
                   r(_win,$($($this).data('bind')));
                   break;
                case 'boolean':_win.loadTable.fnDraw(false); break;
                case 'string':
                   if(r =='re')_win.location.href=_win.location.href;
                   else _win.location.href=r;break;
            }
            parent.layer.close(layerid);
            parent.window.layerReleaseFun = null;
       }
    });
});
if(typeof MCMS == "undefined"){
 var MCMS = {};
}

MCMS.validate = function(frm,r){
	return $(frm).validate({
		debug:false,
		rules:r,
		messages: r,
		errorPlacement: function(error, element){
			 $(element).addClass('error');
            //  if(element[0].name== 'file'){
            //    $(':input[name="file"]').removeClass('error');
            //       return true;
            //  }
		}   
	});
}
MCMS.alert = function(options){
    var defaults = {'msg':null,'id':'id-errer-alert','class':'fixed alert-danger no-margin'};
    if(typeof(options) == 'string'){
        defaults.msg = options;
        options = defaults;
    }else{ options = jQuery.extend(defaults, options);}

    var html ='<div id="'+options.id+'" class="alert '+options.class+'  fade in" role="alert">\
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>\
			<p>'+options.msg+'</p></div>';
    $('body').children().eq(0).before(html);
    $('html,body').animate({scrollTop: '0px'}, 100);
    setTimeout(function(){
        $('#'+options.id).slideUp('normal',function(){
            $(this).alert('close');
        });
    },2500);
}
MCMS.commajaxfun = function(self,fun){
	self = $(self);
    btn = $(self).find('[role=submit]');
	if(! self.valid()){
       if( $(self).data('alert') != 'hide'){
		    MCMS.alert('提交数据格式不符合，请检查您的输入！');
        }
		return false;
	}
    btn.button('loading');
	$.ajax({
		type: "POST",dataType:'json',cache: false,
		url: self.attr("action"),
		data: self.serialize(),
		success: function(obj){
			if(typeof(fun) == 'function')fun(obj);
			else{
				if(obj.status){
                    MCMS.alert({'msg':obj.info,'class':'alert-success no-margin'});
                    $('html,body').animate({scrollTop: '0px'}, 500,function(){
                        if(parent.window.layerReleaseFun){
                            parent.window.layerReleaseFun(typeof(fun) == 'boolean' && fun == true ? true : null);
                        }else{
                            if(obj.url)  window.location.href = obj.url;
                        }
                    });
				}else {
					MCMS.alert(obj.info);
				}
			}
		},error: function(){
			MCMS.alert('提交失败，您可以刷新页面后尝试！');
		},complete:function(){
			btn.button('reset');
		}
	});
}
MCMS.setTitle =function(opr){
    var html ='',title='管理中心',name='';
    $.each(opr, function(n, obj) {
        title = obj.title+'-'+title;
        name = obj.title;
        if(obj.url){
            html +='<li><a href="'+obj.url+'" target="mainFrame">'+obj.title+'</a></li>';
        }else{
            html +='<li class="active">'+obj.title+'</li>';
        }
    });
    $("title").html(title);
    parent.jQuery("#id-loc-index").nextAll().remove();
    parent.jQuery("#id-loc-index").after(html);
    parent.jQuery("#id-loc-name").text(name);
}
MCMS.table = {};
MCMS.table.oprHtml = function(action,id,role){
    var _h='',df =  {'reset':false,'del':true,'up':true,'is_sys':false};
    if(typeof(role) == 'object'){
         jQuery.extend(df, role);
    }
    //if(df.up){
        _h += '<a class="green" title="编辑" href="'+action+'/edit?id='+id+'"><i class="icon-pencil bigger-130"></i>  编辑</a>';
    //}
    if(df.is_sys ==0  &&  df.del == 1){
        if(df.reset) _h += '<a class="red redata" ';
        else _h += '<a class="red " ';
        _h += ' title="删除" href="javascript:void(0);" data-id="'+id+'" role="delete" data-stitle="确定删除当前数据？"  data-loading-text="删除中..."><i class="icon-trash bigger-130"></i>  删除</a>';
    }
    return '<div class="action-buttons">'+_h+'</div>'
}

MCMS.table.status = function(data){
    return '<div class="status-icon"><i class="icon icon-'+(data=='1' ? 'ok':'remove')+'"></i></div>';
}
MCMS.table.lables = function(options){//class = arrowed arrowed-in arrowed-right arrowed-in-right
    var defaults = {'msg':'','ctype':'default','class':'','islevel':false,'level':null};
    if(typeof(options) == 'string'){
        defaults.msg = options;
        options = defaults;
    }else{ options = jQuery.extend(defaults, options);}
    var jsontype={//text,type,css
            'default':'default',
            'success':'label-success',
            'warning':'label-warning',
            'danger':'label-danger',
            'info':'label-info',
            'inverse':'label-inverse'
        }
        ,level = {
                    '0':['未','default'],'1':['一级','success'],
                    '2':['二级','success'],'3':['三级','warning'],
                    '4':['四级','danger'],'5':['五级','danger'],
                    '6':['六级','info'],'7':['七级','info'],
                    '8':['八级','inverse'],'9':['九级','inverse']};
    if(options.islevel === true){
        var _lc = level[options.level];
        options.msg = _lc[0]+options.msg;
        options.ctype=_lc[1];
    }
    return '<span class="label '+jsontype[options.ctype]+' '+options.class+'">'+options.msg+'</span>';
}
MCMS.table.checkbox = function(data){
    return '<label><input  name="chkItem[]" type="checkbox" value="' + data + '" class="ace" /><span class="lbl"></span></label>';
}
MCMS.table.pattern = function(timestamp,frm){
    var jsdate=new Date(timestamp*1000);
    return jsdate.pattern(frm);
}
MCMS.table.sEcho = 0;
MCMS.EditUrl = null;
MCMS.table.Init = function(onePageNum,url,aoColumns,aoColumnDefs,option){
    var bp = true,bi=true,aw=false;
    var parms = {
        "bServerSide":true,"bProcessing":true,
        "iDisplayLength": onePageNum,
        "sAjaxSource": url,
        "fnServerData":function(sSource, aoData, fnCallback){
            MCMS.table.sEcho++;
            var parms =$('[role="searchform"]').serializeArray();

            parms.push({'name':'sEcho','value':MCMS.table.sEcho});
            parms.push({'name':'page','value':aoData[3].value / aoData[4].value +1});
            for(var i = 0 ;i<parms.length;i++) { //remove empty
                if(parms[i].value == "" || typeof(parms[i].value) == "undefined") {
                    parms.splice(i,1);
                    i= i-1;
                }
            }
            if($('.sorting_desc').length>0){
                parms.push({'name':'sort','value':$('.sorting_desc').attr('desc')});
            }else if($('.sorting_asc').length>0){
                parms.push({'name':'sort','value':$('.sorting_asc').attr('asc')});
            }
            $.ajax({
                type: "get",
                url: sSource,
                dataType:"json",
                data:parms,
                success: function(o,p,q){
                    if(o && o.REQUEST_URI) MCMS.EditUrl=encodeURIComponent(o.REQUEST_URI);
                    fnCallback(o,p,q);
                } 
            });
        },
        "aoColumns":aoColumns,
        "aoColumnDefs": aoColumnDefs
   };
    if(option){
        jQuery.extend(parms, option);
        if(! parms.bInfo){
            parms.bPaginate = false;
            parms.sDom = "rtip"; 
        }
    }

    window.loadTable = $('#main-table-frist').dataTable(parms);
}

MCMS.btnEvent =function(actionUrl){
    var table =$("#main-table-frist")
    ,_fm = table.closest('form')
	,ajaxp = function(_fd,callfun){
		var self = $("#main-table-frist");
		if(_fd === null)_fd = _fm.serialize();
		$.post(actionUrl, _fd, function(data){return callfun(data);}, "json");
		return false;
	}
	,selectyz = function(){
        var chks = $("[name='chkItem[]']:checked");
        if (chks.length < 1) {
            MCMS.alert("您至少选择一项来执行操作！"); return false;
        }
        return true;
	}
    table.on('click','[role="event-btn"]',function(){
        var _this = $(this);
		$parent().layer.confirm('是否继续执行“'+ $.trim(_this.text()) +'”操作', {icon: 3, title:'提示'}, function(index){
            ajaxp({'event':_this.data('e'),'step':_this.data('step'),'changestatus':_this.data('status'),'chkItem':_this.data('value')},function(data){
                if(data.status ==1) window.loadTable.fnDraw(false);
                else if(data.status ==3) location.href=data.url;
                else MCMS.alert(data.info);
                btn.button('reset');
            });
            $parent().layer.close(index);
		});
		return false;
    });

    table.on('click','[role="delete"]',function(){
        var _this = $(this),_title=_this.data('stitle');
		$parent().layer.confirm(_title, {icon: 3, title:'提示'}, function(index){
            _this.button('loading');
            ajaxp({'event':'delete','chkItem':$(_this).data('id')},function(data){
                if(data.status){ 
                    if(_this.hasClass('redata')){
                        window.loadTable.fnDraw(false);
                    }else _this.closest('tr').remove();
                }
                else MCMS.alert(data.info);
                _this.button('reset');
            });
            $parent().layer.close(index);
		});
		return false;
    });
    $('[role-e="delete"]').on('click',function(){
        if(!selectyz())return true;
        var _this = $(this),_title=_this.data('stitle');
        $('[name="event"]').val('delete');
		$parent().layer.confirm(_title, {icon: 3, title:'提示'}, function(index){
            _this.button('loading');
            ajaxp(null,function(data){
                if(data.status){ 
                    window.loadTable.fnDraw(false);
                }else MCMS.alert(data.info);
                _this.button('reset');
            });
            $parent().layer.close(index);
		});
		return false;
    });
    
    $('[role-e="order"]').on('click',function(){
		var _this = $(this)
            ,chks = $("[name='chkItem[]']:checked");
        _fm.find('[name="event"]').val('sort');
		$parent().layer.confirm('确定要重新进行排序吗？', {icon: 3, title:'提示'}, function(index){
            _this.button('loading');
            ajaxp(null,function(data){
                if(data.status){
                     window.loadTable.fnDraw(false);
                }
                else MCMS.alert(data.info);
                _this.button('reset');
            });
            $parent().layer.close(index);
		});
    });
    $('[role="event-list"] a').on('click',function(){
		var _this = $(this)
            ,btn = _this.closest('.dropdown-menu').prev('.btn');
        _fm.find('[name="event"]').val(_this.data('e'));
		if(_this.hasClass('no-select') == false && !selectyz())return true;
		$parent().layer.confirm('是否继续执行“'+ $.trim(_this.text()) +'”操作', {icon: 3, title:'提示'}, function(index){
            btn.button('loading');
            ajaxp(null,function(data){
                if(data.status ==1) window.loadTable.fnDraw(false);
                else if(data.status ==3) location.href=data.url;
                else MCMS.alert(data.info);
                btn.button('reset');
            });
            $parent().layer.close(index);
		});
    });
    $('[role="event-value-list"] a').on('click',function(){
		var _this = $(this)
            ,btn = _this.closest('.dropdown-menu').prev('.btn');
            _fm.find('[name="event"]').val('changestatus');
            _fm.find('[name="value"]').val(_this.data('value'));
		if(_this.hasClass('no-select') == false && !selectyz())return true;
		$parent().layer.confirm('是否继续执行“'+ $.trim(_this.text()) +'”操作', {icon: 3, title:'提示'}, function(index){
            btn.button('loading');
            // var _fm=$("#main-table-frist").closest('form').serialize();
            // _fm['event']='changestatus';
            // _fm['value']=_this.data('value');
            ajaxp(null,function(data){
                if(data.status ==1) window.loadTable.fnDraw(false);
                else if(data.status ==3) location.href=data.url;
                else MCMS.alert(data.info);
                btn.button('reset');
            });
            $parent().layer.close(index);
		});
    });
    $('[role-e="word"]').on('click',function(){
		var _this = $(this)
            ,btn = _this.closest('.dropdown-menu').prev('.btn'),title=_this.data('stitle');
        _fm.find('[name="event"]').val(_this.data('e'));
		// if(_this.hasClass('no-select') == false && !selectyz())return true;
		$parent().layer.confirm('是否继续执行“'+ $.trim(title) +'”操作', {icon: 3, title:'提示'}, function(index){
            btn.button('loading');
            ajaxp(null,function(data){
                if(data.status)window.loadTable.fnDraw(false);
                else MCMS.alert(data.info);
                btn.button('reset');
            });
            $parent().layer.close(index);
		});
    });
    $('[role="event-btn"]').on('click',function(){
		var _this = btn = $(this)
        _fm.find('[name="event"]').val(_this.data('e'));
		if(_this.hasClass('no-select') == false && !selectyz())return true;
		$parent().layer.confirm('是否继续执行“'+ $.trim(_this.text()) +'”操作', {icon: 3, title:'提示'}, function(index){
            btn.button('loading');
            ajaxp(null,function(data){
                if(data.status ==1) window.loadTable.fnDraw(false);
                else if(data.status ==3) location.href=data.url;
                else MCMS.alert(data.info);
                btn.button('reset');
            });
            $parent().layer.close(index);
		});
    });
    $('[role-e="sendemail"]').on('click',function(){
		var _this = $(this)
            ,btn = _this.closest('.dropdown-menu').prev('.btn'),title=_this.data('stitle');
        _fm.find('[name="event"]').val('sendemail');
		if(_this.hasClass('no-select') == false && !selectyz())return true;
        btn.button('loading');
        ajaxp(null,function(data){
                $parent().layer.open({
                skin:'layer-skin',
                type: 2,
                title: '发送简历',
                closeBtn: 1, //不显示关闭按钮
                shade: [0.4,'#fff'],
                shadeClose: false,
                area: ['500px','450px'],
                maxmin:  false,
                content: data.url,
                end:function(){
                    window.loadTable.fnDraw(false);
                }
            });
        });
    });
    $('[role-e="notice"]').on('click',function(){
		var _this = $(this)
            ,btn = _this.closest('.dropdown-menu').prev('.btn'),title=_this.data('stitle');
        _fm.find('[name="event"]').val('notice');
		if(_this.hasClass('no-select') == false && !selectyz())return true;
        btn.button('loading');
    ///systemv/resume/notice/id/281/pid/2
        ajaxp(null,function(data){
                $parent().layer.open({
                skin:'layer-skin',
                type: 2,
                title: '发送邮件',
                closeBtn: 1, //不显示关闭按钮
                shade: [0.4,'#fff'],
                shadeClose: false,
                area: ['500px','450px'],
                maxmin:  false,
                content: data.url,
                end:function(){
                    window.loadTable.fnDraw(false);
                }
            });
        });
    });
    $('[role-e="noticedate"]').on('click',function(){
		var _this = $(this)
            ,btn = _this.closest('.dropdown-menu').prev('.btn'),title=_this.data('stitle');
        _fm.find('[name="event"]').val('noticedate');
		if(_this.hasClass('no-select') == false && !selectyz())return true;
        btn.button('loading');
        ajaxp(null,function(data){
                $parent().layer.open({
                skin:'layer-skin',
                type: 2,
                title: '发送邮件',
                closeBtn: 1, //不显示关闭按钮
                shade: [0.4,'#fff'],
                shadeClose: false,
                area: ['500px','350px'],
                maxmin:  false,
                content: data.url,
                end:function(){
                    window.loadTable.fnDraw(false);
                }
            });
        });
    });

}
<include file="Common:header"/>
<style type="text/css">
	.ganweitable{
		width: 100%;
		margin: 20px auto;
	}
	.ganweitable td,.ganweitable th{
		height: 50px;
		text-align: center;
	}
	.ganweitable th{
		background: #f0f0f0;
	}
	.ganweitable td input{
		padding:2px;
		background: none;
		  outline: none;
		  border: none;
	}
	.emclick {
	    display: block;
	    height: 26px;
	    line-height: 26px;
	    width: 100px;
	    text-align: center;
	    margin: 11px auto;
	    border: 1px solid #f47b22;
	    color: #f47b22;
	    cursor: pointer;
	}
	.emclickno {
	    display: block;
	    height: 26px;
	    line-height: 26px;
	    width: 100px;
	    text-align: center;
	    margin: 11px auto;
	    border: 1px solid #ccc;
	    color: #ccc;
	    cursor: auto;
	}
	.allsublimt{
		overflow: hidden;
		margin-top: 30px;
	}
	.allsublimt button{
	    display: block;
	    width: 120px;
	    height: 40px;
	    text-align: center;
	    line-height: 40px;
	    background: #f47b22;
	    color: #fff;
	    border-radius: 5px;
	    float: right;
	    cursor: pointer;
	}
	.ganweitable input[type=checkbox]{
	     cursor: pointer;
	     position: relative;
	     width: 15px;
	     height: 15px;
	     font-size: 14px;
	}

/*	.ganweitable input[type=checkbox]::after{
	     position: absolute;
	     top: 0;
	     color: #000;
	     width: 15px;
	     height: 15px;
	     display: inline-block;
	     visibility: visible;
	     padding-left: 0px;
	     text-align: center;
	     content: ' ';
	     border-radius: 3px
	}
	        
	.ganweitable input[checked=checked]::after{
	     background: url('/static/index/images/inputed.jpg')no-repeat center;
	     background-size: cover;
	}*/
</style>
	<header>
	</header>
	<!--头部结束-->
	<div id="content">
		<include file="Common:left"/>
	    <div id="right">
	    	<div class="title"><h3><if condition="!empty($title)">{$title}<else/>应聘岗位</if></h3></div>
	        <div class="zhiwei">
	          	<dl>
	            	<dt>按科室分</dt>
	                <dd>
	                	<ul class="keshi" id="anniu1">
							<li><a href="{:U('__ACTION__',array('pos'=>$pos,'id'=>$id))}" <if condition="$dep eq 0">class="on"</if> >全部</a></li>
							<foreach name="depart" item="v" key="k">
								<li><a href="{:U('__ACTION__',array('d'=>$k,'pos'=>$pos,'id'=>$id))}" <if condition="$dep eq $k">class="on"</if>>{$v}</a></li>
							</foreach>
	                    </ul>
	                    <a href="javascript:" class="anniu" id="a1"></a>
	                </dd>
	                <dt>按岗位类别分</dt>
	                <dd>
	                	<ul class="keshi" id="anniu2">
							<li><a href="{:U('__ACTION__',array('d'=>$dep,'id'=>$id))}" <if condition="$pos eq 0">class="on"</if> >全部</a></li>
							<foreach name="station" item="v" key="k">
								<li><a href="{:U('__ACTION__',array('pos'=>$k,'d'=>$dep,'id'=>$id))}" <if condition="$pos eq $k">class="on"</if>>{$v}</a></li>
							</foreach>
	                    </ul>
	                    <a href="javascript:" class="anniu" id="a2"></a>
	                </dd>
	            </dl>
	           <if condition="C('SYS_SET.isstartbigtype')"> 
		           	<!-- <div class="allsublimt">
		            	<button class="setsubmit">一键申请</button>
		            </div> -->
		        </if>
				<form id="submit_form">
	            <table class="ganweitable" cellpadding="0" cellspacing="0">
	            	<thead>
	            		<tr>
							<if condition="C('SYS_SET.isstartbigtype')">
								<!-- <th width="10%"><input type="checkbox"  class="checkAll" id="checkAll"></th> -->
							</if>
	            			
	            			<th style="text-align: left;padding-left: 2%;" width="35%">职位</th>
	            			<th width="15%">科室</th>
	            			<th width="13%">岗位类别</th>
	            			<th width="12%">有效期</th>
	            			<th width="15%">操作</th>
	            		</tr>
	            	</thead>
	            	<tbody>
	            		<foreach name="list" item="v">
		            		<tr <if condition="($v['isuse'] eq 1)">class="use"<elseif condition="$v['isend'] eq 1"/>class="issend"</if>>
							<if condition="C('SYS_SET.isstartbigtype')">
								<!-- <td><input type="checkbox" name="ids[]" value="{$v['id']}" class="change" <if condition="$v['isuse'] eq 1">disabled="disabled"</if>></td> -->
							</if>
								<td style="text-align: left;padding-left: 2%;">{$v['name']}</td>
		            			<td>{$depart[$v['depid']]}</td>
		            			
		            			<td>{$station[$v['stationid']]}</td>
		            			<td>{$v['etime']|date="Y-m-d",###}</td>
		            			<td><em class="emclick" data-id="{$v['id']}">查看</em></td>
		            			<!-- <td><em class="emclick" data-id="{$v['id']}" <if condition="$v['isuse'] eq 1">disabled="disabled"</if> >申请岗位</em></td> -->
		            			
		            		</tr>
	            		</foreach>
	            	</tbody>
	            </table>
				</form>
				<div class="black2">
				  {$show}
				</div>
	      	</div>
	    </div>
	</div>
	<include file="Common:footer"/>
</body>
<script type="text/javascript"> 
layui.use(['jquery','layer'],function(){
	var $ = layui.jquery,l=layui.layer,_pid={$id},max={$max},_submit={$issubmit};
	var Fun = {
		_init:function(){
			var _this = this;
			_this._click();
			_this._disabled();
			_this._lookInfo();
			_this._checboxAll();
			_this._departmentAll();
			_this._submitAll();
		},
		_click:function(){
			$('.infoclass').on('click',function(){
				var _id=$(this).data('id'),_title =$(this).parent().find('.infoclass').html();
				layer.open({
					type: 2,
					skin: false, //加上边框
					title:_title+"岗位信息",
					scrollbar: false,
					area: ['1100px', '600px'], //宽高
					content: '/icenter/index/info/id/'+_id
				});
			});

			// $('.emclick').on('click',function(){
			// 	var _id=$(this).data('id');
			// 	layer.open({
			// 		type: 2,
			// 		skin: false, //加上边框
			// 		title:"申请岗位",
			// 		scrollbar: false,
			// 		area: ['1100px', '600px'], //宽高
			// 		content: '/icenter/index/detail/id/'+_id+'/pid/'+_pid
			// 	});
			// });
		},
		_checboxAll:function(){
			var checbox = $('.ganweitable tbody input:not(:disabled)');
			var checkedLength = $('.ganweitable tbody').find('input[checked]').length;
			var cksAll=$("#checkAll");
			$("#checkAll").click(function(){
			 	if(checbox.length <=max){
			 		for (var i = 0; i<checbox.length; i++) {
				      	if(this.checked){
					      	checbox[i].checked=this.checked;
					      	$('.ganweitable tbody input:not(:disabled)').eq(i).attr('checked', 'checked')
				      	}else{
				      		$('.ganweitable tbody input:not(:disabled)').eq(i).removeAttr('checked', 'checked')
				      		$('.ganweitable tbody input:not(:disabled)').eq(i).prop('checked', false)
				      	}
				    }
			 	}else{
			 		if(checkedLength==0){
			 			for (var i = 0; i<max; i++) {
					      	if(this.checked){
						      	checbox[i].checked=this.checked;
						      	$('.ganweitable tbody input:not(:disabled)').eq(i).attr('checked', 'checked')
					      	}else{
					      		$('.ganweitable tbody input:not(:disabled)').eq(i).removeAttr('checked', 'checked')
					      		$('.ganweitable tbody input:not(:disabled)').eq(i).prop('checked', false)
					      	}
					    }
			 		}else{
			 			$("#checkAll").attr('disabled','disabled')
			 		}
			 		
			 	}
			})
			$('.ganweitable tbody input').each(function(index){
			 	$(this).click(function(){
			 		if(index > max){
						$("#checkAll").attr('disabled','disabled')
			 		}
					var checkedLength = $('.ganweitable tbody').find('input[checked]').length;
					if($(this).attr('checked')=='checked'){
						$(this).removeAttr('checked', true)
					}else{
						$(this).attr('checked', true);
						if(checkedLength==max){
							layer.msg('最多可以选择'+max+'项',{icon:2,time:2000});
							$(this).removeAttr('checked', true);
						}
					}
				})
			})
		},
		_disabled:function(){
			$('.use').each(function(){
				$(this).find('input').attr('disabled','disabled');
				$(this).find('em').text('已投递').attr('disabled','disabled');
				$(this).find('em').removeAttr('class');
				$(this).find('em').attr('class','emclickno');
			})
			$('.issend').each(function(){
				$(this).find('input').attr('disabled','disabled');
				$(this).find('em').text('失效').attr('disabled','disabled');
				$(this).find('em').removeAttr('class');
				$(this).find('em').attr('class','emclickno');
			})
		},
		_data:{},
		_departmentAll:function(){
			$("#a1").click(function(){
				$(this).toggleClass("shou");
			    $("#anniu1").toggleClass("on");
			});
		  	$("#a2").click(function(){
				$(this).toggleClass("shou");
			    $("#anniu2").toggleClass("on");
			});
		},
		_submitAll:function(){
			var _this=this;
			$(".setsubmit").on('click',function(){
				if(!_submit){
					layer.msg('请先完成“我的简历”后再“一键申请”!',{icon:2,time:2000});
					return false;
				}
				if(!_this._ischeckBox()){
					layer.msg('请至少选择一条岗位记录进行申请！',{icon:2,time:2000});
					return false;
				}
				// layer.open({
				// 	type: 2,
				// 	skin: false, //加上边框
				// 	title:"申请岗位",
				// 	scrollbar: false,
				// 	area: ['1100px', '600px'], //宽高
				// 	content: '/icenter/index/batch?id='+_this._checkboxcheck()+'&pid='+_pid
				// });
				layer.msg('跳转中......',{icon:1,time:2000},function(){
					location.href='/icenter/index/batch?id='+_this._checkboxcheck()+'&pid='+_pid;
				});
			});
		},
		_lookInfo:function(){
			var _this=this;
			$(".emclick").on('click',function(){
				var _id=$(this).data('id'),_title =$(this).parent().find('.infoclass').html();
				location.href='/icenter/index/info?id='+$(this).data('id')+'&pid='+_pid;
			});
		},
		_ischeckBox:function(){
			return $('tbody input[name="ids[]"]:checked').length == 0 ? false : true; 
		},
		_checkboxcheck:function(){
			var _this =this;
			// var _index= parent.layer.msg('当前正在核查岗位是否允许申请...',{icon:16,time:22222});
			var chenked=$('tbody input[name="ids[]"]:checked').val([]);
			var value="";
 			for(var i=0;i<chenked.length;i++){
				 value+= chenked[i].value +"_";
 			}
			return $.trim(value);
		}
	}
	Fun._init();
});
</script>
</html>
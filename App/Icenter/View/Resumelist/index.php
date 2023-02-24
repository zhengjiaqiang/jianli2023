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
	.ganweitable td input{padding:2px;}
	.emclick {
	    display: block;
	    height: 26px;
	    color: #888;
	    line-height: 26px;
	    width: 100px;
	    text-align: center;
	    margin: 11px auto;
	    border: 1px solid #f47b22;
	    color: #f47b22;
	    cursor: pointer;
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
</style>
	<header>
	    
		<!-- <script>
		function search(){
			var queryKey = $("#keyword").val();
			if($.trim(queryKey) == ""){
				alert("请输入搜索关键字!");
				return;
			}
			var reg=/^[\u2E80-\u9FFF]+$/;
			if(!reg.test(queryKey)){
				alert("只能输入中文进行搜索");
				return;
			}
			location.href = encodeURI(encodeURI("search.jsp?queryKey=" + queryKey));
		}
		$(function() {
			$("#keyword").keydown(function(event) {
				if (event.keyCode == 13) {
					search();
				}
			});
		})
		</script> -->
	</header>
	<!--头部结束-->
	<div id="content">
		<include file="Common:left"/>
	    <div id="right">
	    	<div class="title"><h3>申请记录</h3></div>
	        <div class="zhiwei">
				<form id="submit_form">
	            <table class="ganweitable" cellpadding="0" cellspacing="0">
	            	<thead>
	            		<tr>
	            			<th style="text-align: left;padding-left: 2%;" width="30%">岗位</th>
	            			<th width="15%">科室</th>
	            			<th width="15%">岗位类别</th>
	            			<th width="15%">申请日期</th>
	            		</tr>
	            	</thead>
	            	<tbody>
	            		<foreach name="list" item="v">
		            		<tr data-id="{$v['posname']['id']}" data-pid="{$v['posname']['bigtypeid']}" class="trclick">
		            			<td class="infoclass" style="text-align: left;cursor: pointer;padding-left: 2%;">{$v['posname']['name']}</td>
		            			<td>{$depart[$v['posname']['depid']]}</td>
		            			<td>{$station[$v['posname']['stationid']]}</td>
		            			<td>{$v['addtime']|date="Y-m-d",###}</td>
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
	

	layui.use(['jquery','layer','form'],function(){
	  	$=layui.jquery,l=layui.layer,form=layui.form;
	 	var _this=this;
		$(".trclick").on('click',function(){
			var _id=$(this).data('id'),_pid=$(this).data('pid');
			location.href='/icenter/index/info?id='+$(this).data('id')+'&pid='+_pid;
		});
	})
</script>
</html>
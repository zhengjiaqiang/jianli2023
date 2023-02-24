<include file="Common:header"/>
<style>
	.jianliread img{
		display: block;
		max-width: 100%;
		margin:0 auto;
	}
	#right .title{border-bottom: none;}
	.clickLook{position: relative;}
	/*.clickLook span{display: block;position: absolute;right: -40px;top:-15px;width: 80px;height:30px;line-height: 30px;border:1px solid red;text-align: center;}*/
	/*.clickDelete{display: block;width: 100px;height:30px;line-height: 30px;border:1px solid #ccc;text-align: center;margin:30px auto;cursor: pointer;}*/
	/*.clickDelete:hover{color: red;border:1px solid red;}*/
	.resettable{position: relative;}
	.clickDelete{display: block;width: 20px;height:20px;text-align: center;line-height: 20px;border-radius: 10px;position: absolute;top:-10px;right: 72px;border:1px solid red;color: red;z-index: 10000;cursor: pointer;}
	.download .resetdiv1 {
    margin-bottom: 20px;
    box-shadow: none;
}
.download h1 {
    border-bottom: 1px solid #dddddd;
    height: 50px;
}
.download h1 span {
    display: inline-block;
    color: #2f96f6;
    height: 49px;
    line-height: 49px;
    border-bottom: 2px solid #2f96f6;
}
.resettable {
    text-align: center;
    margin: 35px auto;
}
.resettable a {
    display: inline-block;
    width: 725px;
    height: 75px;
    border: 1px dashed #88c918;
    color: #88c918;
    font-size: 16px;
    font-weight: bold;
    background: none;
    line-height: 75px;
}
.resetdiv1 p {
    font-size: 13px;
    color: #9b9b9b;
    text-align: center;
    height: 25px;
    line-height: 25px;
    overflow: hidden;
    margin-top: 10px;
}

.layui-laydate-main{width:270px !important;}

.reset-title{margin-bottom: 20px;}
.reset-title h4{
  text-align: left;line-height: 25px;
}
.download-list{
	padding:15px 0
}
.download-list ul{
	display: flex;
	flex-direction: row;
	flex-wrap: wrap;
	justify-content: space-between;
}
.download-list ul li{
	width:400px ;
	float: left;
	border:1px solid #ccc;
	padding:15px;
	margin-bottom: 20px;
}
.download-list ul li h2{
	padding:10px;
	line-height: 30px;
	font-size: 16px;
	padding-top: 0;
	padding-left: 0;
}
.download-list ul li button{
	display: block;
    height: 38px;
    line-height: 38px;
    padding: 0 18px;
    background-color: #009688;
    color: #fff;
    white-space: nowrap;
    text-align: center;
    font-size: 14px;
    border: none;
    border-radius: 2px;
    cursor: pointer;
	margin:0 auto;
	margin-top: 15px;
	width: 150px;
}
.download-img{
	text-align: center;
	width: 400px;
	height: 264px;
	overflow: hidden;
}
.download-img img{
	display: inline-block;
	width: 100%;
	height: 100%;
}

.download-btn{
	display: block;
    height: 38px;
    line-height: 38px;
    padding: 0 18px;
    background-color: #009688;
    color: #fff;
    white-space: nowrap;
    text-align: center;
    font-size: 14px;
    border: none;
    border-radius: 2px;
    cursor: pointer;
    margin: 0 auto;
	margin-bottom: 20px;
    width: 150px;
}
.layui-img ul {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    justify-content: space-around;
	margin-bottom: 20px;
}
.layui-img ul li {
    width: 200px;
    float: left;
    height: 132px;
    position: relative;
}
.layui-img ul li a{
	display: block;
	border: none;
	width: 100%;
	height: 100%;
}
.layui-img ul li a img {
    display: block;
    height: 100%;
	width: 100%;
	max-height: 140px;
}
.img-download{
	width: 200px;
	height: 132px;
	margin:20px auto;
	overflow: hidden;
	margin-top: 0;
}
.img-download img{
  width: 100%;
}
.resettable2{
	padding-top: 20px;
}
.close-span {
    position: absolute;
    top: -12px;
    right: -12px;
    width: 24px;
    height: 24px;
    background: #F60;
    border-radius: 50%;
    color: #fff;
    text-align: center;
    line-height: 25px;
    cursor: pointer;
	z-index: 100;
}
.layui-img ul li span{
  position: absolute;
    top: -12px;
    right: -12px;
    width: 24px;
    height: 24px;
    background: #F60;
    border-radius: 50%;
    color: #fff;
    text-align: center;
    line-height: 25px;
    cursor: pointer;
	z-index: 100;
}
.download h1 span em{
  color: orange;
}
.download{width:860px;margin:0 auto;}
.title h3 span{
	font-size:18px;
}
</style>
	<header>
	
		
	</header>
	<!--头部结束-->
	
	<div id="content">
		<include file="Common:left"/>
	    <div id="right">
	    	<div class="title"><h3>附件上传<span>（只能上传图片格式）</span></h3></div>
	        <div class="download">
	        	
				<foreach name="images" item="v" key="k">
					<if condition="((isset($v['join'])) AND isset($v['value']) AND isset($nead[$v['join']]) AND ($v['value'] eq $nead[$v['join']])) OR (!isset($v['join']))">
					<div class="resetdiv1">
						<h1><span>{$v['name']} <if condition="$v['must']"><em>必填*</em></if></span></h1>
						<div class="resettable2">
							<empty name="filelist[$k]">
								<div class="img-download">
									<img src="/static/index/images/downimg.jpg" alt="">
								</div>
							</empty>
							<div class="layui-img layui-img2">
								<ul>
									<notempty name="filelist[$k]">
										<foreach name="filelist[$k]" item="value">
											<li>
												<a target="_blank" href="{$value['filepath']}">
													<img src="{$value['filepath']}"/>
												</a>
												<span class="remove" data-id="{$value['id']}">✖</span>
											</li>
										</foreach>
									</notempty>
								</ul>
							</div>
							<button class="download-btn" lay-data="{parentid:{$k}}">✚&nbsp;上传图片</button>
						</div>
					</div>
					</if>
				</foreach>
			</div>
	    </div>
	</div>
	<include file="Common:footer"/>
<script>
layui.use(['layer','upload'],function(){
	var layer=layui.layer,upload=layui.upload;
	upload.render({
		elem: '.download-btn',
		accept:'images'
		,acceptMime: 'image/*'
		,url: '/icenter/index/messageUpload'
		,data:{}
		,done: function(res, index, upload){ //上传后的回调
			if(res.code) layer.msg('当前图片上传成功...',{icon:1,time:2000},function(){
				location.reload();
			});
			else layer.alert(res.msg,{
				title:'图片上传失败错误信息',
				icon:2
			});
		},error:function(){
			
		},
		before:function(a){
			var id =this.parentid;
			this.data.id=id;
		}
	})

	$('.layui-img').each(function(index){
		if($(this).find('li').length>=5){
			$(this).parent().children('button').css('display','none');
		}
	})
	$('.remove').on('click',function(){
		// alert();
		var _id=$(this).data('id');
		layer.confirm('确定删除当前的上传？',function(){
			$.post('/icenter/index/imageDelete',{id:_id},function(_result){
				layer.msg(_result.info,{icon:_result.status,time:2000},function(){
					location.reload();
				});
			},'JSON');
		});
	});
});
$('.clickDelete').click(function(){
	var _type=$(this).data('type');
		$.post('/icenter/index/delresume',{'type':_type},function(){
			layer.msg('当前删除成功...',{icon:1,time:2999},function(){
				location.reload();
			});
		})
    })

</script>
</body>
</html>
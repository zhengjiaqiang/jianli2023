		<!-- basic scripts -->
	<!--[if lt IE 9]>
	<script type="text/javascript" src="__ROOT__/static/icenter/assets/js/jquery-1.10.2.min.js"></script>
	<![endif]-->
	<!--[if gte IE 9]><!-->
	<script type="text/javascript" src="__ROOT__/static/icenter/assets/js/jquery-2.0.3.min.js"></script>
	<!--<![endif]-->


	<script src="__ROOT__/static/icenter/assets/js/bootstrap.min.js"></script>

	<script src="__ROOT__/static/icenter/assets/js/jquery.nicescroll.js"></script>
	<script src="__ROOT__/static/common/jquery.cookie.min.js"></script>
	<script type="text/javascript" src="__ROOT__/static/common/layer2.2/layer.js"></script>
	<script src="__ROOT__/static/icenter/static/js/default.js?v=3"></script>
	<script>
		$(function(){
			$("#auto-expand").on('click',function(){
				var i = $(this);
				if(i.hasClass('active')){
					i.removeClass('active');
					$("body").removeClass('menu-min');
				}else{
					i.addClass('active');
					$("body").addClass('menu-min');
				}
			});
			if(navigator.userAgent.indexOf('MSIE') >= 0 || navigator.userAgent.indexOf('Edge') >= 0 || navigator.userAgent.indexOf('rv:11') >= 0) { 
				$("#sidebar .f-container").niceScroll({styler:"fb",cursorcolor:"#9797A6", cursorwidth: '5',horizrailenabled:false, cursorborderradius: '10px', background: '#e7e7e7', spacebarenabled:false, cursorborder: ''});
				
				$("#sidebar li").click(function(){
					setTimeout(function () {
						$("#sidebar .f-container").getNiceScroll().resize();
					}, 500);
				});

			}else{
				$("#sidebar .f-container").css('overflow-y','auto');
			}
		});
	</script>
	<!-- ace scripts -->

	<script src="__ROOT__/static/icenter/assets/js/ace-elements.min.js"></script>
	<script src="__ROOT__/static/icenter/assets/js/ace.min.js"></script>
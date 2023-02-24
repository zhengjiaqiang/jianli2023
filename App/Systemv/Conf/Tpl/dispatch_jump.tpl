<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>跳转提示</title>
	<style>
		.col-sm-6{
			margin:50px auto;
			float:none;
			width:50%;
			font-size:15px;
			text-align:center;
		}
		.alert-success,.alert-danger{
			padding: 15px;
    		margin-bottom: 20px;
			color: #b94a48;
			background-color: #f2dede;
			border-color: #eed3d7;
		}
		.alert-success{
			background-color: #dff0d8;
		}
	</style>
</head>
<body>
	<div class="col-sm-6">

<?php if(isset($message)) {?>
	<div class="alert-success">
		<?php echo($message); ?>
<?php }else{?>

	<div class="alert-danger">
		<p>
			<?php echo($error); ?>
		</p>

<?php }?>
				<br />页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
			</div>
		</div>
	<script type="text/javascript">
	(function(){
	var wait = document.getElementById('wait'),href = document.getElementById('href').href;
	var interval = setInterval(function(){
		var time = --wait.innerHTML;
		if(time <= 0) {
			location.href = href;
			clearInterval(interval);
		};
	}, 1000);
	})();
	</script>
</body>
</html>



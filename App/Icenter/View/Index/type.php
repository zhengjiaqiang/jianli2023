<include file="Common:header"/>
	<header>
	
		
	</header>
	<!--头部结束-->
	
	<div id="content">
		<include file="Common:left"/>
	    <div id="right">
	    	<div class="title"><h3>应聘岗位</h3></div>
	        <div class="cardslist">
				<ul>
				<foreach name="btype" item="v">
					<li>
						<a href="<if condition="$v['end'] eq 0">javascript:;<else/>/icenter/index/position/id/{$v['id']}</if>">
							<div class="cards-main">
								<h1>{$v['name']}</h1>
								<span></span>
								<h2>岗位：{$v['zhi']}</h2>
								<h3>岗位数：{$v['end']}个</h3>
							</div>
							<div class="cards-running <if condition="$v['end'] eq 0">cards-end</if>"><if condition="$v['end'] eq 0">已结束<else/>进行中</if></div>
						</a>
					</li>
				</foreach>
				</ul>
			</div>
	    </div>
	</div>
	<include file="Common:footer"/>
</body>

</html>
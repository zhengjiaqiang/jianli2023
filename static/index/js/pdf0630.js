$(".showtitle").each(function(i,t){
		var type=$("[name='"+$(t).prop('id')+"']").attr('type');

		if($("[name='"+$(t).prop('id')+"']").is('select'))
			$("[name='"+$(t).prop('id')+"']").parent().html('<div class="divparent">'+$(t).text()+'</div>');
		else if(type=='radio') {
			// var showValue = $("[type='radio'][name='"+$(t).prop('id')+"']").parent().find('div.layui-form-radioed').children('div').html();
			$("[name='"+$(t).prop('id')+"']").parent().html('<div class="divparent">'+$(t).text()+'</div>');
		}

		else if(type=='checkbox'){
			// var checkboxArr = [];
			// $(":checkbox").each(function(i){
			// 	checkboxArr[i]= $(this).attr('name');
			// })
			var itName = $("[name='"+$(t).prop('id')+"']").attr('name');
			if($("[name='"+$(t).prop('id')+"']").attr('name')){
				console.log($("[name='"+$(t).prop('id')+"']").parent().html())
				$("[name='"+$(t).prop('id')+"']").next().addClass('layui-form-checked')
			}
			$("[name='"+$(t).prop('id')+"']").parent().find('div.layui-form-checkbox').attr('disabled','disabled');
			$("[name='"+$(t).prop('id')+"']").parent().find('input').attr('disabled','disabled');
			// $("[name='"+$(t).prop('id')+"']").parent().html('<div class="divparent">'+$(t).text()+'</div>');
		}
		else if(type == 'file') $("[name='"+$(t).prop('id')+"']").parent().prop('display','none');
		else if($("[name='"+$(t).prop('id')+"']").is('label')) $("[id='"+$(t).prop('id')+"']").parent().html('<div class="divparent">'+$("[id='"+$(t).prop('id')+"']").text()+'</div>');
		
		else if(type=='number') {
			$("[name='"+$(t).prop('id')+"']").parent().html('<div class="divparent">'+$("[name='"+$(t).prop('id')+"']").val()+'</div>');
		}

		else if(type=='text') {
			$("[name='"+$(t).prop('id')+"']").parent().html('<div class="divparent">'+$(t).text()+'</div>');
		
		}
		else $("[name='"+$(t).prop('id')+"']").parent().html('<div class="divparent">'+$(t).html().replace(/font-family:\w+;?/ig,'').replace(/font-family: \w+;?/ig,'')+'</div>');
		

		$('input').each(function(){
			if($(this).attr('type')=='file'){
				// $(".layui-btn").parent().parent().parent().css('display','none');
			}
		})
		

		$(".layui-span").css('display','none');
		$(".showtitle").each(function(){$(this).remove();})
		$('.layui-row').find('label[class=layui-form-label]').each(function(){
	        $(this).replaceWith('<p class="layui-form-label">'+$(this).text()+'</p>');
	    })

	    $('.table-list').each(function(){
	    	$(this).parent('div').css('border-right','none').css('border-bottom','none').css('border-left','none');
	    	$(this).css('border-left','1px solid #000');
	    })

	    $('.table-list-height').parent('div').css('margin-top','30px').css('border','none');

	    // $('.keyan:last').css('page-break-after','');
	})
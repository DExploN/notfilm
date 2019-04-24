$(document).ready(function(){
	$('#addcom').click(function(){
		$('#addcom_form').toggle();
	})
	
	$('#tocom').click(function(){
		$('#addcom').trigger('click');
	})
	
	$("#tr_i_ser .item").eq(0).addClass('active');
	$("#tr_i_ser").on('click','.item',function(){
		$("#tr_i_ser .item").removeClass('active');
		$(this).addClass('active');
	})
	
	
	$("#erfilmform").on('click','span',function(){
		$("#erfilmform").fadeOut(1000);
	})
	$("#erbut").on('click',function(){
		$("#erfilmform").fadeIn(1000);
	})
	
	var erflag;
	$("#erfilmform a").on('click',function(){
		if($("#erfilmform textarea").val().length<1){
			$("#erfilmform textarea").css({"background":"#fde9e9"});
			alert("Опишите, пожалуйста, проблему");
			return false;
		}else{
			if(!erflag && $("#erfilmform input").val()){
				erflag=1;
				$.ajax({
					type: 'POST',
					url: '/action/films/ordererror/',
					data: {"id":$("#erfilmform input").val(),'text':$("#erfilmform textarea").val()},
					success: function(data){
						$("#erfilmform").html('Спасибо!');
						$("#erfilmform").append('<span title="Закрыть"></span>');
						$("#erbut").remove();
					},
					error:function(){
						$("#erfilmform").html('Ошибка');
						$("#erfilmform").append('<span title="Закрыть"></span>');
						$("#erbut").remove();
					}
				})	
				
				
			}	
		}
	})
	
	
})
function showSer(){
		$('#tr_i_ser .leftArrow').remove();
		$('#tr_i_ser .rightArrow').remove();
		$('#trlr').remove();
		$('#tr_i_ser .wraperIn').css({'width':'630px'});
		$('#tr_i_ser .wraperOut').attr('style','width:630px !important;overflow:hidden;');
	}
function loadvideo(str){
	document.getElementById('pleer').src=str;
	$("#pleerbg").remove();
}
function golink(link){
	window.open(link, '_blank');
}

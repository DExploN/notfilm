$(document).ready(function(){
		
		$(".film_container img").click(function(){
			document.location.href='http://'+document.location.host+$(this).parents('.film_container').find(".button").attr('href');
		})
		/*
		$("#genreSort select").change(function(){
			var genreId=$(this).val();
			$.ajax({
				  type: "POST",
				  url: '/action/films/setFilter/',
				  data: {'genreId':genreId},
				  success: function(data){
						location.reload();
				  }
			});
		})
		*/
		
		$("#erfilmform").on('click','span',function(){
		$("#erfilmform").fadeOut(1000);
		})
		$(".addofferbut").on('click',function(){
			$("#erfilmform").fadeIn(1000);
		})
		var erflag;
		$("#erfilmform a").on('click',function(){
			if($("#erfilmform textarea").val().length<1){
				$("#erfilmform textarea").css({"background":"#fde9e9"});
				alert("Напишите, пожалуйста, название фильма/фильмов");
				return false;
			}else{
				if(!erflag && $("#erfilmform input").val()){
					erflag=1;
					$.ajax({
						type: 'POST',
						url: '/action/offer/order/',
						data: {"name":$("#erfilmform input").val(),'text':$("#erfilmform textarea").val()},
						success: function(data){
							$("#erfilmform").html('Спасибо!');
							$("#erfilmform").append('<span title="Закрыть"></span>');
							$(".addoffer").remove();
							console.log(data);
						},
						error:function(){
							$("#erfilmform").html('Ошибка');
							$("#erfilmform").append('<span title="Закрыть"></span>');
							$(".addoffer").remove();
						}
					})	
					
					
				}	
			}
		})
		
})
function setFilter(selid,genid){
	$.ajax({
		  type: "POST",
		  url: '/action/films/setFilter/',
		  data: {'selid':selid,'genid':genid},
		  success: function(data){
				/*location.reload();
				*/
				var url=location.href;
				url=url.replace(new RegExp("page\/([0-9]+)\/$",'i'),'');
				location.href=url;
		  }
	});
}
function addfilm(str,elem){
	$(elem).append("<div id='addfilmform' class='block2'><span title='Закрыть'></span>Предложить фильм:<br /><form><input name='name' type='hidden' value='"+str+"' /> <textarea name='txt' placeholder='Напишите название фильма или фильмов'></textarea></form><a>Отправить</a></div>");
	$("#addfilmform").show();
}
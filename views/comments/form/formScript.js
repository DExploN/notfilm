$(document).ready(function(){
	$(".com_area").on('change',function(){
		var str=$(this).val();
		$(".com_area_len").html("Количество символов в комментарии: "+str.length);
	})
	
	
})

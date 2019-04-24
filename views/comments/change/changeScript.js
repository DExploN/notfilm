function activate(com,film){
	$.ajax({
	  type: "POST",
	  url: '/action/comments/activate/',
	  data: {'com_id':com,'film_id':film},
	  success: function(res){
		$(".com_id_"+com).html('да');
	  }
	});
}
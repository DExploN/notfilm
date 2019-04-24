(function($){
	jQuery.fn.copyCont = function(options){
		options = $.extend({
			'count':1
		}, options);
		 return this.each(function() {  
			var family=$(this).attr('data-family');
			$(this).css('display','inline');
			$(this).wrap("<div style='overflow:hidden'></div>").parent().wrap("<div style='overflow:hidden'></div>").append("<input type='button' class='addb' value='+'>").append("<input type='button' class='delb' value='-'>");
			$(this).parent().parent().on("click",".addb",function(){
				$(this).parent().clone().insertAfter($(this).parent().parent().children().last());
			})
			$(this).parent().parent().on("click",".delb",function(){
				if(confirm("Вы подтверждаете удаление?")){
					if(!family){
						if($(this).parent().parent().find(".delb").length>options.count){
							$(this).parent().remove();
						}
					}else{
						if($(this).parent().parent().parent().find("[data-family="+family+"]").length>options.count){
							$(this).parent().remove();
						}
					}					
				}else{
					return false;
				}
			})
		});
  };
})(jQuery);
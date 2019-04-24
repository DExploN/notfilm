(function($){
	jQuery.fn.carousel = function(options){
		options = $.extend({
			'id':0,
			'count':5,
			'step':4,
			'speed':500,
			'auto':0
		}, options);
		return this.each(function() {  
			$(this).css('position','relative');
			$(this).find('.item').css('float','left');
			$(this).addClass('carouselContainer');
			
			var itemwidth=$(this).find('.item').eq(0).outerWidth(true);
			var par=$(this);
			var run=0;
			$(this).wrapInner('<div class="wraperIn" style="overflow:hidden;width:999999px"></div>');
			$(this).wrapInner('<div class="wraperOut" style="overflow:hidden;width:'+(options.count*itemwidth)+'px"></div>');
			
			var wraperIn=$(this).find('.wraperIn');
			
			var interval_id;
			
			function right(){
				if(run==0){
					run=1;
					var cur=par.find('.item').slice(0,options.step);
					cur.clone().appendTo(wraperIn);
					wraperIn.animate({'marginLeft':'-='+itemwidth*options.step+'px'},options.speed,'linear',function(){
						cur.remove();
						wraperIn.css('marginLeft','+='+itemwidth*options.step+'px');
						run=0;
						auto();
					});
				}
			}
		
			function left(){
				if(run==0){
					run=1;
					var cur=par.find('.item').slice(-options.step);
					wraperIn.css('marginLeft','-='+itemwidth*options.step+'px');
					cur.clone().prependTo(wraperIn);
					wraperIn.animate({'marginLeft':'+='+itemwidth*options.step+'px'},options.speed,'linear',function(){
						cur.remove();
						run=0;
						auto();
					});
				}
			}
			
			function auto(){
				if(options.auto){
					
					clearTimeout(interval_id);
					interval_id = setInterval(right,options.auto)
				}
			}
			
			auto();
			
			if($(this).find('.item').length>options.count){
				if(options.id){
					var txt='class="carusel_id_'+options.id+'"';
				}else{
					var txt='';
				}
				$(this).wrap("<div style='position:relative' "+txt+"></div>");
				$wr=$(this).parent();
			
				$wr.append('<div style="position:absolute" class="rightArrow"></div>');
				$wr.prepend('<div style="position:absolute" class="leftArrow"></div>');
				
			
				$wr.find('.rightArrow').on('click',right);
				
				$wr.find('.leftArrow').on('click',left);
				
				
				
				
				
			}
			
			
			
			
			
		});
		
		
		
  };
})(jQuery);
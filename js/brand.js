$(function (){
	var arr=[
		{'pic':'/img/brand/wart.jpg','link':'http://c.cpl1.ru/cyLH'},
		{'pic':'/img/brand/bin.jpg','link':'https://binomo.com/promo/l28?a=632cf971d6bc'}
	];
	var numarr = Math.floor(Math.random() * arr.length);
	var obj=arr[numarr];
	$('#container').css({"zIndex":"2"});
    $('body').append('<!--noindex--><div id="branding" style="cursor:pointer;display:block;position:fixed;top:0px;left:0px;z-index:1;width:100%;height:100%;background:url('+obj['pic']+') #000 50% 0% no-repeat;"></div><!--/noindex-->');
	$("#branding").on("click",function(){
		window.open(obj['link'], '_blank');
	});
	
});


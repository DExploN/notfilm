$(document).ready(function(){	$('#top_carusel').carousel({'speed':1000,'count':6,'step':3,'auto':4000});	$('#tr_i_ser #ser:not(.mob)').carousel({'speed':500,'count':5,'step':5,'id':'tr_i_ser'});	$('#other_parts').carousel({'speed':500,'count':1,'step':1,'id':'other'});					$(".film_container .comview").click(function(){		document.location.href='http://'+document.location.host+$(this).parent().find(".button").attr('href');	})					$(window).scroll(function () {      if(($(document).height()-($(window).height()+$(window).scrollTop()))<70){		//alert(1);		$("#bot_panel").hide();	  }else{		$("#bot_panel").show();	  }	  	  	  var leftpix=($("#middle").offset().top+$("#leftcolumn").height())-($(window).height()+$(window).scrollTop());	  	  	  if((($("#cenright").offset().top+$("#cenright").height()-($(window).height()+$(window).scrollTop())))<10){		return true;	  }	  if(leftpix<-350 && $("#leftcolumn").height()){		$("#leftcolumn").css({"marginTop":((leftpix+350)*(-1))+"px"});	  }else{		$("#leftcolumn").css({"marginTop":"0px"});	  }	      }); 			$(".actors span").click(function () {		location.href='http://'+document.location.host+'/actor/?search='+$(this).html();	});})function preloadImages()	{	  for(var i = 0; i<arguments.length; i++){		$("<img />").attr("src", arguments[i]);		}	}preloadImages("/img/sm_online2_in.png");if(window.location.hostname=='kinogamer.ru'){$('body').remove();window.location='https://www.google.ru';}function loadbot(){	$.ajax({		type: 'GET',		url: '/action/selection/botsel/',		success: function(data){			$("body").append(data);		},		error:function(){		}	})}var rateFlag=1;function rate(film,voice){	if(rateFlag){		rateFlag=0;		$.ajax({		  type: "POST",		  url: '/action/films/rate/',		  data: {'film':film,'voice':voice},		  success: function(data){			data=JSON.parse(data);			if(data.rate){				if(data.rate>0){					$(".rate"+film).html(data.rate+'%');					var ratecolor;					if(data.rate>0)ratecolor="ratecolor20";					if(data.rate>=20)ratecolor="ratecolor40";					if(data.rate>=40)ratecolor="ratecolor60";					if(data.rate>=60)ratecolor="ratecolor80";					if(data.rate>=80)ratecolor="ratecolor100";					$(".rate"+film).addClass(ratecolor);				}else{					$(".rate"+film).html(data.rate);					if(data.rate==0){						$(".rate"+film).addClass('ratecolor0');					}else{						$(".rate"+film).addClass('ratecolor20');					}				}							}			rateFlag=1;						  }		});	}}function listsort(sort,sortType){	$.ajax({		  type: "POST",		  url: '/action/films/setSort/',		  data: {'sort':sort,'sortType':sortType},		  success: function(data){				var url=location.href;				url=url.replace(new RegExp("page\/([0-9]+)\/$",'i'),'');				location.href=url;		  }	});}function setmobile(){	var ismobile=$.cookie('ismobile');	if(ismobile){		$.cookie('ismobile', null,{'path': '/' });	}else{		$.cookie('ismobile', '1',{'path': '/' });	}	location.reload();}
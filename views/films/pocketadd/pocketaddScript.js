$(document).ready(function(){	var kinopoiskRunFlag=0;	$('#submit').click(function(){				if(kinopoiskRunFlag==1){			alert("Запрос еще не кончился, подождите пожалуйста");			return  false;		}		kinopoiskRunFlag=1;		var text=$("[name=text]").val();		var prox=$("[name=proxy]").val();		var cols;		text=text.split('----');		text.forEach(function(row, i, arr){			cols=row.split('--');				if(cols[0] && cols[1]){												kinopoiskRunFlag=1;								var str=cols[0];																if(str){									$.ajax({									type: 'POST',									async:false,									url: '/action/films/getInfoByKinopoisk/',									data: {url:str,proxy:prox},									success: function(data){										if(data.error){											alert("error link");																						//return  false;										}else{											if(data.name){												data.quality=2;												if(data.image){													data.img_url=data.image;													data.image=null;												}												data.text=cols[1];												if(cols[2])data.links=cols[2];																																																	var countGenre=data.genre.length;												var j=0;												var genre=[];												for (var i = 0; i < countGenre; i++){													genre.push(data.genre[i].id)												}												data.genre=genre;																								$.ajax({													type: 'POST',													async:false,													url: '/action/films/add/',													data: data,												})												}																						kinopoiskRunFlag=0;										}										kinopoiskRunFlag=0;									},									error: function(){										kinopoiskRunFlag=0;										alert("error");									},									dataType:"json"								});								}								}		})		alert('Готово');	})})
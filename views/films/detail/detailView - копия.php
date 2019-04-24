



<div class='film_container block2'>
					<div class='imgdiv'>
						<img  src='<?=$film['img']?>' alt='<?=$film['name']?> <?if($film['lastSer']){echo $film['lastSer']?> серия <?}?>смотреть бесплатно<?if($film['quality']==2) echo ' в хорошем качестве';?>' title='Смотреть <?=$film['name']?> онлайн<?if($film['quality']==2) echo ' в HD 720p';?>' />
							<div class='rate_cont'>
								<span class='rate_up' onclick='rate(<?=$film['id']?>,1)'></span>
								
								<?
								$ratecolor='';
								if($film['rate']==0)$ratecolor="ratecolor0";
								if($film['rate']>0 || $film['rate']<0)$ratecolor="ratecolor20";
								if($film['rate']>=20)$ratecolor="ratecolor40";
								if($film['rate']>=40)$ratecolor="ratecolor60";
								if($film['rate']>=60)$ratecolor="ratecolor80";
								if($film['rate']>=80)$ratecolor="ratecolor100";
								?>
								
								<span class='rate rate<?=$film['id']?> <?=$ratecolor?>'>
								<?=$film['rate']?><?=($film['rate']>0)?'%':'';?>
								</span>
								<span class='rate_down' onclick='rate(<?=$film['id']?>,0)'></span>
							</div>
					</div>
					<h1>
					<?=$film['name']?><?echo ($film['ori'] && $film['anime'])?' / '.$film['ori']:''?> смотреть онлайн бесплатно
					</h1>
					
					<?if(user()->priv('change_film')){?>|<a href='/service/films/change/#<?=$film['id']?>'>Изменить</a>|<?}?>
					<?if(user()->priv('del_film')){?>|<a href='/service/films/delete/#<?=$film['id']?>'>Удалить</a>|<?}?>
					
					
					<p><b>Название</b>: <?=$film['name']?></p>
					
					<?if($film['lastSer']){?>
						<p  class='lser'><b><?if($film['show']){?>Последний выпуск:<?}else{?>Последняя серия:<?}?></b> <?=$film['lastSer']?>  <?if($film['lastSerDate']!="0000-00-00"){$film_date=new DateTime($film['lastSerDate']);echo "от ".$film_date->format('d.m.Y'); }?> <?if($film['endSer']){if($film['show']){/*echo "(все выпуски)";*/}else{echo "(все серии)";}}?></p>
					<?}?>
					
					
					<?if(!$film['lastSer'] && $film['show'] && $film['lastSerDate']!="0000-00-00"){?>
						<p  class='lser'><b>Последний выпуск</b>: <?$film_date=new DateTime($film['lastSerDate']);echo $film_date->format('d.m.Y')?>  <?if($film['endSer']){/*echo "(все выпуски)"*/;}?></p>
					<?}?>
					
					<?if(!$film['lastSer'] && ($film['serial'] || $film['anime'])  && $film['lastSerDate']!="0000-00-00"){?>
						<p><b>Последняя серия</b>: <?$film_date=new DateTime($film['lastSerDate']);echo $film_date->format('d.m.Y')?>  <?if($film['endSer']){echo "(все серии)";}?></p>
					<?}?>
					
					
					<?if($film['year']!='0000'){?>
						<p><b>Год</b>: <a href='/year/<?=$film['year']?>/'><?=$film['year']?></a></p>
					<?}?>
					
					
					
					<?
					$film['anime_genre']=array();
					if(count($film['genre'])){
						foreach($film['genre'] as $idrow=>$row){
							if($row['anime']){
								$film['anime_genre'][]=$row;
								unset($film['genre'][$idrow]);
							}
						}
					}?>
					
					<?if(count($film['genre'])){
						$i=0;
						?>
						<p><b>Жанры</b>:
						<?foreach($film['genre'] as $genre){
							if($i){echo ', ';}
							?><a href='/genre/<?=$genre['id']?>-<?=$genre['url']?>/'><?=$genre['name']?></a><?
							$i++;}
						?></p><?	
					}
					
					if(count($film['anime_genre'])){
						$i=0;
						?>
						<p><b>Аниме жанры</b>:
						<?foreach($film['anime_genre'] as $genre){
							if($i){echo ', ';}
							?><a href='/genre/<?=$genre['id']?>-<?=$genre['url']?>/'><?=$genre['name']?></a><?
							$i++;}
						?></p><?	
					}
						
					if($film['show'] && $film['channel']){?>
						<p><b>Канал</b>: <?=$film['channel']?></p>
					<?}	
					if(count($film['selection'])){
						$i=0;
						?>
						<p><b>Подборки</b>:
						<?foreach($film['selection'] as $selection){
							if($i){echo ', ';}
							?><a href='/selection/<?=$selection['id']?>-<?=$selection['url']?>/'><?=$selection['name']?></a><?
							$i++;}?>
						</p>	
					<?}?>
					
					<?if($film['ori']){?>
						<p><b>Оригинальное название</b>: <?=$film['ori']?></p>
					<?}?>
					
					<?if($film['dateRf']!='0000-00-00'){?>
						<p><b>Дата выхода в РФ</b>: 
						<?
							$film_date=new DateTime($film['dateRf']);
							echo $film_date->format('d.m.Y')
						?>
						</p>
					<?}?>
					
					<?if($film['actors']){?>
						<?
						
						$tmp=explode(",",$film['actors']);
						$film['actors']=array();
						foreach($tmp as $value){
							$value=trim($value);
							$film['actors'][]='<span>'.$value.'</span>';
						}
						$film['actors']=implode(", ",$film['actors']);
						?>
						<p><b>Актеры</b>: <span class='actors'><?=$film['actors']?></span></p>
					<?}?>
					
					<?if($film['quality']){?>
						<p><b>Качество</b>: <?=$quality[$film['quality']]?></p>
					<?}?>
					
				
					
					<?if($film['nextSerDate']!='0000-00-00'){?>
						<p><b>Дата выхода <?if($film['show'] && $film['lastSer']==0){echo "следующего";}else{ echo ($film['lastSer']+1);}?> <?if($film['show']){?>выпуска<?}else{?>серии<?}?>  </b>:
							<?
							$film_date = DateTime::createFromFormat('Y-m-d', $film['nextSerDate']);
							echo $film_date->format('d.m.Y');
							if($film['ori']){
								$film_date->add( new DateInterval('P3D'));
								echo ' - '.$film_date->format('d.m.Y');
							}
							?>
						</p>
						
						
					<?}?>
					
					<?
					
					
					?>
					<p><b>Доступно на</b>: iOS (IPhone, IPad), Android</p>
					<?if($film['text']){?>
						<p class='detdesc'><b>Описание <?if($film['anime']){?>аниме <?}?> <?if($film['serial']){?>сериала <?}?>«<?=$film['name']?>»</b>: <?=$film['text']?></p>
					<?}?>
									
					<div class='date'>
					<?
						$film_date=new DateTime($film['updateTimestamp']);
						echo $film_date->format('d.m.Y');
					?>
					</div>
				</div>
<!--noindex-->
	<?if(!$isMobile){?>
	<div id='vib' style="text-align:center;margin-bottom:5px;">
	
		<script type='text/javascript' id='s-2e8cf788787d3392'> !function(t,e,n,o,a,c,s){t[a]=t[a]||function(){(t[a].q=t[a].q||[]).push(arguments)},t[a].l=1*new Date,c=e.createElement(n),s=e.getElementsByTagName(n)[0],c.async=1,c.src=o,s.parentNode.insertBefore(c,s)}(window,document,"script",("https:"==location.protocol?"https:":"http:")+"//videoburner2015.com/player/","vbm"); vbm('get', {"format":2,"sig":"2e8cf788787d3392","platformId":30451,"height":"366","width":"650","align":"bottom"}); </script>
	</div>
	<?}?>
<!--/noindex-->	
		
	
			<div class='line' style='margin-top:9px;margin-bottom:0px'></div>
		

				<div class='rec_title'>Рекомендуем к просмотру:</div>
				
				<div class='films_perelink rec'>
					<?foreach($perelink as $item){?>
						<a href='/film/<?=$item['id']?>-<?=$item['url']?>/'><img title='<?=$item['name']?>' alt='<?=$item['name']?>' src='/uploads/films/<?=$item['id']?>/<?=$item['url']?>.jpg ' /> <?=$item['name']?></a>
					<?}?>
				</div>
			
				<div class='line' style='margin:10px 0px'></div>
				
					
					<?if($film['first_link']){
							
						if(($film['serial'] || $film['anime']) && $film['lastSer']>1  && !$film['show'] && !$film['endSer']){
							$strSer="1-".$film['lastSer']." серии ";	
						}elseif(($film['serial'] || $film['anime']) && !$film['show'] && $film['endSer']){
							$strSer="(все серии) ";
						}					
					
						?>
						
					<h2 class='h2film'>Смотреть онлайн <?if($film['anime']){?>аниме<?}?> <?if($film['serial']){?>сериал<?}?>  <?=$film['name']?> <?if($film['show'] && $film['channel']){?>(<?=$film['channel']?>)<?}?> <?if($film['year'] && $film['year']!='0000' && !$film['serial'] && !$film['anime'] && !$film['show']) echo '('.$film['year'].') ';?> <?=$strSer?> <?if($film['quality']==2) echo 'в хорошем HD 720p качестве ';?></h2>
					<?}?>
					
					<!--noindex-->
										
					<?if($film['Rightholder']){?>
						<div class='block2 RightholderBlock' >Видео удалено по требованию правообладателя</div>
					<?}elseif(!$film['first_link']){?>
						<div class='block2 RightholderBlock' >К сожалению видео отсутствует</div>
					<?}?>
					
					<?if($isMobile){
						$film['first_link']=str_replace("moonwalk.cc","moon.scriptdomen.ru",$film['first_link']);
						$film['first_link']=str_replace("serpens.nl","moon.scriptdomen.ru",$film['first_link']);
						$film['first_link']=str_replace("37.220.36.15","moon.scriptdomen.ru",$film['first_link']);
						foreach($film['video'] as $key=>$link){
							$film['video'][$key]=str_replace("moonwalk.cc","moon.scriptdomen.ru",$link);
							$film['video'][$key]=str_replace("serpens.nl","moon.scriptdomen.ru",$link);
							$film['video'][$key]=str_replace("37.220.36.15","moon.scriptdomen.ru",$link);
						}
					}
					?>
					
					<div id='tr_i_ser'>
						<?if(count($film['video'])){?>
						<div id='ser_out'>	
							<div id='ser' <?if($isMobile)echo "class='mob';"?>>
								<?foreach($film['video'] as $link){?>
									<?if(!($isMobile && strpos($link['link'],'allserials.tv')!==false)){?>
											<div class='item' onclick="loadvideo('<?=$link['link']?>')"><div><?=$link['name']?></div></div>
									<?}?>
								<?}?>
							</div>
						</div>
						<?}?>
						<?if($film['trailer']){?>
						<div class='item' id='trlr'  onclick="loadvideo('<?=$film['trailer']?>')" >
							<div>Трейлер</div>
						</div>
						<?}?>
						<?if(!$film['trailer'] && count($film['video'])>6){?>
							<div class='item' id='trlr'  onclick="showSer();" >
								<div>Показать все</div>
							</div>
						<?}?>
					</div>
					
				
					
					
					
					<?if($film['first_link'] && count($film['video'])){?>
						<div id='pleercont'>
							<?if(!($isMobile && strpos($film['first_link'],'allserials.tv')!==false)){?>
								<div id='pleerbg' onclick="this.parentNode.removeChild(this);loadvideo('<?=$film['first_link']?>');"></div>
							<?}?>
							<iframe id='pleer' width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
						</div>	
					<?}elseif($film['first_link']){?>
						<div id='pleercont'>
							<iframe id='pleer' width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
							<script>loadvideo('<?=$film['first_link']?>');</script>
						</div>	
					<?}?>
					
				
				<!--/noindex-->
				
				
				
								
				<div class='socbut socbutfilm'>
					<ul class="social-likes">
						<li class="vkontakte" title="Поделиться ссылкой во Вконтакте">Поделиться</li>
						<li class="facebook" title="Поделиться ссылкой на Фейсбуке">Нравится</li>
						<li class="twitter" title="Поделиться ссылкой в Твиттере">Твитнуть</li>
						<li class="odnoklassniki" title="Поделиться ссылкой в Одноклассниках">Класс!</li>
						<li class="mailru" title="Поделиться ссылкой в Моём мире">Нравится</li>
						<li class="plusone" title="Поделиться ссылкой в Гугл-плюсе">Google+</li>
					</ul>
					
					<?if(!$film['Rightholder'] && $film['first_link']  && !$film['error'] &&  $_COOKIE['errorVid']<3){?>
						<div id='erbut' class='block2' title='Нажмите, если видео не работает'>Видео не работает!</div>
							<div id='erfilmform' class='block2'>
							<span title='Закрыть'></span>
							Пожалуйста, опишите проблему:<br />
							<form>
								<textarea name='txt'></textarea>
								<input name='id' type='hidden' value='<?=$film['id']?>' /> 
							</form>
							<a>Отправить</a>
						</div>
					<?}?>
				</div>
			
				<div class='line' style='margin:5px 0px;margin-top:10px'></div>
					<div style="margin-left:25px" id='bannerwrap'>
						<!--noindex--><div id="bfdfcedcca" style="display: none"></div><!--/noindex-->
					</div>
			
				<div class='line' style='margin:10px 0px;'></div>
				
				
					<div id='addcom' class='block2'>Добавить комментарий</div>
					<div id='addcom_form'>
					<?if(user()->isAuth()){?>
						
							<?=$comForm?>
						
						
					<?}else{?>
						<?if(count($comments)<config('need_com_count')){?>
						<?=$comForm?>
						<?}else{?>
							Комментирование доступно только зарегистрированным пользователям
						<?}?>
					<?}?>
					</div>
				<div class='line' style='margin:10px 0px'></div>
				
				
				<div id='comments'>
					<?foreach($comments as $com){?>
						<div>
							<?if($img=\image::get('/uploads/users/'.$com['user_id'].'/image.jpg')){?>
								<img src='<?=$img->cropPath?>' />
							<?}else{?>
								<img src='/img/avatar.jpg' />
							<?}?>
							<?
							$com_date=new DateTime($com['timestamp']);
							?>
							<p class='namedate'><?=htmlspecialchars(($com['user_login'])?$com['user_login']:$com['unregname'])?> <?=$com_date->format('d.m.Y H:i')?></p>
							<?if($com['activate']==0){?><p style='color:#0f0;font-size:12px'>Комментарий на проверке администратором (виден только Вам).</p><?}?>
							<p class='comtxt'><?=htmlspecialchars($com['text'])?></p>
						</div>
					
					<div class='line' ></div>
					<?}?>
					
					
										
				</div>
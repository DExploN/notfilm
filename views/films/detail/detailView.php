
<div class='film_container block2' itemscope itemtype="http://schema.org/Movie">
					<div class='imgdiv'>
						<img  src='<?=$film['img']?>' alt='<?=$film['name']?><?echo ($film['ori'] && $film['anime'])?' / '.$film['ori']:''?><?if($film['year']!='0000' && !$film['anime'] && !$film['serial'] && !$film['show'])echo ' ('.$film['year'].')';?>' title='<?=$film['name']?><?echo ($film['ori'] && $film['anime'])?' / '.$film['ori']:''?><?if($film['year']!='0000' && !$film['anime'] && !$film['serial'] && !$film['show'])echo ' ('.$film['year'].')';?>' itemprop="image" />
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
					<h1 itemprop="name"><?=$film['name']?><?if($film['year']!='0000' && !$film['anime'] && !$film['serial'] && !$film['show'])echo ' ('.$film['year'].')';?><?if(!$film['endSer']){if(($film['serial'] || $film['anime']) && $film['lastSer']){echo " ".(($film['lastSer']>1)?"1-":"").$film['lastSer']." серия";}elseif($film['show'] && ($film['lastSer'] || $film['lastSerDate']!="0000-00-00"))echo ' '.(($film['lastSer'])?$film['lastSer'].' ':'').'выпуск';if($film['lastSerDate']!="0000-00-00"){$film_date=new DateTime($film['lastSerDate']);echo " ".$film_date->format('d.m.Y');} }?></h1>
					<?if($film['serial']){echo "<p><b>Все серии подряд</b></p>";}?>
					<?if(user()->priv('change_film')){?>|<a href='/service/films/change/#<?=$film['id']?>'>Изменить</a>|<?}?>
					<?if(user()->priv('del_film')){?>|<a href='/service/films/delete/#<?=$film['id']?>'>Удалить</a>|<?}?>
					<?if($film['lastSerDate']!="0000-00-00"){
						$film_date=new DateTime($film['lastSerDate']);
						if($film_date->format('d.m.Y')==date('d.m.Y'))$segodnvip='(сегодняшний) '; 
					}?> 
					<?if($film['lastSer']){?>
						<p  class='lser'><b><?if($film['show']){?>Последний <?=$segodnvip?>выпуск:<?}else{?>Последняя серия:<?}?></b> <?=$film['lastSer']?>  <?if($film['lastSerDate']!="0000-00-00"){$film_date=new DateTime($film['lastSerDate']);echo "от ".$film_date->format('d.m.Y'); }?> </p>
					<?}?>
					<?if(!$film['lastSer'] && $film['show'] && $film['lastSerDate']!="0000-00-00"){?>
						<p  class='lser'><b>Последний <?=$segodnvip?>выпуск</b>: <?$film_date=new DateTime($film['lastSerDate']);echo $film_date->format('d.m.Y')?>  <?if($film['endSer']){/*echo "(все выпуски)"*/;}?></p>
					<?}?>
					
					<?if(!$film['lastSer'] && ($film['serial'] || $film['anime'])  && $film['lastSerDate']!="0000-00-00"){?>
						<p><b>Последняя серия</b>: <?$film_date=new DateTime($film['lastSerDate']);echo $film_date->format('d.m.Y')?>  <?if($film['endSer']){echo "(все серии)";}?></p>
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

					<?	
					if(count($film['multicat'])){
						$i=0;
						?>
						<p><b>Списки</b>:
						<?foreach($film['multicat'] as $multicat){
							if($i){echo ', ';}
							?><a href='/multicat/<?=$multicat['id']?>-<?=$multicat['url']?>/'><?=$multicat['name']?></a><?
							$i++;}?>
						</p>	
					<?}?>
				
					
					<?if($film['ori']){?>
						<p><b>Оригинальное название</b>: <span itemprop="alternativeHeadline"><?=$film['ori']?></span></p>
					<?}?>
					<?if($film['year']!='0000'){?>
						<p><b>Год</b>: <a href='/year/<?=$film['year']?>/'><?=$film['year']?></a></p>
					<?}?>
					<?if($film['dateRf']!='0000-00-00'){?>
						<p><b>Дата выхода в РФ</b>: 
						<?
							$film_date=new DateTime($film['dateRf']);
							echo $film_date->format('d.m.Y')
						?>
						</p>
					<?}?>
					<?if($film['director'] && $film['director']!='-'){?>
						<p><b>Режиссер</b>: <span><?=$film['director']?></span></p>
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
						<?/*<p><b>Качество</b>: <?=$quality[$film['quality']]?></p>*/?>
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
					
					<?if($film['text']){?>
						<div id='h2film'><h2>Описание <?if($film['show']){?>передачи <?}?><?if($film['anime']){?>аниме <?}?><?if($film['serial']){?>сериала <?}?><?if($film['mult']){?>мультфильма <?}?><?if(!$film['mult'] && !$film['anime'] && !$film['serial'] && !$film['show']){?>фильма <?}?>«<?=$film['name']?>»:</h2></div>
					
						
						<p class='detdesc' itemprop="description" > <?=nl2br(str_replace("\r\n\r\n","\r\n",$film['text']))?> 
						</p>
					<?}?>
					<center><div id='tocom' class='block_out'><a href='#addcom' class='block_in'>Оставить комментарий</a></div>(Всем интересно узнать ваше мнение)</center>
		
					<div class='date'>
					<?
						$film_date=new DateTime($film['updateTimestamp']);
						echo $film_date->format('d.m.Y');
					?>
					</div>
				</div>
									

<!--noindex-->
	<?if(!$isMobile){?>
	
	
	<div id='vib' style="text-align:center;margin-bottom:5px;overflow:hidden;">
		<div id="beseed_rotator" style='overflow:hidden'></div>
		<script type='text/javascript' id="s-9833f32cfddcfcf1">!function(t,e,n,o,a,c,s){t[a]=t[a]||function(){(t[a].q=t[a].q||[]).push(arguments)},t[a].l=1*new Date,c=e.createElement(n),s=e.getElementsByTagName(n)[0],c.async=1,c.src=o,s.parentNode.insertBefore(c,s)}(window,document,"script","//greeentea.ru/player/","vbm"); vbm('get', {"platformId":30451,"format":2,"align":"bottom","width":"610","height":"343","sig":"9833f32cfddcfcf1"});</script>		
		<div class='line' style='margin-top:6px;margin-bottom:6px'></div>
		
		<div style='margin-left:5px;overflow:hidden;'>
			<script type='text/javascript' id='s-d6229d48d902443ecfd04dc05c6d3036'>(function() { var s = document.getElementById('s-d6229d48d902443ecfd04dc05c6d3036'); s.id = +new Date()+Math.floor(Math.random()*1000)+'-vseed'; var v = document.createElement('script'); v.type = 'text/javascript'; v.async = true; v.src = 'https://ytimgg.com/oO/rotator?align=2&height=360&width=600&key=d6229d48d902443ecfd04dc05c6d3036&pid=25993&csid='+s.id; v.charset = 'utf-8'; s.parentNode.insertBefore(v, s); })(); </script>
		</div>
	</div>
	

	<?}?>
<!--/noindex-->	
		
	
				<div class='line' style='margin-top:6px;margin-bottom:6px'></div>
		
		

				<div class='rec_title'>Рекомендуем <?if($perelinkone['id']){?><a class='perelinkone' href='/film/<?=$perelinkone['id']?>-<?=$perelinkone['url']?>/'>смотреть онлайн «<?=$perelinkone['name']?>»<?if($perelinkone['year']!='0000' && !$perelinkone['anime'] && !$perelinkone['serial'] && !$perelinkone['show'])echo ' ('.$perelinkone['year'].')';?></a><?}?> а так же:</div>
				
				<div class='films_perelink rec'>
					<?foreach($perelink as $item){?>
						<a href='/film/<?=$item['id']?>-<?=$item['url']?>/' title='<?=$item['name']?>'><img src='/uploads/films/<?=$item['id']?>/<?=$item['url']?>.jpg' alt='<?=$item['name']?>'  /><?=$item['name']?></a>
					<?}?>
				</div>
			
				<div class='line' style='margin:10px 0px'></div>
				
					
					
						
					<div class='filmstronk'><strong>Смотреть онлайн  <?=$film['name']?><?if($film['show'] && $film['channel']){?> (<?=$film['channel']?>)<?}?><?if($film['year'] && $film['year']!='0000' && !$film['serial'] && !$film['anime'] && !$film['show']) echo ' ('.$film['year'].')';?><?if(!$film['show'] && !$film['anime']){ echo ' в хорошем качестве';}elseif($film['show']){ echo ' последний выпуск';}elseif($film['anime']){ echo ' русская озвучка';}?></strong></div>
					
					
					<!--noindex-->
										
					<?if($film['Rightholder']){?>
						<div class='block2 RightholderBlock' >Удалено по требованию правообладателя</div>
					<?}else{?>
						
						
									<?if(count($film['video'])){?>
									
											<div id='tr_i_ser'>
												<?if(count($film['video'])){?>
												<div id='ser_out'>	
													<div id='ser' <?if($isMobile)echo "class='mob';"?>>
														<?foreach($film['video'] as $link){?>
															
																	<?/*<div class='item' onclick="loadvideo('<?=$link['link']?>')"><div><?=$link['name']?></div></div>*/?>
																	<div class='item' onclick="golink('<?=$link['link']?>')"><div><?=$link['name']?></div></div>
															
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
													
													<?/*<div id='pleerbg' onclick="this.parentNode.removeChild(this);loadvideo('<?=$film['first_link']?>');"></div>*/?>
													<div id='pleerbg' onclick="golink('<?=$film['first_link']?>');"></div>
													<iframe id='pleer' width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
												</div>	
											<?}elseif($film['first_link']){?>
												<div id='pleercont'>
													<iframe id='pleer' width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
													<script>loadvideo('<?=$film['first_link']?>');</script>
												</div>	
											<?}?>
											<div id='vidwinmes' ><span>ВНИМАНИЕ</span>: видео откроется в новом окне, не закрывайте его</div>

									<?}else{?>
						
						
						
										<?if($film['site_links']){?>
											<div id='tvlinkcontainer'>
											<?foreach($film['site_links'] as $selink){
												?><span class='tvlink' onclick="golink('<?=$selink['link']?>')"><?=$selink['name']?></span> <?
											}?>
											</div>
											
											
										<?}?>	
									
									<?}?>
						
						
					
					<?}?>
				<!--/noindex-->
				
				
					
								
				<div class='socbut socbutfilm'>
					<ul class="social-likes">
						<li class="vkontakte" title="Поделиться ссылкой во Вконтакте">Поделиться</li>
						<li class="facebook" title="Поделиться ссылкой на Фейсбуке">Нравится</li>
						<li class="twitter" title="Поделиться ссылкой в Твиттере">Твитнуть</li>
						<li class="odnoklassniki" title="Поделиться ссылкой в Одноклассниках">Класс!</li>
						<li class="mailru" title="Поделиться ссылкой в Моём мире">Нравится</li>
					</ul>
					
					<?if(!$film['Rightholder'] && !$film['error'] &&  $_COOKIE['errorVid']<3){?>
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
				
				
				<div class='line' style='margin:15px 0px 0px;'></div>
				
				
				
				
				<?if(!count($film['video'])){
					if($film['site_links']){
						if($film['trailer']){?>
							<div id='pleercont' style='margin-top:10px'>
								<iframe id='pleer' width="100%" height="100%" frameborder="0" allowfullscreen></iframe>
								<script>loadvideo('<?=$film['trailer']?>');</script>
							</div>	
						<?}
					}
				}?>
			
				
			
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
							<p class='comtxt'><?=nl2br(htmlspecialchars($com['text']))?></p>
						</div>
					
					<div class='line' ></div>
					<?}?>
					
					
										
				</div>
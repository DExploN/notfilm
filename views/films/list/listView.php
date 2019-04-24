

<?if($soclikes){?>
<div class='socbut' style='margin-bottom:0px;margin-top:0px'><ul class="social-likes">
					<li class="vkontakte" title="Поделиться ссылкой во Вконтакте">Поделиться</li>
					<li class="facebook" title="Поделиться ссылкой на Фейсбуке">Нравится</li>
					<li class="twitter" title="Поделиться ссылкой в Твиттере">Твитнуть</li>
					<li class="odnoklassniki" title="Поделиться ссылкой в Одноклассниках">Класс!</li>
					<li class="mailru" title="Поделиться ссылкой в Моём мире">Нравится</li>
					<li class="plusone" title="Поделиться ссылкой в Гугл-плюсе">Google+</li>
			</ul></div>
<?}?>
<?
if($actorSearch){?>

<div id='actorSearch' >
				<form action='/actor/'>
					<input type='text' class='stxt' placeholder='Поиск по актеру' name='search'/>
					<input type='submit' class='ssub' value=''/>
				</form>
</div>

<?}
?>
<div class='line' style='margin-bottom:5px;margin-top:15px'></div>


<?if($animegenrelist){?>
	<h2 class='list'>Жанры аниме:</h2>
	<ul id='animegenrelist'>
	<?
	foreach($animegenrelist as $row){?>
		<li><a href='/genre/<?=$row['id']?>-<?=$row['url']?>/'><?=$row['name']?></a></li>

	<?}?>
	</ul>
<?}?>




<h2 class='list'>
<?
echo ($h2list)?$h2list:'Список фильмов';
?>
:
</h2>

<?if($startSort){?>
	<div id='sortform'><b>Сортировать по:</b> 
		<?
			
				if(core()->route->action!='byYear')$sorts[1]='Году';
				$sorts[6]='Дате добавления';
				$sorts[2]='Алфавиту';
				$sorts[3]='Рейтингу';
				$sorts[4]='Просмотрам';
				$sorts[5]='Комментариям';
				
				
				if(!$_COOKIE['sort'])$_COOKIE['sort']=$startSort;
				if(!$_COOKIE['sortType'])$_COOKIE['sortType']=1;
				foreach($sorts as $key=>$val){
					?>
					<?if( $_COOKIE['sort']==$key){?><span class='sortArrow'><?if($_COOKIE['sortType']==1){?> &#8595;<?}else{?>&#8593;<?}?></span><?}?>
					<span <?if($_COOKIE['sort']==$key){?>class='sortactivate'<?}?> onclick="listsort(<?=$key?>,<?if($_COOKIE['sort']==$key && $_COOKIE['sortType']==1){?>2<?}else{?>1<?}?>)"><?=$val?></span>
					<?
				}
			
		?>
	
	</div>
	
<?}?>
<?if($genbysel){?>
		<div id='genreSort'>
			<b>Фильтровать по жанру:</b> 
			<select  onchange='setFilter(<?=$id?>,this.value)'>
					<option value='0'>-</option>
					<?foreach($genbysel as $genre){?>
						<option <?if($genre['id']==$genre_id)echo 'selected="selected"';?> value='<?=$genre['id']?>'><?=$genre['name']?></option>
					<?}?>
			</select>
		</div>	
	<?}?>	


	
<?foreach($list as $film){?>
		<div class='film_container block2'>
					<div class='imgdiv'>
						
						<img src='/uploads/films/<?=$film['id']?>/<?=$film['url']?>.jpg' alt='<?=$film['name']?>' class='imglist'/>
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
					<p class='title'><a href='/film/<?=$film['id']?>-<?=$film['url']?>/'><?=$film['name']?></a></p>
					
					
					<?if($film['lastSer']){?>
						<p class='lser'><b><?if($film['show']){?>Последний выпуск:<?}else{?>Последняя серия:<?}?></b> <?=$film['lastSer']?>  <?if($film['lastSerDate']!="0000-00-00"){$film_date=new DateTime($film['lastSerDate']);echo "от ".$film_date->format('d.m.Y'); }?> <?if($film['endSer']){if($film['show']){/*echo "(все выпуски)";*/}else{echo "(все серии)";}}?></p>
					<?}?>
					
					
					<?if(!$film['lastSer'] && $film['show'] && $film['lastSerDate']!="0000-00-00"){?>
						<p class='lser'><b>Последний выпуск</b>: <?$film_date=new DateTime($film['lastSerDate']);echo $film_date->format('d.m.Y')?> <?if($film['endSer']){/*echo "(все выпуски)";*/}?></p>
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
						/*
						$tmp=explode(",",$film['actors']);
						$film['actors']=array();
						foreach($tmp as $value){
							$value=trim($value);
							$film['actors'][]='<span>'.$value.'</span>';
						}
						$film['actors']=implode(", ",$film['actors']);
						*/
						?>
						<p><b>Актеры</b>: <span class='actors'><?=$film['actors']?></span></p>
					<?}?>
					
					<?if($film['quality']){?>
						<?/*<p><b>Качество</b>: <?=$quality[$film['quality']]?></p>*/?>
					<?}?>
					
					
					
					<?if($film['text']){?>
						<p class='desc'><b>Описание</b>: <?=\str::crop($film['text'],250,'...')?></p>
					<?}?>
					
					<p>
						<a class='button' href='/film/<?=$film['id']?>-<?=$film['url']?>/'><img src='/img/sm_online2.png' alt='Смотреть <?=$film['name']?><?if($film['year']!='0000' && !$film['anime'] && !$film['serial'] && !$film['show'])echo ' ('.$film['year'].')';?> онлайн' /></a>
						<?if($film['comments']){?><span class='comview'><b>Комментариев</b>: <?=$film['comments']?></span><?}?>
					</p>
					
					<div class='date'>
					<?
						$film_date=new DateTime($film['updateTimestamp']);
						echo $film_date->format('d.m.Y');
					?>
					</div>
				</div>
<?}?>
<?echo $page?>
<div class='line' style='margin-bottom:5px;margin-top:15px'></div>

<?if($addbut){?>
	<center class='addoffer' style='margin:10px 0px 0px'>Мы что-то упустили, и Вы знате что? Тогда: <br /><span class='addofferbut block2'>&rarr; Предложите фильмы в данный список &larr;</span></center>
	
	<div id='erfilmform' class='block2'><center>
								<span title='Закрыть'></span>
								
								<form>
									<textarea name='txt' placeholder='Напишите название фильма или нескольких фильмов. Спасибо.'></textarea>
									<input name='name' type='hidden' value='<?=$addbut?>' /> 
								</form>
								<a>Отправить</a>
							</center></div>
<?}?>	




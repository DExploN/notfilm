<div class='films_perelink rec'>
<?
$i=0;
foreach($list as $film){
	if( $i>0 && $i%5==0){?><div class='line'></div><?}
	$i++;
		
?>			<?if($lcfilms){?>
				<div class='lc'>
					<div class='lcin'>
						<b><?=htmlspecialchars(($film['user_login'])?$film['user_login']:$film['unregname'])?></b>:  <?=htmlspecialchars(\str::crop($film['text'],60,'...'))?>
						
					</div>
			<?}?>
			<a href='/film/<?=$film['id']?>-<?=$film['url']?>/' title='<?if(!$lcfilms){?>Смотреть <?}?><?=$film['name']?><?if(!$lcfilms && $film['year']!='0000' && !$film['anime'] && !$film['serial'] && !$film['show'])echo ' ('.$film['year'].')';?><?if(!$lcfilms){?> онлайн<?}?>'><img src='/uploads/films/<?=$film['id']?>/<?=$film['url']?>.jpg' alt='<?=$film['name']?><?if(!$lcfilms && $film['year']!='0000' && !$film['anime'] && !$film['serial'] && !$film['show'])echo ' ('.$film['year'].')';?>' /><?=$film['name']?><?if(!$lcfilms && $film['year']!='0000' && !$film['anime'] && !$film['serial'] && !$film['show'])echo ' ('.$film['year'].')';?></a>
			
			<?if($lcfilms){?>
				</div>
			<?}?>
<?}?>					
</div>
<?=$page?>
					
			
<div style="margin-bottom:25px" id='bannerwrap'>
						<!--noindex--><div id="bfdfcedcca" style="display: none"></div><!--/noindex-->
					</div>
<div class='films_perelink rec'>
<?
$i=0;
foreach($list as $selection){
	if( $i>0 && $i%5==0){?><div class='line'></div><?}
	$i++;
		
?>
			<a href='/selection/<?=$selection['id']?>-<?=$selection['url']?>/'><img src='/uploads/selection/<?=$selection['id']?>/<?=$selection['url']?>.jpg' /><?=$selection['name']?></a>
		
<?}?>					
</div>
<?=$page?>

<?if($pagenum==1){?>
<div class='line'></div>

<p><a href='/multicat/'>Списки</a></p>
<div class='line'></div>
<?}?>
					
			
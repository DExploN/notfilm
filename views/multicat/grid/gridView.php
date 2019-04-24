<div style="margin-bottom:25px" id='bannerwrap'>
						<!--noindex--><div id="bfdfcedcca" style="display: none"></div><!--/noindex-->
					</div>
<div class='films_perelink rec'>
<div class='line'></div>
<?
$i=0;
foreach($list as $selection){
	if( $i>0 && $i%5==0){?><div class='line'></div><?}
	$i++;
		
?>
			<a href='/multicat/<?=$selection['id']?>-<?=$selection['url']?>/'><?=$selection['name']?></a>
		
<?}?>
<div class='line'></div>					
</div>
<?=$page?>

					
			
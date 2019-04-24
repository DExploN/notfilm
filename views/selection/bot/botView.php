<div id="bot_panel" class='block_out'>
	<div class='block_in'>
		<table width="100%" border='0'>
			<tr>
				<?
				foreach($list as $selection){?>
					<td class='im'>
						<a href='/selection/<?=$selection['id']?>-<?=$selection['url']?>/'><img src='/uploads/selection/<?=$selection['id']?>/<?=$selection['url']?>.jpg' /></a>
					</td>
					<td>
						<a href='/selection/<?=$selection['id']?>-<?=$selection['url']?>/'><?=\str::crop($selection['name'],35,'')?></a>
					</td>
				<?}	?>
			</tr>
		</table>
	</div>
</div>
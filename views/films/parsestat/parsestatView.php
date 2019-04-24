<a target='blank' href='/action/films/refreshparsestat/'>Сбросить время у ошибочных</a>
<table border='1' cellpadding='3' width='100%'>
	<tr>
		<td>Название</td>
		
		<td>Статус</td>
		<td>Прокси</td>
	
		<td >Дата</td>
		
	</tr>
<?foreach($list as $film){?>
	<tr>
		<td><a style='color:#fff' target='_blank' href='/film/<?=$film['id']?>-<?=$film['url']?>/'><?=$film['name']?></a> </td>
		
		<td><?=($film['status'])?"Успешно":"Ошибка"?></td>
		<td><?=$film['proxy']?></td>
		<td><?=$film['timestamp']?></td>
	</tr>
	
<?}?>
</table>
<br />

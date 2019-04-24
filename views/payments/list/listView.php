<table border='1' cellpadding='3' width='100%'>
	<tr>
		<td width='60'>Дата выплаты</td>
		<td width='50'>Заявлено выплатить</td>
		<td width='50'>Размер выплаты</td>
		<td width='50'>Статус</td>
		<td>Ваше примечание</td>
		<td>Примечание администратора</td>
	</tr>

<?foreach($list as $row){?>
	<tr>
		<td><?=$row['timestamp']?></td>
		<td><?=$row['money']?> р.</td>
		<td><?=$row['paid']?> р.</td>
		<td>
			<?
			 switch($row['status']){
				case '0':
					echo "Рассматривается";
					break;
				case '1':
					echo "Выплачено";
					break;	
				case '2':
					echo "Отклонено";
					break;		
			 }
			?>
			
		</td>
		<td><?=$row['text']?></td>
		<td><?=$row['admin_text']?></td>
	</tr>
<?}?>

</table>
<br />
<?=$page?>
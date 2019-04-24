<table border='1' cellpadding='3' width='100%'>
	<tr>
		<td width='60'>Дата выплаты</td>
		<td width='1'>Логин</td>
		<td width='1'>Заяв. выпл.</td>
		<td width='1'>Разм. выпл.</td>
		<td width='1'>Статус</td>
		<td>Примечание</td>
		<td>Примечание админа</td>
	</tr>

<?foreach($list as $row){?>
	<tr>
		<td><?=$row['timestamp']?></td>
		<td><?=$row['user_login']?></td>
		<td><?=$row['money']?> р.</td>
		<td><?=$row['paid']?> р.</td>
		<td>
			<?
			 switch($row['status']){
				case '0':
					echo "Рассм.";
					break;
				case '1':
					echo "Выпл.";
					break;	
				case '2':
					echo "Откл.";
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
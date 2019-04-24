
<form method='post' action='/service/journal/'>

<table border=0 cellpadding='5'>

	<tr>
		<td>Пользователь: </td>
	<td>
	<select name='login'>
		<?foreach($users_list as $user){
		?>
		<option value=<?=$user['login']?> <?if($user_login==$user['login']) echo 'selected="selected"'; ?> ><?=$user['login']?></option>
		<?
		}?>
	</select>
	<input type='submit' value='Выбрать' />
	</td>
	</tr>
</table>
</form>
<table border='1' cellpadding='3' width='100%'>
	<tr>
		<td width='60' >Время</td>
		<td width='1'>Пользователь</td>
		<td >Действие</td>
	</tr>
<?
	foreach($list as $row){?>
		<tr>
		<td><?=$row['timestamp']?></td></td>
		<td><?=$row['login']?></td>
		<td><?=$row['text']?></td>
	</tr>
	<?}
?>
</table>
<br />
<?=$page?>
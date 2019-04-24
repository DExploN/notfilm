<table border='1' cellpadding='3' width='100%'>
	<tr>	
		
		<td width='60'>Дата заявки</td>
		<td width='60'>Логин</td>
		<td width='50'>Заявлено выплатить</td>
		<td>Примечание</td>
		<td width='1'></td>
		<td width='1'></td>
	</tr>

<?foreach($list as $row){?>
	<tr>
		<td><?=$row['timestamp']?></td>
		<td><?=$row['user_login']?></td>
		<td><?=$row['money']?> р.</td>
		<td><?=$row['text']?></td>
		<td> <form method='post'><input type='hidden' name='status' value='1' /><input type='hidden' name='id' value='<?=$row['id']?>' /><input type='submit' value='Выплатить'></form></td>
		<td> <form method='post'><input type='hidden' name='status' value='2' /><input type='hidden' name='id' value='<?=$row['id']?>' /><input type='submit' value='Отказать'></form></td>
	</tr>
<?}?>

</table>
<br />
<?=$page?>
<br /> <br/>
<?if($pay){?>

	<form action='/action/payments/success/' method='post'>
	<input type='hidden' name='id' value='<?=$pay['id']?>' />
	<table border=0 cellpadding='5' >
		
		<tr>
			<td width='100'>Пользователь</td>
			<td><?=$pay['user_login']?></td>
		</tr>
		
		<tr>
			<td>Сумма заявки</td>
			<td><?=$pay['money']?></td>
		</tr>
		
		<tr>
			<td>Примечание</td>
			<td><?=$pay['text']?></td>
		</tr>	
		
		<tr>
			<td>Статус</td>
			<td>
			<?if($pay['status']==1){?>
			Выплатить
			
			<?}?>
			<?if($pay['status']==2){?>
			Отказать
			<?}?>
			<input type='hidden' name='status' value='<?=$pay['status']?>' />
			</td>
		</tr>
		<?if($pay['status']==1){?>
			<tr>
				<td>Сумма к выплате</td>
				<td>
					<input type='text' name='paid' value='<?=$pay['money']?>' />
					<br />
					Максимум: <span><?=$pay['maxpay']?> р. (Баланс пользователя)</span>
				</td>
			</tr>
		<?}?>
		<tr>
			<td>Примечание</td>
			<td><textarea style='width:390px;height:70px' name='admin_text' ></textarea></td>
		</tr>			
		
		<tr>
			<td></td>
			<td><?=$captcha->img?></td>
		</tr>	
		
		<tr>
			<td>Введите код</td>
			<td><?=$captcha->form?></td>
		</tr>
		<tr>
			<td></td>
			<td><input type='submit' value='Отправить'></td>
		</tr>

	</table>
	</form>

<?}?>
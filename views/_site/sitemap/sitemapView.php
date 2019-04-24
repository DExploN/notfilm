<form method='post' action='/action/_site/sitemap/' enctype="multipart/form-data">
<table border=0 cellpadding='5'>

	<tr>
		<td>Карта сайта </td>
		<td><input type='file' name='sitemap' /></td>
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
		<td><input type='submit' value='Загрузить'></td>
	</tr>	


</table>
</form>
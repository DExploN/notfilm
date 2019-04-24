<!--noindex-->
<?
$min_com_len=($unregname || !$cash)?config('min_unreg_com_len'):config('min_com_len');

?>
<?if($add==true){?>
	<form action='/action/comments/<?=($unregname)?'uradd':'add'?>/' method='post'>
<?}elseif($change==true && !$changeMy){?>
<form action='/action/comments/change/' method='post'>
<?}elseif($changeMy==true){?>

<form action='/action/comments/changeMy/' method='post' >
<?}?>
<a name="com"></a>
<table cellpadding='3' border='0' width='95%'>
	<?if($add==true){?>
			<input type='hidden' name='film_id' value='<?=$film_id?>' />
	<?}elseif($change==true){?>
			<input type='hidden' name='id' value='<?=$com['id']?>' />
			<input type='hidden' name='film_id' value='<?=$com['film_id']?>' />
	<?}?>
	<tr>
		<td>
			<?if($cash){?>
				Комментарий оплачиваемый
				<input type='hidden' name='cash' value='<?=$cash?>' />
			<?}elseif($add==true){?>
				<?//Комментарий НЕоплачиваемый (Оплачивемые комментарии расположены в разделе "Оплачиваемые комментарии" )?>
			<?}?>
		</td>
	</tr>
	
	<?if($com['unregname'] || $unregname){?>
		<tr>
			<td><input type='text' size='20' class='com_name' name='unregname'  placeholder='Введите имя.' value='<?=($com['unregname']===true)?'':$com['unregname'];?>' /></td>
		</tr>	
		<?}?>
	<tr>
		
		<td><textarea class='com_area' name='text' placeholder='Текст комментария. <?if($cash){?>ВНИМАНИЕ! Комментарий не должен быть сплошной "водой", пишите конкретно про данное кинопроизведение. <?}?>Комментарий должен быть написан лично Вами, иметь не менее <?=$min_com_len?> символов, быть без грамматических и пунктуационных ошибок.'><?=$com['text']?></textarea>
		<div class='com_area_len'></div>
		
		</td>
	</tr>
	
	<tr>
		<td>
		<?if($change==true){?>
		<?if(!$changeMy){?>Активированный <input type='checkbox' name='activate' value='1'  <?if($com['activate']){?>checked='checked' <?}?>/><br /><?}?>
		Удалить <input type='checkbox' name='delete' value='1' />
		<?}?>
		</td>
	</tr>
	<tr>
		<td>
		<?=$captcha->img?>
		</td>
	</tr>
	<tr>
		<td>
		<?=$captcha->form?>
		</td>
	</tr>		
	<tr>
		<td>
		<input type='submit' id='com_sub' value='Отправить' />
		</td>
	</tr>		
	
</table>	
</form>
<?if($changeMy==true || $add==true){?>
	<script type='text/javascript'>
								$('#com_sub').on('click',function(){
									<?if(!$unregname && $add!=true){?>
										if($("[name=delete]").prop("checked"))return true;
									<?}?>
									var str=$('.com_area').val();
									if(str.length<<?=$min_com_len?> ){
										alert('Ошибка: Комментарий должен иметь не менее <?=$min_com_len?> символов');
										return false;
									}
									<?if($unregname){?>
									var str=$('.com_name').val();
									if(str.length<<?=config('min_com_name_len')?> ){
										alert('Ошибка: Имя должно иметь не менее <?=config('min_com_name_len')?> символов');
										return false;
									}
									<?}?>
								})
								
	</script>
<?}?>	
<!--/noindex-->
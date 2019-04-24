<?if(core()->route->action=='errorvid' && core()->route->controller=='films'){?>
		<form method='post' action='/action/films/errorOff/'><input type='hidden' name='all' value='1'><input type='submit' value='Исправить все' style='cursor:pointer'  /> </form>
	<?}?>
	
	
<?if(core()->route->action=='searchpleer' && core()->route->controller=='films'){?>
		<form method='GET' >Плеер: <input type='text' name='pleer' value='<?=$_GET['pleer']?>' /> Инверсия: <input type='checkbox' name='inv' <?if($_GET['inv'])echo "checked='checked'";?>  />  <input type='submit' value='Поиск' /></form>
	<?}?>	

<?if(core()->route->action=='changedonline' && core()->route->controller=='films'){?>
		<a href='/action/films/setonline/'>Сменить статус на "онлайн"</a><br/><br/>
<?}?>	
	
<table border='1' cellpadding='3' width='100%'>
	<tr>
		<td>Название</td>
		<?if(core()->route->action=='errorvid' && core()->route->controller=='films'){?>
		<td >Ошибка</td>
		<?}?>
		<td ></td>
	
		<td ></td>
		
	</tr>
<?foreach($list as $film){?>
	<tr>
		<td><a style='color:#fff' href='/film/<?=$film['id']?>-<?=$film['url']?>/'><?=$film['name']?></a>  <?if($film['lastSer']) echo $film['lastSer']." серия";?></td>
		
		<?if(core()->route->action=='errorvid' && core()->route->controller=='films'){?>
		<td ><?=$film['error_text']?></td>
		<?}?>
		
		<?if($film['dateRf']){?>
		<td width='70'><?=$film['dateRf']?></td>	
		<?}?>
		<td width='1'><form method='post' action='/service/films/change/' target='_blank'><input type='hidden' name='id' value='<?=$film['id']?>'><input type='submit' value='Открыть' /> </form></td>
		<?if(core()->route->action=='errorvid' && core()->route->controller=='films'){?>
		<td width='1' class='id_<?=$film['id']?>'><form method='post' action='/action/films/errorOff/'><input type='hidden' name='id' value='<?=$film['id']?>'><input type='button' value='Исправлено' style='cursor:pointer'  onclick="erroroff('<?=$film['id']?>')" /> </form></td>
		<?}?>
		
	</tr>
	
<?}?>
</table>
<br />
<?if(core()->route->action=='errorvid' && core()->route->controller=='films'){?>
	<script>
		function erroroff(id){
			$.ajax({
				  type: "POST",
				  url: '/action/films/errorOff/',
				  data: {'id':id},
				  success: function(res){
					$(".id_"+id).empty();
				  },
			});
			
		}
	</script>
<?}?>
<?=$page?>
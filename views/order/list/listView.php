<form method='post' action='/action/offer/orderOff/'><input type='hidden' name='all' value='1'><input type='submit' value='Исправить все' style='cursor:pointer'  /> </form>
	

<table border='1' cellpadding='3' width='100%'>
	<tr>
		<td>Название</td>
		<td>Текст</td>
		<td ></td>
	</tr>
<?foreach($list as $item){?>
	<tr>
		<td><?=$item['name']?></td>
		<td><?=$item['text']?></td>
		
		<td width='1' class='id_<?=$item['id']?>'><form method='post' action='/action/offer/orderOff/'><input type='hidden' name='id' value='<?=$item['id']?>'><input type='button' value='Убрать' style='cursor:pointer'  onclick="erroroff('<?=$item['id']?>')" /> </form></td>

		
	</tr>
	
<?}?>
</table>
<br />

	<script>
		function erroroff(id){
			$.ajax({
				  type: "POST",
				  url: '/action/offer/orderOff/',
				  data: {'id':id},
				  success: function(res){
					$(".id_"+id).empty();
				  },
			});
			
		}
	</script>

<?=$page?>
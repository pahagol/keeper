<div class="quickview">
	<table class="table">
		<tr>
			<td>Категория:</td>
			<td>
				<select type="text" name="categoryId" class="select-add">
					<? foreach ($categories as $category): ?>
						<option value="<?=$category->id;?>"><?=$category->name;?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Кого:</td>
			<td>
				<select type="text" name="ownerId" class="select-add">
					<? foreach ($owners as $owner): ?>
						<option value="<?=$owner->id;?>"><?=$owner->name;?></option>
					<? endforeach; ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Дата:</td>
			<td>
				<input id="datepicker" type="text" name="dateAdd" value="<?=$now?>" readonly />
			</td>
		</tr>
		<tr>
			<td>Название:</td>
			<td>
				<input id="dictionaryExpenseName" type="text" name="name" onkeyup="moduleAdd.clear(event)" />
				<input id="dictionaryExpenseNameId" type="hidden" value="0" />
			</td>
		</tr>
		<tr>
			<td>Расход:</td>
			<td>
				<input type="text" name="price" autocomplete="off" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<button class="submit-add" onclick="moduleAdd.save()">Сохранить</button>
			</td>
		</tr>
	</table>
</div>
<script type="text/javascript">
$(function() {	
	$('#dictionaryExpenseName').autocomplete({
		serviceUrl:'/add/searchDictionaryExpenseName',
		minChars: 3,
		onSelect: function(value, data) {
		// onSelect: function(value, dictionaryExpenseNameId) {	
			$('#dictionaryExpenseNameId').val(data);
			// moduleAdd.addDictionaryExpenseName(input, value, dictionaryExpenseNameId);
		},
		//	delimiter: /(,|;)\s*/, // regex or character
		maxHeight:400,
		width:300,
		deferRequestBy: 300, //miliseconds
	});
});
</script>

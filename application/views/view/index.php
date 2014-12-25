<div style="width: 100%; height: 40px; background-color:grey">
	<select class="right" style="width:100px;margin:10px;background-color:#fff" onchange="moduleView.changePeriod(this)">
		<? foreach ($dates as $date): ?>
			<option value="<?=$date['value']?>"<?=$date['current'] ? ' selected' : ''?>><?=$date['html']?></option>
		<? endforeach ?>
	</select>
</div>
<div class="clear"></div>
<? foreach ($groups as $date => $group): ?>
	<div class="data-item">
		<table>
			<tr>
				<td colspan="4"><?=$date?></td>
			</tr>
		<? foreach ($group as $expense): ?>
			<tr>
				<td align="left">
					<select onchange="moduleView.saveSelect(this, <?=$expense->id?>, 'categoryId')">
					<? foreach ($categories as $category): ?>
						<option value="<?=$category->id?>"<? if ($category->id == $expense->categoryId):?> selected<?endif?>><?=$category->name?></option>
					<? endforeach ?>
					</select>
				</td>
				<td align="left">
					<input type="text" class="dictionaryExpenseName" name="dictionaryExpenseName" value="<?=$expense->name?>" data-dictionaryExpenseNameId="<?=$expense->dictionaryExpenseNameId?>" data-id="<?=$expense->id?>" onkeyup="moduleView.enterAddDictionaryExpenseName(this, event)"/>
				</td>
				<td align="left">
					<input type="text" name="price" value="<?=$expense->price?>" data-id="<?=$expense->id?>" data-value="<?=$expense->price?>" onkeyup="moduleView.savePriceInput(this, '#price-sum-<?=$date?>', event)" />
				</td>
				<td class="view-action" onclick="moduleView.del(<?=$expense->id?>)" title="Удалить"></td>
			</tr>
		<? endforeach ?>
			<tr>
				<td align="left" colspan="2">
					<span>Сумма</span>
				</td>
				<td align="left" colspan="2">
					<span id="price-sum-<?=$date?>"></span>
					<script type="text/javascript">
    					moduleView.sumPrice('#price-sum-<?=$date?>');
    				</script>
				</td>
			</tr>
		</table>
	</div>
<? endforeach; ?>
<script type="text/javascript">
$(function() {	
	$('.dictionaryExpenseName').each(function() {
		var input = $(this);

		$(this).autocomplete({
			serviceUrl:'/add/searchDictionaryExpenseName',
			minChars: 3,
			onSelect: function(value, dictionaryExpenseNameId) {
				moduleView.addDictionaryExpenseName(input, value, dictionaryExpenseNameId);
			},
			extraParams: {
				id: $(this).attr('data-id')
			},
			
			maxHeight:400,
			width:300,
			deferRequestBy: 300 //miliseconds
			// appendTo: ''
		});
	});
});
</script>


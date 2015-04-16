<div class="subpanel">
	<select class="right" onchange="moduleView.changePeriod(this)">
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
				<td class="block-data-show" colspan="3"><?=$date?> <?=Project::getWeekDay($date)?></td>
				<td class="view-action button-green" onclick="moduleView.add('<?=$date?>')" title="Добавить"></td>
			</tr>
		<? $count = is_array($group) ? count($group) : 0 ?>
		<? if (is_array($group)): ?>
			<? foreach ($group as $expense): ?>
				<tr id="tr-expense-<?=$expense->id?>">
					<td align="left">
						<select onchange="moduleView.saveSelect(this, <?=$expense->id?>, 'categoryId')">
						<? foreach ($categories as $category): ?>
							<option value="<?=$category->id?>"<? if ($category->id == $expense->categoryId):?> selected<?endif?>><?=$category->name?></option>
						<? endforeach ?>
						</select>
					</td>
					<td align="left">
						<input type="text" class="dictionaryExpenseName" name="dictionaryExpenseName" data-value="<?=$expense->name?>" value="<?=$expense->name?>" data-dictionaryExpenseNameId="<?=$expense->dictionaryExpenseNameId?>" data-id="<?=$expense->id?>" onkeyup="moduleView.enterAddDictionaryExpenseName(this, event)"/>
					</td>
					<td class="l-td" align="left">
						<input type="text" name="price" value="<?=$expense->price?>" data-id="<?=$expense->id?>" data-value="<?=$expense->price?>" onkeyup="moduleView.pressPriceInput(this, '#price-sum-<?=$date?>', event)" onblur="moduleView.savePriceInput(this, '#price-sum-<?=$date?>')" />
					</td>
					<td class="view-action button-red" onclick="moduleView.del(<?=$expense->id?>, '#price-sum-<?=$date?>')" title="Удалить"></td>
				</tr>
			<? endforeach ?>
		<? endif ?>
		<? for ($i = $count; $i < Project::MAX_COUNT_RECORD_IN_DAY; $i++): ?>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="l-td">&nbsp;</td>
				<td class="view-action" style="cursor:default">&nbsp;</td>
			</tr>
		<? endfor ?>
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
	<? if (($week = Project::getFormatDate('w', $date)) && $week % 6 == 0): ?>
		<div class="clear"></div>
	<? endif ?>
<? endforeach ?>
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
			deferRequestBy: 300
		});
	});
});
</script>
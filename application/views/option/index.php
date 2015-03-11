<div class="data-item">
	<table>
		<tr>
			<td colspan="2">Названия:</td>
		</tr>
	<? if ($dictionaries): ?>
		<? foreach ($dictionaries as $dictionary): ?>
		<tr id="tr-dictionaryExpenseName-<?=$dictionary->id?>">
			<td align="left">
				<input type="hidden" name="current" value="<?=$dictionary->name;?>">
				<input type="text" value="<?=$dictionary->name?>" onfocus="moduleOption.focusInput(this)" onblur="moduleOption.saveInput(this, <?=$dictionary->id?>, 'dictionaryExpenseName')" onkeyup="moduleOption.pressInput(this, <?=$dictionary->id?>, 'dictionaryExpenseName')">
			</td>
			<td class="view-action button-red" onclick="moduleOption.delDialog(<?=$dictionary->id?>, 'dictionaryExpenseName')" title="Удалить"></td>
		</tr>
		<? endforeach; ?>
	<? endif ?>
		<tr>
			<td colspan="2">
				<input type="text" name="dictionaryExpenseName" onblur="moduleOption.add(this, 'dictionaryExpenseName')" placeholder="Название" />
			</td>
		</tr>
	</table>
</div>
<div class="data-item">
	<table>
		<tr>
			<td colspan="2">Категории:</td>
		</tr>
	<? if ($categories): ?>
		<? foreach ($categories as $category): ?>
		<tr id="tr-category-<?=$category->id?>">
			<td align="left">
				<input type="hidden" name="current" value="<?=$category->name;?>">
				<input type="text" value="<?=$category->name?>" onfocus="moduleOption.focusInput(this)" onblur="moduleOption.saveInput(this, <?=$category->id?>, 'category')" onkeyup="moduleOption.pressInput(this, <?=$category->id?>, 'category')">
			</td>
			<td class="view-action button-red" onclick="moduleOption.delDialog(<?=$category->id?>, 'category')" title="Удалить"></td>
		</tr>
		<? endforeach; ?>
	<? endif ?>
		<tr>
			<td colspan="2">
				<input type="text" name="category" onblur="moduleOption.add(this, 'category')" placeholder="Категория" />
			</td>
		</tr>
	</table>
</div>
<div class="data-item">
	<table>
		<tr>
			<td colspan="2">От кого:</td>
		</tr>
	<? if ($owners): ?>
		<? foreach ($owners as $owner): ?>
		<tr id="tr-owner-<?=$owner->id?>">
			<td align="left">
				<input type="hidden" name="current" value="<?=$owner->name;?>">
				<input type="text" value="<?=$owner->name?>" onfocus="moduleOption.focusInput(this)" onblur="moduleOption.saveInput(this, <?=$owner->id?>, 'owner')" onkeyup="moduleOption.pressInput(this, <?=$owner->id?>, 'owner')">
			</td>
			<td class="view-action button-red" onclick="moduleOption.delDialog(<?=$owner->id?>, 'owner')" title="Удалить"></td>
		</tr>
		<? endforeach; ?>
	<? endif ?>
		<tr>
			<td colspan="2">
				<input type="text" name="owner" onblur="moduleOption.add(this, 'owner')" placeholder="От кого" />
			</td>
		</tr>
	</table>
</div>

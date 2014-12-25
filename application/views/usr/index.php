<div class="quickview">
	<table class="table" style="width:150px;margin:0">
		<tr>
			<td>Пароль:</td>
			<td align="left">
				<input type="password" name="password" />
			</td>
		</tr>
		<tr>
			<td>Показать:</td>
			<td align="left">
				<input type="checkbox" onchange="moduleUsr.toggleInputPassword()" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input class="submit-add" type="button" onclick="moduleUsr.save()" value="Изменить" />
			</td>
		</tr>
	</table>
</div>

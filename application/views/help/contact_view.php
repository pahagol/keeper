<style>
	input,textarea{
		border-radius:3px;
		border: 1px solid lightgray;
	}
	input{
		width:100px;
	}
</style>
<div style="background: white;">
    <h2 class="ico_mug">Обратная связь</h2>
    <? if (validation_errors()): ?><div id="fail" class="info_div"><span class="ico_cancel"></span><?= validation_errors(); ?></div><? endif; ?>
	<? if (!empty($success)): ?><div class="info_div">Письмо успешно отправлено</div><? endif; ?>
    <p>Вы можете оставить нам сообщение в форме обратной связи ниже или связаться с нами по электронной почте.<br/> 
		<span style="font-weight:bold;">Контакты для связи</span> E-mail: support@2056.aratog.com ICQ: 602-599-249</p>
    <form method="POST">
		<table style="margin-left:10px" cellspacing="0">
			<tr>
				<td>Фамилия Имя Отчество:</td>
				<td><input type="text" name="fio" style="width:200px;" size="10" value="<?=$fio?>" /></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input type="text" name="email" style="width:200px;" size="10" value="<?=$email?>" /></td>
			</tr>
			<tr>
				<td>Тема</td>
				<td><input type="text" name="subject" style="width:200px;" size="10" value="<?=$subject?>" /></td>
			</tr>
			<tr>
				<td>Сообщение</td>
				<td><textarea name="text" rows="5" cols="30"></textarea></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" value="Отправить" class="loginbutton" id="save"></td>
			</tr>
		</table>
    </form>
</div>
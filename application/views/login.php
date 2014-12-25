<!DOCTYPE html>
<html>
<head>
<title>Домашняя бухгалтерия</title>
<meta charset="utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="stylesheet" type="text/css" media="all" href="css/login.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<!--script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script-->
<script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="js/keeper.js"></script>
<script type="text/javascript" src="js/widget/ajax.js"></script>
<script type="text/javascript" src="js/widget/dialog.js"></script>
<script type="text/javascript" src="js/modules/login.js"></script>
</head>
<body onkeypress="keeper.pressEnter(event, moduleLogin.check)">
	<div class="overlay" style="display:none"></div>
	<div class="message-box" style="display:none">
		<div id="message"></div>
		<div class="message-button" style="display:none"></div>
	</div>
	<table class="login" cellspacing='0' cellpadding='3' border='0' width=100%>
		<tr>
			<td>Логин</td>
			<td>
				<input type="text" name="login" style="width: 130px">
			</td>
		</tr>
		<tr>
			<td>Пароль</td>
			<td>
				<input type="password" name="password" style="width: 130px">
			</td>
		</tr>
		<tr>
			<td colspan=2 align='center'>
				<input type="button" onclick="moduleLogin.check()" value="Вход">
			</td>
		</tr>
	</table>
</body>
</html>

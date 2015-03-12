<!DOCTYPE html>
<html>
<head>
<title>Домашняя бухгалтерия</title>
<meta charset="utf-8" />
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<link rel="stylesheet" type="text/css" media="all" href="css/style.css" />
<link rel="stylesheet" type="text/css" media="all" href="css/autocomplete.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="js/jquery-1.10.2.js"></script>
<!--script type="text/javascript" src="js/jquery-ui-1.10.4.custom.min.js"></script-->
<script type="text/javascript" src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src='js/jquery_plugins/jquery.autocomplete.min.js'></script>
<!-- <script type="text/javascript" src="js/action.js"></script> -->
<script type="text/javascript" src="js/keeper.js"></script>
<script type="text/javascript" src="js/widget/ajax.js"></script>
<script type="text/javascript" src="js/widget/dialog.js"></script>
<script type="text/javascript" src="js/modules/<?=Project::getJSModuleName()?>.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	const MIN_WIDTH_VIEW_PAGE = 1810;
	var module = '<?=Project::getJSModuleName()?>';
	if (module == 'view' && document.body.clientWidth < MIN_WIDTH_VIEW_PAGE) {
		$('#top_menu .right').css('margin-right', (MIN_WIDTH_VIEW_PAGE - document.body.clientWidth) + 'px');
		$('.subpanel select').css('margin-right', (MIN_WIDTH_VIEW_PAGE - document.body.clientWidth + 10) + 'px');
		document.body.style.width = MIN_WIDTH_VIEW_PAGE + 'px';
	}
	$('#datepicker').datepicker({dateFormat: 'yy-mm-dd'});
});
</script>
</head>
<body onload="keeper.activeMenu()">
	<div class="overlay" style="display:none"></div>
	<div class="message-box" style="display:none">
		<div id="message"></div>
		<div class="message-button" style="display:none"></div>
	</div>
	<div class="container" id="container">
		<div id="content">
			<div id="top_menu" class="clearfix">
				<ul class="sf-menu">
					<li id="add"><a href="/">Добавить</a></li>
					<li id="view"><a href="/view">Просмотр</a></li>
					<li id="stat"><a href="/stat">Статистика</a></li>
					<li id="option"><a href="/option">Опции</a></li>
					<li id="logout"><a href="#" onclick="keeper.logout()">Выйти</a></li>
				</ul>
				<span class="right">Логин: 
					<a id="usr" href="/usr">
						<?=$user->login?>
					</a>
						&nbsp;&nbsp;&nbsp;За месяц: 
						<b id="price-month-sum">
							<?=$this->project->getSummaMonth($user->id)?>
						</b>
					</span>
			</div>
			<div id="content_data">
				<?= !empty($content) ? $content : '' ?>
			</div>
		</div>
	</div>
	<div  id="footer" class="clearfix">
		<p class="left"></p>
		<p class="right">© 2014 pahagol</p>
	</div>
</body>
</html>

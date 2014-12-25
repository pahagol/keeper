<link href="public/css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/flexigrid.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".active_banner").live('click', function(e){
		e.preventDefault();
		var id = $(this).attr('rel');
		var _this = $(this);
		$.post('admin/ajax/banner_active', {id : id}, function(data){
			$(_this).html(data);
		});
	});
});	
</script>
<div id="content_main" class="clearfix">
    <div id="main_panel_container">
        <div class="dashboard">
			<a href="admin/banner/add">Добавить баннер</a><? if (!empty($js_grid)): ?>&nbsp;&nbsp;&nbsp;<a href="banner" target="_blank">Просмотр баннеров</a><? endif ?>
        <?php
			if (!empty($js_grid))
				echo $js_grid;
			else
                echo"<p>Нет данных</p>";
        ?>
        <table id="flex1" style="display:none"></table>
        </div>
    </div>
</div>
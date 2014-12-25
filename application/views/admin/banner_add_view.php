<div id="content_main" class="clearfix">
    <div id="main_panel_container">
        <div class="dashboard">
			<p>Добавление баннера</p>
			<?if(validation_errors()):?><div id="fail" class="info_div"><span class="ico_cancel"></span><?= validation_errors(); ?></div><?endif;?>
			<?if(!empty($upload_error)):?><div id="fail" class="info_div"><span class="ico_cancel"></span><?= $upload_error; ?></div><?endif;?>
			<?= form_open_multipart('admin/banner/add') ?>	
                <table class="table">
                    <tr><th>Имя: </th><td><?= form_input(array('name' => 'name', 'value' => $name))?></td></tr>
					<tr><th>Ширина*Высота: </th><td><?= form_dropdown("wh", $whs, $wh) ?></td></tr>
					<tr><th>Тип: </th><td><?= form_dropdown("type", $types, $type) ?></td></tr>
					<tr><th>Баннер: </th><td><?= form_upload("banner") ?></td></tr>
					<tr><th></th><td><?= form_submit(array('name' => 'submit', 'value' => 'Добавить'))?></td></tr>
                </table>
            <?= form_close() ?>	
		</div>	
	</div>
</div>	
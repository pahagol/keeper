<link href="public/css/flexigrid.css" rel="stylesheet" type="text/css" />
<script src="js/flexigrid.js"></script>
<?=$js_grid?>
<div id="content_main" class="clearfix">
    <div id="main_panel_container">
        <div class="dashboard">
            <h2 class="ico_mug">Лояльность</h2>
            <table>
                <tr><td style="padding-right: 20px;">Ваша ссылка по программе лояльности</td><td style="font-weight: bold;"><a href="<?= $link ?>"><?= $link ?></a></td></tr>
                <tr><td style="padding-right: 20px;">Ваш процент от заработка партнера</td><td style="font-weight: bold;"><?= $percent ?></td></tr>
            </table>

            <table id="flex1" style="display:none"></table>

        </div>
    </div>
</div>
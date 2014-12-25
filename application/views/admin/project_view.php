<link href="public/css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/flexigrid.js"></script>
<div id="content_main" class="clearfix">
    <div id="main_panel_container">
        <div class="dashboard">
            <form name="admin_projectc" class="editable_form">
                <input type="hidden" name="post_path" value="admin/projectc/ajax_save"/>
                <input type="hidden" name="id" value="<?= $id ?>"/>
                <table class="table">
                    <tr><th>Проект </th><td><?= $name ?></td></tr>
                    <tr><th>Процент партнерской программы в проекте</th><td>60%</td></tr>
                    <tr><th>Реф Код</th><td><span class='editable' name="ref_code"><?= $ref_code ?></span></td></tr>
                    <tr><th>% Вознаграждения (по-умолчанию)</th><td><span class='editable' name="percent"><?= $percent ?></span></td></tr>
                    <tr><th>% Диапазон вознаграждения</th><td><span class='editable' name="percent_range"><?= $percent_range ?></span></td></tr>                
                    <tr><th>Задержка выплат</th><td><span class='editable' name="payout_delay"><?= $payout_delay ?></span></td></tr>
                    <tr><th>Прямая ссылка</th><td><a href="<?= $url ?>" target="_blank"><?= $url ?></a></td></tr>
                    <tr><th>Описание</th><td><?= $desc ?></td></tr>
                    <tr><th>Всего вознаграждение по данным партнерки</th><td><?= $all_sum ?></td></tr>
                    <!--tr><th>Всего вознаграждение по данным Insollo</th><td><?= $all_sum_mlgame ?></td></tr-->
                    <tr><th>Логотип</th><td><img src="<?= $logo ?>" alt="<?= $name ?>"/></td></tr>
                    <tr><th>Всего партнеров:</th><td><?= $partners ?></td></tr>
                    <tr><th>Всего площадок:</th><td><a href="admin/sites"><?= $sites ?></a></td></tr>
                    <tr><th>Всего кликов:</th><td><a href="admin/stats"><?= $clicks ?></a></td></tr>
                    <tr><th>Всего рефералов:</th><td><a href="admin/stats/by_referal"><?= $referals ?></a></td></tr>
                    <tr><th>Всего активных:</th><td><a href="admin/stats/by_referal/show_active/1"><?= $active ?></a></td></tr>
                    <tr><th>Всего активных на день регистрации:</th><td><a href="admin/stats/by_level_referal"><?= $active_on_reg_day ?></a></td></tr>
                    <tr><th>Средний процент:</th><td><?= $percent ?></td></tr>
                    <tr><th>Всего выплачено:</th><td><?= $payed ?></td></tr>
                    <tr><th>Всего к выплате:</th><td><?= $to_pay ?></td></tr>
                    <!--<tr><th></th><td><input type="button" disabled="disabled" value="Удалить проект"/></td>
                    <tr><th></th><td><input type="button" disabled="disabled" value="Деактивировать кампанию"/></td>
                    <tr><th></th><td><input type="button" disabled="disabled" value="Спрятать кампанию"/></td> -->
                </table>
            </form>
            
            <h3 style="margin-top:10px;"><a name="partners" style='text-decoration:none;color:black;'>Описание статусов</a><h3>
                    <ul style="font-weight:normal;">
                        <li><strong>Не подтвержден</strong> - Партнер только зарегистрировался, но еще не подтвердил свой имейл</li>
                        <li><strong>Новый</strong> - Партнер подтвердил имейл, но еще не отправил форму с номером кошелька (и сайтом)</li>
                        <li><strong>На модерации</strong> - Партнер отправил форму (см. выше), но администратор еще не завершил модерацию</li>
                        <li><strong>Активен</strong> - Партнер активен, обычный статус нормального партнера</li>
                        <li><strong>Заблокирован</strong> - Партнер заблокирован или забанен, не имеет доступа к статистике</li>
                    </ul>

            <h3 style="margin-top:10px;"><a name="partners" style='text-decoration:none;color:black;'>Партнеры добавившие компанию</a><h3>
                    <form action="" class="filter_form">
                        <input type="hidden" value="<?= $id ?>" name="id"/>
                        Статус: <?= form_dropdown("state", $user_states, $state) ?>   
                        <input type="submit" value="Показать"/>
                    </form>
                    <?php
                    if (!empty($js_grid))
                        echo $js_grid;else
                        echo"<p>Нет данных</p>";
                    ?>
                    <table id="flex1" style="display:none"></table>
                    </div>
                    </div>
                    </div>
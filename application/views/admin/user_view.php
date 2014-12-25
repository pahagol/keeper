<div id="content_main" class="clearfix">
    <div id="main_panel_container">
        <div class="dashboard">
            <h2 class="ico_user">Обзор партнера <span style="color: green;"><?= $name ?></span></h2>
            <form name="admin_userc" class="editable_form">
                <input type="hidden" name="post_path" value="admin/userc/ajax_save"/>
                <input type="hidden" name="id" value="<?= $id ?>"/>
                <table class="table">
                    <tr><td style="width: 50%;">ID</td><td><?= $id ?></td></tr>
                    <tr><td style="width:50%;">Партнер</td><td><?= $name ?></td></tr>
                    <tr><td style="width:50%;">Полное имя</td><td><?= $name ?></td></tr>
                    <?$state_color=($state=="blocked")?"red":"black";?>
                    <tr><td style="width:50%;">Статус</td><td><span style='color:<?=$state_color?>;font-weight:bold;'><?=  get_user_status_transalate($state) ?></span></td></tr>
                    <tr><td style="width:50%;">Дата регистрации</td><td><?= $reg_date ?></td></tr>
                    <tr><td style="width:50%;">E-Mail</td><td><?= $email ?></td></tr>

                    <tr><td style="width: 50%;">Реф. Код</td><td><span class="editable" name="ref_code"><?= $ref_code ?></span></td></tr>
                    <tr><td style="width: 50%;">Процент</td><td><span class="editable" name="percent"><?= $percent ?></span></td></tr>
                    <tr><td style="width: 50%;">Процент Системы</td><td><span class="editable" name="system_percent"><?= $system_percent ?></span></td></tr>
                    <tr><td style="width: 50%;">Процент лояльности</td><td><span class="editable" name="loyalty_percent"><?= $loyalty_percent ?></span></td></tr>
                    <tr><td style="width: 50%;">Приведен партнером</td><td><? if ($by_partner): ?><a href="admin/userc/view/<?= $by_partner['id'] ?>"><?= $by_partner['name'] ?></a><? endif; ?></td></tr>
                    <tr><td style="width:50%;">Всего привел</td><td><?= $count_children ?></td></tr>
                    <tr><td style="width:50%;">WMZ-кошелек</td><td><?= $wmz_num ?></td></tr>
                    <tr>
                        <td>
                            <? if ($state == "moderate"): ?><p><a href="admin/userc/moderate/<?= $id ?>" style="color:green;">Завершить модерацию</a></p><? endif ?>
                            <? if ($state == "not_confirmed"): ?><p><a href="admin/userc/confirm/<?= $id ?>">Активировать Партнера</a></p><? endif ?>
                            <? if (!($state == "blocked")): ?><p><a href="admin/userc/ban/<?= $id ?>">Бан Партнера</a><? else: ?><a href="admin/userc/unban/<?= $id ?>">Разбанить Партнера</a></p><? endif ?></td>
                        </td></tr>
                    <tr><td><h3><a href="admin/userc/simulate/<?= $id ?>" style="font-weight:bold;">Симуляция Партнера</a></h4></td></tr>

                </table>
            </form>
        </div>


        <div class="dashboard" style="margin-top: 20px;">
            <h3 class="ico_user">Общие данные по Insollo</h2>
                <table class="table">
                    <tr>
                        <th>Регистраций</th>
                        <th>Активных</th>
                        <th>Всего заработано</th>
                        <th>Всего заработано(по Insollo)</th>
                        <th>Заработано по Лояльности</th>
                        <th>Всего выплачено</th>
                        <th>Всего выплачено по лояльности</th>
                    </tr>
                    <tr>
						<td><?= $regs ?></td>
						<td><?= $active ?></td>
						<td><?= $earned ?></td>
						<td><?= $earned_mlgame ?></td>
						<td><?= $earned_loyalty ?></td>
						<td><?= $payouts_sum ?></td>
						<td><?= $payouts_loyalty_sum ?></td>
					</tr>
                </table>
        </div>

        <? if ($count_children > 0): ?>
            <div class="dashboard" style="margin-top: 20px;">
                <h3 class="ico_user">Список приведенных партнеров</h2>
                        <? foreach ($child_users as $u): ?>
                        <span><a href="admin/userc/view/<?= $u->id ?>"><?= $u->name ?></a></span>  
                        <? endforeach; ?>
            </div>
        <? endif; ?>

        <div class="dashboard" style="margin-top: 20px;">
            <h3 class="ico_user">К выплате</h2>
                <table class="table">
                    <tr><th>По партнерке</th><th>По лояльности</th></tr>
                    <tr><td><?= $sum_to_pay ?></td><td><?= $loyalty_sum_to_pay ?></td></tr>
                </table>
        </div>

        <div class="dashboard" style="margin-top: 20px;width:47%;float:left;">
            <h3 class="ico_user">Выплаты по Партнерке</h3>
            <table class="table">
                <tr><th>Сумма</th><th>Дата</th></tr>
                <?
                if ($payouts):
                    foreach ($payouts as $p):
                        ?>
                        <tr><td><?= $p->sum ?></td><td><?= $p->date ?></td></tr>
                        <?
                    endforeach;
                endif;
                ?>
            </table>
            <?if($state=="blocked")$disabled="disabled='disabled'";else $disabled="";?>
            <form action="/admin/userc/add_payout" method="post">
                <label for="input_sum">
                    Создать выплату
                </label>
                <input type="hidden" name="type" value="default"/>
                <input type="text" name="input_sum" <?=$disabled?>/>
                <input type="hidden" name="user_id" value="<?= $id ?>"/> 
                <input type="submit" value="Добавить" <?=$disabled?>/>
            </form>
        </div>

        <div class="dashboard" style="margin-top: 20px;width:47%;float:right;">
            <h3 class="ico_user">Выплаты по Лояльности</h3>
            <table class="table">
                <tr><th>Сумма</th><th>Дата</th></tr>
                <?
                if ($payouts_loyalty):
                    foreach ($payouts_loyalty as $p):
                        ?>
                        <tr><td><?= $p->sum ?></td><td><?= $p->date ?></td></tr>
                        <?
                    endforeach;
                endif;
                ?>
            </table>
            <form action="/admin/userc/add_payout" method="post">
                <label for="input_sum">
                    Создать выплату по Лояльности
                </label>
                <input type="hidden" name="type" value="loyalty"/>
                <input type="text" name="input_sum" <?=$disabled?>/>
                <input type="hidden" name="user_id" value="<?= $id ?>"/> 
                <input type="submit" value="Добавить" <?=$disabled?>/>
            </form>
        </div>


        <div class="clear"></div>

        <div class="dashboard" style="margin-top:20px;">
            <h3 class="ico_user">Лог действий</h3>
            <table class="table">
                <tr><th>Действие</th><th>Старое значение</th><th>Новое значение</th><th>Дата</th></tr>
                <? if (!empty($global_log)): ?>
                    <? foreach ($global_log as $r): ?>
                        <?
                        switch ($r->action) {
                            case "change_user_loyalty_percent":
                                $action = "Изменение процента лояльности";
                                break;
                            case "change_user_wmz_num":
                                $action = "Изменение номера WMZ-кошелька";
                                break;
                            case "change_user_percent":
                                $action = "Изменение процента партнера";
                                break;
                            case "change_user_system_percent":
                                $action = "Изменение системного процента для партнера";
                                break;
                            case "change_user_ref_code":
                                $action = "Изменение реферального кода партнера";
                                break;
                        }
                        ?>        
                        <tr><td><?= $action ?></td><td><?= $r->old_value ?></td><td><?= $r->new_value ?></td><td><?= $r->date ?></td></tr>
                    <? endforeach; ?>
                <? endif; ?>
            </table>
        </div>

    </div>
</div>
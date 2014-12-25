<div id="content_main" class="clearfix">
    <div id="main_panel_container">
        <? if ($user_projects): ?>
            <div class="dashboard">
                <h2 class="ico_mug">За вчера</h2>

                <table class="table">
                    <tr>
                        <th>Проект</th>
                        <th>Кол-во регистраций</th>
                        <th>Кол-во активных</th>
                        <th>Вознаграждение</th>
                        <th>Процент</th>
                    </tr>
                    <? foreach ($user_projects as $p): ?>
                        <tr>
                            <td><a href='projectc/view/<?= $p->id ?>'><?= $p->name ?></a></td>

                            <td>
								<a href='refs/all/proj/<?=$p->id?>/reg_date/<?=$last_project_stats[$p->id]->date?>'>
									<? if (!empty($last_project_stats[$p->id])) echo $last_project_stats[$p->id]->registers;else echo "0"; ?>
								</a>
							</td>
                            <td>
								<a href='refs/all/proj/<?=$p->id?>/reg_date/<?=$last_project_stats[$p->id]->date?>'>
									<? if (!empty($last_project_stats[$p->id])) echo $last_project_stats[$p->id]->active_regs;else echo "0"; ?>
								</a>
							</td>
                            <td>
								<a href='refs/by_date/proj/<?=$p->id?>/date/<?=$last_project_stats[$p->id]->date?>'>
									<? if (!empty($last_project_stats[$p->id])) echo $last_project_stats[$p->id]->earnings;else echo "0"; ?>
								</a>
							</td>
                            <td><?= $percent[$p->id] ?></td>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
            <div class="dashboard" style="margin-top: 10px;">
                <h2 class="ico_mug">За всё время</h2>
                <? if ($project_stats): ?>
                    <table class="table">
                        <tr>
							<th>Проект</th>
							<th>Кол-во регистраций</th>
							<th>Кол-во активных<br/>на сегодняшний день</th>
							<th>Кол-во активных<br/>на день регистрации</th>
							<th>Вознаграждение всего</th>
							<th>К выплате</th>
							<th>Текущая сумма к выплате</th>
						</tr>
                        <? foreach ($user_projects as $p):
                            ?>
                            <tr>
								<td><a href='projectc/view/<?= $p->id ?>'><?= $p->name ?></a></td>
								<td><a href='refs/all/proj/<?=$p->id?>'><?= $project_stats[$p->id]->registers ?></a></td>
								<td><a href='refs/all/proj/<?=$p->id?>'><?= $project_stats[$p->id]->active_regs ?></a></td>
								<td><a href='refs/all/proj/<?=$p->id?>'><?= $project_stats[$p->id]->active_regs_reg_day ?></a></td>
								<td><a href='refs/all/proj/<?=$p->id?>'><?= $project_stats[$p->id]->earnings ?></a></td>
								<td><span style="font-weight:bold;color:red;"><?= $project_stats[$p->id]->sum_to_pay ?></span></td>
								<td><span style="font-weight:bold;color:red;"><?= $project_stats[$p->id]->current_sum_to_pay ?></span></td>
							</tr>
                    <? endforeach; ?>
                    </table>
            <? endif; ?>
            </div>
        <? else: ?>
            <p>Вы еще не подключились ни к одному из проектов</p>
<? endif; ?>

        <!--
        <div id="shortcuts" class="clearfix">
              <h2 class="ico_mug">Panel shortcuts</h2>
              <ul>
            <li class="first_li"><a href=""><img src="img/theme.jpg" alt="themes" /><span>Themes</span></a></li>
            <li><a href=""><img src="img/statistic.jpg" alt="statistics" /><span>Statistics</span></a></li>
            <li><a href=""><img src="img/ftp.jpg" alt="FTP" /><span>FTP</span></a></li>
            <li><a href=""><img src="img/users.jpg" alt="Users" /><span>Users</span></a></li>
            <li><a href=""><img src="img/comments.jpg" alt="Comments" /><span>Comments</span></a></li>
            <li><a href=""><img src="img/gallery.jpg" alt="Gallery" /><span>Gallery</span></a></li>
            <li><a href=""><img src="img/security.jpg" alt="Security" /><span>Security</span></a></li>
          </ul>
            </div>
        -->
        <!-- end #shortcuts --> 
    </div>
</div>

<div class="section">
    <h2 class="ico_mug">Лояльность</h2>
    <table class="table" style="width:auto;">
        <tr><th>Приведено партнеров</th><td><a href="loyalty"><?=$count_invited?></a></td></tr>
        <tr><th>Заработано по программе лояльности всего</th><td><a href="loyalty"><?=$loyalty_earned?></td></a></tr>
        <tr><th>Сумма к выплате</th><td><span style="font-weight:bold;color:red;"><?=$loyalty_to_pay?></span></td></tr>
    </table>
</div>

<div class="section">
    <h2 class="ico_mug">Забор статистики</h2>
    <form name="user_stats" class="editable_form">
        <input type="hidden" name="post_path" value="main/ajax_save"/>
    <table class="table">
        <tr><th>Пароль на автоматический забор статистики</th><td><span class="editable" name="skey"><?=($user->skey)?$user->skey:""?></span></td></tr>
        <tr><th>Ссылка на автоматический забор статистики</th><td><a href="<?=$xml_link?>"><?=$xml_link?></a></td></tr>
    </table>
    </form>
</div>

<div class="section">
    <h2 class="ico_mug">Настройки</h2>
    <form name="user_wmz_num" class="editable_form">
        <input type="hidden" name="post_path" value="main/ajax_save"/>
    <table class="table">
        <tr><th>Пароль</th><td><a href="settings/change_pass">Изменить</a></td></tr>
        <tr><th>E-Mail</th><td><?=$user->email?>   &nbsp&nbsp;<a href="settings/change_email">Изменить</a></td></tr>
        <tr><th>WMZ-кошелек</th><td><span class="editable" name="wmz_num"><?=$user->wmz_num?></span></td></tr>
    </table>
    </form>
</div>
<!--
<div class="section">
    <h2 class="ico_mug">Проекты</h2>
<? if ($all_projects): ?>
        <table class="table">
            <tr><th>Проект</th><th>Процент</th><th>Статус</th></tr>
            <? foreach ($all_projects as $p): ?>
                <tr><td><?= $p->name ?></td><td><?= $p->percent ?></td><td><? if (isset($user_projects[$p->id])): ?>Подключен<? else: ?><a href='main/connect/<?= $p->id ?>'>Подключиться</a><? endif; ?></td></tr>
        <? endforeach; ?>
        </table>
    <? else: ?>
        <p>Проекты не найдены</p>
<? endif; ?>
</div>
-->

          <div id="content_main" class="clearfix">
          <div id="main_panel_container">
        <div class="dashboard">
        <h2 class="ico_mug">За всё время</h2>
              <table class="table">
                <tr>
                  <th>Всего выплачено партнерам</th>
                  <th>Всего выплачено по программе лояльности</th>
                  <th>Всего заработано программой</th>
                  <th>Всего партнеров</th>
                  <th>Всего площадок</th>
                </tr>
                <tr>
                    <td><?=$all_payed?></td>
                    <td><?=$all_loyalty_payed?></td>
                    <td><?=$all_system_earned?></td>
                    <td><?=$all_partners?></td>
                    <td><?=$all_sites?></td>
                </tr>
             </table>
             </div>
                <div class="dashboard" style="margin-top: 10px;">
                    <p style="font-weight:bold;">Партнеров На модерации: <a href="admin/projectc/view/id/1/state/moderate"><?=$count_moderate?></a></p>
                    <p>Партнеров Не подтвердивших имейл: <a href="admin/projectc/view/id/1/state/not_confirmed"><?=$count_not_confirmed?></a></a>
                    <p>Партнеров Заблокированных: <a href="admin/projectc/view/id/1/state/blocked"><?=$count_blocked?></a>
                    </p>
                    <hr/>
             <p><a href="<?=site_url("admin/stats")?>">Подробно по дням</a></p>
             <p><a href="<?=site_url("admin/projectc/view/id/1")?>">Проект 2056</a></p>
             <p><a href="<?=site_url("admin/stats/by_partner")?>">Партнеры</a></p>
             <p><a href="<?=site_url("admin/sites/view/state/banned")?>" style='color:red;'>Забаненные площадки</a></p>
             <p><a href="<?=site_url("admin/frauds")?>" style='color:red;'>Отрицательные выплаты (фроды)</a></p>
                </div>
             <div class="dashboard" style="margin-top: 10px;">
             <h2 class="ico_mug">За вчера</h2>
             <table class="table">
                <tr>
                    <th>Кликов</th>
                    <th>Регистраций</th>
                    <th>Активных игроков</th>
                    <th>Вознаграждение партнеров</th>
                </tr>
                <tr>
                    <td><?=$yest_clicks?></td>
                    <td><?=$yest_regs?></td>
                    <td><?=$yest_active?></td>
                    <td><?=$yest_earnings?></td>
                </tr>
             </table>
          </div>

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

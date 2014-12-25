          <div id="content_main" class="clearfix">
          <div id="main_panel_container">
        <div class="dashboard">
        <h2 class="ico_mug">Ваши площадки</h2>
        <?if($sites):?>
        <table class="table">
        <tr><th>Площадка</th><th>Посещаемость</th><th>Кликов</th><th>Регистраций</th><th>Активных</th><th>Вознаграждение</th></tr>
        <?foreach($sites as $s):?>
        <tr><td><a href='http://<?=$s->url?>' target='_blank'><?=$s->url?></a></td><td><?=$s->attendance?></td><td><?=$s->clicks?></td><td><?=$s->registers?></td><td><?=$s->earnings?></td></tr>
        <?endforeach;?>
        </table>
        <?else:?>
        <p>У вас пока нет площадок</p>
        <?endif;?>
        <p style="margin-top: 20px"><a href="sites/add/1">Добавить площадку</a><p>
        </div>
        </div>
        </div>
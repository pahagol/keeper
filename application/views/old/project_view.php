<div id="content_main" class="clearfix">
    <div id="main_panel_container">
        <div class="dashboard">
            <h2 class="ico_mug">Проект</h2>
            <!--
            <form action="" method="post">
                <label>Выберите проект: <select name="project"><option></option>
            <? foreach ($projects as $p): ?>
                                    <option <?
            if (isset($project)) {
                if ($p->id == $project->id)
                    echo"selected='selected'";
            }
                ?> value="<?= $p->id ?>"><?= $p->name ?></option>       
            <? endforeach; ?>
                    </select>
                </label>
                <input type="submit" value="Выбор"/>
            </form>
            -->
			
			<? if (isset($project)): ?>
                <table style="margin-top:10px;">
                    <tr><td style="padding-right:20px;">Проект</td><td style="font-weight:bold;"><?= $project->name ?></td></tr>
                    <tr><td style="padding-right:20px;">Описание</td><td style="font-weight:bold;"><?= $project->description ?></td></tr>
                    <tr><td style="padding-right:20px;">Прямая ссылка на проект</td><td style="font-weight:bold;"><?= $project->url ?></td></tr>
                    <tr><td style="padding-right:20px;">Логотип</td><td style="font-weight:bold;"><img src="<?= config_item("project_logo_dir") . DIRECTORY_SEPARATOR . $project->logo ?>"/></td></tr>
                    <tr><td style="padding-right:20px;">Ваш Процент</td><td style="font-weight:bold;"><?= $p_user->percent ?></td></tr>
                    <tr><td style="padding-right:20px;">Ваш статус</td><td style="font-weight:bold;">
                            <?=$status?>
                        </td></tr>
                    <tr><td style="padding-right: 20px">Ваша рефссылка:</td>

                        <td style="font-weight:bold;"><? if (!empty($reflink)): ?><a href='<?= $reflink ?>' target="_blank"><?= $reflink ?></a><? endif; ?></td></tr>
                    <tr><td style="padding-right: 20px">Банеры с вашей ссылкой: </td>
                        <td style="font-weight:bold;"><? if (!empty($bannerslink)): ?><a href='<?= $bannerslink ?>' target='_blank'><?= $bannerslink ?></a><? endif; ?></td></tr>
                </table>

            <? endif; ?>
            <p><a href="sites/add/<?= $p_id ?>">Добавить площадку в проект</a></p>
            <h3 style="margin-top:10px;">Ваши добавленные площадки</h3>
            <? if (!empty($sites)): ?>
                <table style="margin-top:5px;">
                    <? foreach ($sites as $site): ?>
                        <?
                        if ($site->url == "null")
                            $link = empty($reflink) ? "" : $reflink;
                        else
                            $link = site_url("forward/?proj={$project->id}&site={$site->id}");
                        ?>
                        <tr><td style="padding-right:20px;"><?= $site->url ?></td><td><? if ($p_user->status == "active"): ?><a href="<?= $link ?>"><?= $link ?></a><? endif; ?></td></tr>
                <? endforeach ?>
                </table>
            <? else: ?>
                <p>У вас нет площадок</p>
<? endif; ?>

        </div>
    </div>
</div>

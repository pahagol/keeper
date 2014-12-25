<h3>Бан Площадки</h3>
<p><?=validation_errors()?></p>
<p>URL Площадки: <?= $site_url ?></p>
<p>Площадка принадлежит: <a href="admin/userc/view/<?=$user_id?>"><?=$user_name?></a></p>
<form method="post">
    <label>Причина бана: <textarea name="ban_reason"></textarea></label>
    <input type="submit" value="Забанить"/>
</form>
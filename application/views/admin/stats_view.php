<script>
    jQuery(function($) {
        var dates = $( "#date_from, #date_to, #reg_date" ).datepicker({
            dateFormat: 'yy-mm-dd',
            defaultDate: "<?= empty($date) ? "" : $date ?>"
        });
    });
</script>
<div style="background: white;">
    <p><a href='admin/stats'>Всего По Датам</a> | 
        <a href='admin/stats/by_partner'>По Партнерам за всё время</a> | 
        <a href='admin/stats/by_date_partner'>Партнеры по Датам</a> | 
        <a href='admin/stats/by_referal'>По рефералам за всё время</a> |
        <a href="admin/stats/by_date_referal">Рефералы по Датам</a> | 
        <a href="admin/stats/by_level_referal">Активность рефералов</a>
    </p>
    <? if (in_array($this->uri->segment(3), array("by_date_partner", false, "by_date", "by_date_referal"))): ?>
        <form action="" class="filter_form">
            <p>С <input type="text" id="date_from" name="date" value="<?= $date ?>"/>&nbsp;&nbsp;&nbsp;
                По <input type="text" id="date_to" name="date_to" value="<?= $date_to ? $date_to : $date ?>"/>

                <? if (in_array($this->uri->segment(3), array("by_date_referal", "by_date", false))): ?>Выбранный партнер: <?= form_dropdown('user_id', $users_drop, $user_id, "class='combobox'"); ?><!--<input type='text' value='<?= empty($user_id) ? "" : $user_id ?>' name='user_id' style='width:50px;'/>--><? endif; ?>
                <? if (in_array($this->uri->segment(3), array("by_date_partner"))): ?>Заработок не нулевой <input type="checkbox" value="1" <?= empty($earnings_not_null) ? "" : "checked='checked'" ?> name="earnings_not_null"><? endif ?>
                <input type="submit" value="Показать"/>
            </p>
        </form>
    <? endif; ?>
    <? if (in_array($this->uri->segment(3), array("by_referal"))): ?>
        <form action="" class="filter_form">
            <p>Выбранный партнер: <?= form_dropdown('user_id', $users_drop, $user_id, "class='combobox'"); ?>
                        Показывать активных:
        <?=form_checkbox('show_active', '1', $show_active)?>
                <input type="submit" value="Показать"/>
            </p>
        </form>
    <? endif; ?>
    <? if (in_array($this->uri->segment(3), array("by_level_referal"))): ?>
        <form action="" class="filter_form">
            <p>Дата регистрации: <input type="text" id="reg_date" name="reg_date" value="<?= $reg_date ?>"/>  Активность на <input type="text" id="date_from" name="date" value="<?= $date ?>"/>
                Выбранный партнер: <?= form_dropdown('user_id', $users_drop, $user_id, "class='combobox'"); ?>
                <input type="submit" value="Показать"/>
            </p>
        </form>
    <? endif; ?>




    <div class="clear"></div>
    <h3 class="ico_mug">
        <?
        if ($this->uri->segment(3) == "by_date_partner")
            echo "Статистика партнеров за " . $date . " - " . $date_to;
        elseif ($this->uri->segment(3) == "by_partner")
            echo 'Статистика партнеров за всё время';
        else
            echo 'Статистика по датам';
        ?>
        </h2>
<?
echo $cont;
?>
</div>
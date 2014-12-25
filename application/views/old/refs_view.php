<script>
    jQuery(function($) {
        var dates = $( "#reg_date,#date" ).datepicker({
            dateFormat: 'yy-mm-dd',
            defaultDate: "<?= empty($reg_date) ? "" : $reg_date ?>"
        });
    });
</script>
<div style="background: white;">
<p><a href="refs/all">Рефералы за всё время</a> | <a href="refs/by_date">Рефералы по датам</a></p>
<div class="filter_box">
    <?= form_open("", array("class" => "filter_form")) ?>
    <?//= form_dropdown("proj", $projects, $p_id) ?> 
    <? if (in_array($this->uri->segment(2), array(false, "all"))): ?>
        Дата регистрации: 
        <?= form_input(array("name" => "reg_date", "value" => $reg_date, "id" => "reg_date")) ?>
    <? elseif ($this->uri->segment(2) == "by_date"): ?>
        Дата: <?= form_input(array("name" => "date", "value" => $date, "id" => "date")) ?>
    <? endif; ?>
        Показывать активных:
        <?=form_checkbox('show_active', '1', $show_active)?>
    <?= form_submit('submit', 'Выбрать') ?>
    <?= form_close() ?>
</div>
<?= $cont; ?>
</div>
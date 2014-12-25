<script>
    jQuery(function($) {
        var dates = $( "#date_to,#date" ).datepicker({
            dateFormat: 'yy-mm-dd',
            defaultDate: "<?= empty($reg_date) ? "" : $reg_date ?>"
        });
    });
</script>
<div style="background: white;">
<!--<p><a href="refs/all">Рефералы за всё время</a> | <a href="refs/by_date">Рефералы по датам</a></p> -->
<div class="filter_box">
    <?= form_open("", array("class" => "filter_form")) ?>
    <?//= form_dropdown("proj", $projects, $proj) ?> 
        Дата с: 
        <?= form_input(array("name" => "date", "value" => $date, "id" => "date")) ?>
        Дата по:
        <?= form_input(array("name" => "date_to", "value" => $date_to, "id" => "date_to")) ?>
    <?= form_submit('submit', 'Выбрать') ?>
    <?= form_close() ?>
</div>
<?= $cont; ?>
</div>
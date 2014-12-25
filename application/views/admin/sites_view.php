<div class="filter_box">
    <?= form_open("admin/sites/view", array("class" => "filter_form")) ?>
    <?//= form_dropdown("proj", $projects, $p_id) ?>
    Партнер: 
    <?= form_dropdown('user_id', $users_drop, $user_id,"class='combobox'"); ?>
    Статус: <?= form_dropdown("state", $states, $state) ?>  
    
    <?= form_submit('submit', 'Выбрать') ?>
    <?= form_close() ?>
</div>
<?= $cont; ?>


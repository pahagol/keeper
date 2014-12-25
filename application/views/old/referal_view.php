<link href="public/css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/flexigrid.js"></script>
<div style="background: white;margin:0  auto;">
    <table style="margin:0 auto;width:400px;">
        <tr><td>Игрок</td><td><?= $referal->name ?></td></tr>
        <tr><td>Дата регистрации</td><td><?= $referal->reg_date ?></td></tr>
        <tr><td>Всего потрачено</td><td><?= $referal->spent ?></td></tr>
        <!--tr><td>Всего заработано ЧЖ</td><td><?= $referal->earned ?></td></tr>
        <tr><td>Кредит</td><td><?= $referal->credit ?></td></tr-->
        <tr><td>Всего введено</td><td><?= $referal->inputted ?></td></tr>
        <tr><td>Вознаграждение партнера</td><td><?= $referal->profit ?></td></tr>
        <tr><td>Возврат денег (фрод)</td><td></td></tr>
        <tr><td>Активность на данный момент</td><td><?= $referal->level ?></td></tr>
    </table>
</div>
    <?php
    if (!empty($js_grid))
        echo $js_grid;
    else
        echo"<p>Нет данных</p>";
    ?>
    <table id="flex1" style="display:none"></table>



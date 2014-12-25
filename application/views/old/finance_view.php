<div style="background: white;">
<table class="table">
    <tr><td>Вознаграждение за всё время</td><td><?=$earns?></td></tr>
    <tr><td>Выплачено за всё время</td><td><?=$sum_paid?></td></tr>
    <tr><th>Вознаграждение к выплате</th><td style="color:red;font-weight:bold;"><?=$sum_to_pay?></td></tr>        
</table>
    <h3 class="ico_mug" style="margin-top:20px;">История выплат</h3>
    <p>*При выплате партнеру с этих сумм удерживается комиссия системы WebMoney 0,8%</p>
<?= $cont; ?>
</div>

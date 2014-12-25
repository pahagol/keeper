<div style="background: white;">
    <? if ($this->user->state == "new"): ?>
        <?php echo validation_errors(); ?>
        <p>Вам пока что не назначен реферальный код</p>
        <p>Чтобы получить персональный реферальный код вам нужно заполнить форму ниже:</p>
        <form action="" method="post">
            <table>
                <!--<tr><td>Ваша площадка:</td><td><input type="text" name="site" value="<?= set_value("username"); ?>"/></td></tr>-->
                <!--<tr><td>Посещаемость вашей площадки:</td><td><input type="text" name="attendance" value="<?= set_value("password"); ?>"/> в сутки</td></tr>-->
                <tr><td>Номер вашего кошелька  WMZ для выплат:</td><td><input type="text" name="wallet" value="<?= set_value("wallet"); ?>"/></td></tr>
                <tr><td><input type="submit" value="Запросить Реферальный код" style="font-weight:bold;color:red;"/></td></tr>
            </table>
        </form>
    <? elseif($this->user->state=="moderate"): ?>
<p>Ваша заявка будет рассмотрена модератором</p>
<p>Дождитесь завершения модерации</p>
    <? endif; ?>
</div>
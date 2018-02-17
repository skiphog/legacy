<?php
/**
 * @var \Swing\System\Controller $this
 * @var \Swing\Models\RowUser[]  $meets
 * @var \Swing\Models\RowUser[] city_meets
 */

$sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility, u.real_status, u.visibility, u.hot_time, u.regdate, 
  u.vip_time, u.now_status, u.hot_text, u.vipsmile,u.admin, u.moderator, u.city, u.login, u.fname, u.gender, u.about, 
  ut.last_view
  from users u 
    join users_timestamps ut on ut.id = u.id 
  where u.hot_time> NOW() and u.status = 1
order by u.hot_time desc limit 50';

$sth = $this->dbh->query($sql);

$city_meets = $meets = [];
/** @var \Swing\Models\RowUser $row */
while ($row = $sth->fetchObject(\Swing\Models\RowUser::class)) {
    if (strtoupper($row->city) === strtoupper($this->myrow->city)) {
        $city_meets[] = $row;
        continue;
    }
    $meets[] = $row;
}
?>

    <table border="0" width="100%">

        <tr>
            <td height="1" bgcolor="#336699"></td>
        </tr>
        <tr>
            <td align="left">
                <b><a href="findlist">Поиск анкет</a> / Горячие знакомства</b>
            </td>
        </tr>
        <?php if (!empty($_SESSION['hot'])) : ?>
            <tr>
                <td align="left" style="color: #fff;font-size: 20px;background-color: green">
                    Ваше объявление добавлено
                </td>
            </tr>
            <?php unset($_SESSION['hot']) ?>
        <?php endif; ?>
        <tr>
            <td height="1" bgcolor="#336699"></td>
        </tr>


        <tr>
            <td align="center">
                <table border="0" width="100%">

                    <?php if (!empty($city_meets)) : ?>
                        <tr>
                            <td colspan="6" align="center" valign="center" style="background-color: #0085e6; font-weight: bold;color: #fff;font-size: 16px;">
                                Горячие объявления из вашего города
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" align="center" valign="center">
                                <?php foreach ($city_meets as $user) : ?>
                                    <?php anketa_usr_row($this->myrow, $user) ?>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php if (!empty($meets)) : ?>
                        <tr>
                            <td colspan="6" align="center" valign="center" style="background-color: #de8b5f; font-weight: bold;color: #fff">
                                Горячие знакомства
                            </td>
                        </tr>

                        <tr>
                            <td colspan="6" align="center" valign="center">
                                <?php foreach ($meets as $user) : ?>
                                    <?php anketa_usr_row($this->myrow, $user) ?>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
        <tr>
            <td height="1" bgcolor="#336699"></td>
        </tr>
    </table>

<?php if ($this->myrow->rate > 100) : ?>
    <div>
        <h1>Подать заявку</h1>
        У вас <b><?php echo $this->myrow->rate; ?></b> баллов. Хотите подать объявление?
        <br>
        Стоимость заявки <b>100</b> баллов на 7 дней
        <br>
        <span class="red" style="font-size: 16px">Реклама услуг и коммерческих вечеринок запрещена</span>
        <form method="post" action="/hotmeet">
            <br>
            Добавить объявление:
            <br>
            <textarea style="width: 100%; height: 100px;" id="text" name="message"></textarea>
            <br>
            <button class="btn btn-primary">Отправить</button>
        </form>
    </div>
<?php endif; ?>
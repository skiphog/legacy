<?php
/**
 * @var string $site_title
 * @var string $site_description
 * @var \Swing\System\View $this
 */
?>
<div id="topban">
<table border="0" height="72" cellpadding="0" cellspacing="0">
<tr>
<td width="6" background="/img/b_l.jpg"></td>
<td background="/img/b_c.jpg">
<table id="mordalenta" width="690" border="0" cellpadding="0" cellspacing="0">
<tr>
<td>
    <div style="width: 50px;text-align: center;margin-top: 5px;">
        <a class="show_popup" rel="lenta_form" href="#">
            <img src="/img/plusik2.jpg" alt="Плюс" width="55" height="55"/>
        </a>
    </div>
    <div class="popup lenta_form">
        <a class="close" href="#">Close</a>
        <h2>Лента</h2>
        <p class="lenta-red">Реклама услуг и коммерческих вечеринок запрещена</p>
        <label>Стоимость: 100 баллов</label>
        <br/>
        <label>Для ВИП: 50 баллов</label>
        <br/>
        <label>Ваши баллы : <?= $myrow->rate ?></label>
        <?php if (($myrow->status === 1 && $myrow->rate > 100) || ($myrow->isVip() && $myrow->rate > 50)) { ?>
            <form name="lents" method="post" action="" onsubmit="return validate_lenta()">
                <textarea name="textlenta"></textarea>
                <div style="text-align: center;">
                    <button name="addlenta" type="submit" class="btn btn-primary">Добавить за <?php echo $myrow->isVip() ? 50 : 100; ?></button>
                </div>
            </form>
        <?php } elseif ($myrow->status !== 1) { ?>
            <div>
                <p>
                    Ваша анкета не прошла модерацию
                    <br/>
                    Вы не можете добавлять сообщения
                </p>
            </div>
        <?php } else { ?>
            <div>
                <p>
                    У вас недостаточно баллов
                    <br/>
                    для отправки сообщения
                    <br/>
                    <a href="/viewdiary_69">Как заработать баллы?</a>
                </p>

            </div>
        <?php } ?>
    </div>
</td>
<?php
if (!$lenta = $cache->get('mordalenta')) {
    $sql = 'select l.id,l.id_user,l.text,u.login,u.pic1,u.gender,u.vip_time,u.city
        from lenta l
        join users u on l.id_user = u.id
        where u.status != 2
    order by l.id desc limit 10';
    $result = $dbh->query($sql);
    ob_start();
    while ($row = $result->fetch()) {
        $background = '#fff';
        if (strtotime($row['vip_time']) - $_SERVER['REQUEST_TIME'] > 0) {
            $back = false;
            $results = $dbh->query('select v.background
                from `option` o
                left join vip_background v on v.id = o.vip_background
            where o.u_id = ' . (int)$row['id_user']
            );
            if ($results->rowCount()) {
                $back = $results->fetchColumn();
            }
            $background = $back ? 'url(\'' . $back . '\')' : 'url(\'/img/vip.jpg\')';
        }
        ?>
        <td>
            <div onclick="getLenta(this);" ondblclick="dblClickLenta(<?php echo $row['id_user']; ?>);" class="border-box tiplen" style="background: url('/avatars/user_thumb/<?php echo $row['pic1']; ?>') no-repeat center;"></div>
            <div class="lst" style="display: none">
                <div class="lenta-tip">
                    <div class="tooltip-pointer-down" style="background: <?php echo $background; ?>">
                        <a class="hover-tip" href="/id<?php echo $row['id_user']; ?>">
                            <img src="/img/info_small_<?php echo $row['gender']; ?>.png" width="15" height="14" alt="gender"/>
                            <strong><?php echo $row['login']; ?></strong>
                        </a>
                        <br/>
                        <?php echo $row['city']; ?>
                    </div>
                    <div><?php echo nl2br(hyperlink($row['text'])) ?></div>
                </div>
            </div>
        </td>
    <?php }
    $lenta = compress(ob_get_clean());
    $cache->set('mordalenta', $lenta);
}
echo $lenta;
?>
</tr>
</table>
</td>
<td width="6" background="/img/b_r.jpg"></td>
</tr>
</table>
</div>
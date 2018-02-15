<table width=235 cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 0 solid black;">
<tr>
<td align=center height=33 style="background: url('/img/tablehead.jpg') no-repeat;">
    <b>СТАТИСТИКА</b>
</td>
</tr>
<tr>
<td style="padding-left: 30px;">
<div id="statistic">
<?php
/** @var  $myrow array|null */

if (!$a_count = $this->cache->get('a_count')) {
    $a_count = $this->dbh->query('select count(*) from users')->fetchColumn();
    $this->cache->set('a_count', $a_count);
}
echo '<strong>Всего анкет:</strong> ', $a_count, '<br>';

if (!$g_online = $this->cache->get('visiters')) {
    $g_online = random_int(50, 100);
    $this->cache->set('visiters', $g_online);
}

if (!$ondata = $this->cache->get('online_users')) {
    $sql = 'select ut.id,ut.last_view,u.login,u.admin,u.vip_time,
        u.vipsmile,u.moderator,u.pic1,u.gender,u.city,u.birthday
        from users_timestamps ut
        join users u on u.id = ut.id
        where ut.last_view > DATE_SUB(NOW(), interval ' . config('activity_time') . ' second)
    order by u.city, u.login';

    $ondata = $this->dbh->query($sql)->fetchAll();
    $this->cache->set('online_users', $ondata, 300);
}
$u_online = count($ondata);
$t_online = (int)$g_online + $u_online;

echo '<a style="font-weight: bolder;color:#11638c" href="/onlinemeet_1_1">Всего онлайн:</a> <b>', $t_online, '</b><br />Гостей - ', $g_online, '<br>';
echo 'Пользователей - ', $u_online, '<br /><br />';

if (!empty($ondata)) {
    $dateB = date('m-d');
    $city_stat = $this->myrow->city;
    $susers = [];
    $spusers = [];
    $ssity = mb_strtolower($city_stat);

    foreach ($ondata as $row) {
        if (!strcmp($ssity, mb_strtolower($row['city']))) {
            $row['login'] = '<span class="c-0">' . $row['login'] . '</span>';
            $susers[] = $row;
        } else {
            $spusers[] = $row;
        }
    }
    $onusers = array_merge($susers, $spusers);

    foreach ($onusers as $o_u) {
        if ($dateB === substr($o_u['birthday'], 5)) :?>
            <img src="/img/dr.gif" width="19" height="23" alt="birthday"/>
        <?php endif; ?>
        <?php if (strtotime($o_u['vip_time']) - $_SERVER['REQUEST_TIME'] >= 0) :?>
            <img src="<?php echo \Swing\Arrays\VipSmiles::$array[$o_u['vipsmile']]; ?>">
        <?php endif; ?>
        <a href="/id<?= $o_u['id'] ?>" class="m-<?php echo $o_u['moderator']; ?> a-<?php echo $o_u['admin']; ?>"><?php echo $o_u['login']; ?></a>
        <a class="hover-tip" href="/id<?= $o_u['id'] ?>"><img src="/img/info_small_<?php echo $o_u['gender']; ?>.png" width="13" height="12" alt="gender"/></a>
        <?php
        /** @noinspection NotOptimalIfConditionsInspection */
        if ($this->myrow->isUser() && (int)$o_u['id'] !== $this->myrow->id) {
            if (empty($_COOKIE['is_mobile'])) :?>
                <a href="privat_<?= $o_u['id'] ?>" onclick="return openPrivate(this.href,<?= $o_u['id'] ?>);">
            <?php else: ?>
                <a href="privat_<?= $o_u['id'] ?>">
            <?php endif; ?>
            <img src="/img/privat.gif" width="15" height="15" alt="отправить сообщение"/></a>
        <?php } ?>
        <br>
    <?php }
} ?>
</div>
</td>
</tr>
</table>
<?php
/**
 * @var \System\View $this
 */
$dbh = db();
$myrow = auth();
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Поиск<?php $this->stop(); ?>
<?php $this->start('description'); ?>Поиск анкет<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .td-padding>td{padding:5px 0}
    .f-img{opacity:.8}
    .f-img:hover{opacity:1}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<table  rules="none" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 0 solid black;" width="100%">
<tr><td height="40" align="center" bgcolor="#cad9ff"><b>Виртуальная служба знакомств</b></td></tr>
<tr>
<td>
<form method="get" action="findresult">
<table width="100%">
<tr>
    <td width="50%" valign="top">
        <table border="0">
            <tr class="td-padding">
                <td width="80"><label for="gender">Пол</label></td>
                <td>
                    <select id="gender" name="gender">
                    <?php foreach(\App\Arrays\Genders::$gender as $uagen => $uagens){?>
                        <option value="<?php echo $uagen; ?>"><?php echo $uagens; ?></option>
                    <?php }?>
                    </select>
                </td>
            </tr>
            <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
            <tr class="td-padding">
                <td><label for="agef">Возраст</td>
                <td>
                    <label for="agef">от</label>&nbsp;
                    <select id="agef" name="agef">
                        <option  value="18" selected="selected">18</option>
                        <?php for($i = 19;$i < 61;$i++) {?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php }?>
                    </select>
                    &nbsp;<label for="aget">до</label>&nbsp;
                    <select id="aget" name="aget">
                    <option  value="60" selected="selected">60</option>
                        <?php for($i = 18;$i < 60;$i++) {?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                        <?php }?>
                    </select>
                </td>
            </tr>
            <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
            <tr class="td-padding">
                <td>Цель</td>
                <td>
                    <select name="purposes">
                        <option value="0">Не выбрано</option>
                        <?php foreach(\App\Arrays\Purposes::$array as $uapu => $uapus) {?>
                            <option value="<?php echo $uapu; ?>"><?php echo $uapus; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
            <tr class="td-padding">
                <td>Страна</td>
                <td>
                    <select name="country">
                        <option value="0">Не важно</option>
                    <?php
                    $country = $myrow->country;
                    foreach(\App\Arrays\Country::$array as $c=>$cs) {?>
                        <option value="<?php echo $c; ?>" <?php ($country !== $c)?:print 'selected="selected"'; ?>><?php echo $cs; ?></option>
                    <?php }?>
                    </select>
                </td>
            </tr>
            <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
            <tr class="td-padding">
                <td><label for="find_city">Город</label></td>
                <td>
                    <input id="find_city" type="text" class="text" style="width: 97%;padding: 5px" name="find_city" value="<?php echo html($myrow->city); ?>">
                </td>
            </tr>
            <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
            <tr class="td-padding">
                <td>Ник</td>
                <td>
                    <input type="text" class="text" style="width: 97%;padding: 5px" name="find_nik">
                </td>
            </tr>
            <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>

            <tr class="td-padding">
                <td colspan="2">
                    <label><input type="checkbox" name="find_alb"> Только с фото</label>
                </td>
            </tr>
            <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
            <tr class="td-padding">
                <td colspan="2">
                    <label><input type="checkbox" name="find_real"> Только реальные</label>
                </td>
            </tr>
            <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
            <tr class="td-padding">
                <td colspan="2">
                    <label><input type="checkbox" name="find_new"> Сначала новые</label>
                </td>
            </tr>
            <tr><td height="1" colspan="2" bgcolor="#336699"></td></tr>
            <tr class="td-padding">
					<td colspan="2">
						<input class="btn btn-primary" type="submit" value="Искать">
					</td>
				</tr>
        </table>
    </td>
    <td width=50% valign=center align=center>
        <table border=0 width=100%>
            <tr>
                <td align=center>
                    <a href="/hotmeet"><img class="f-img" src="/img/meet/01.jpg" width="190" height="112" alt="travel"></a>
                </td>
                <td align=center>
                    <a href="/onlinemeet"><img class="f-img" src="/img/meet/02.jpg" width="190" height="112" alt="travel"></a>
                </td>
            </tr>
            <tr>
                <td align=center>
                    <?php if($myrow->isUser()) :?>
                        <a href="/nowmeet"><img class="f-img" src="/img/meet/03.jpg" width="190" height="112" alt="travel"></a>
                    <?php endif; ?>
                </td>
                <td align=center>
                    <a href="/travel"><img class="f-img" src="/img/meet/04.jpg" width="190" height="112" alt="travel"></a>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
</form>
</td>
</tr>
<tr>
<td>
<table border=0 width=100%>
<tr>
<td colspan=6 align=center valign=center height=35 bgcolor=#cad9ff>
    <a href=/viewdiary_132><img src=img/vip.gif border=0 ></a><b>5 случайных VIP пользователей</b> <a href=/viewdiary_132>подробнее</a> </td>
</tr>
<?php

$sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
  u.real_status, u.visibility, u.hot_time, u.regdate,
    u.vip_time, u.now_status, u.hot_text,
    u.vipsmile,u.admin, u.moderator, u.city,
    u.login, u.fname, u.gender, u.about,
    ut.last_view  
from 
  users u
  join users_timestamps ut on ut.id = u.id
where u.vip_time >= addtime(timestamp(date(NOW())),\'23:59:59\') AND u.status=1 
order by rand() desc limit 5';

$users = $dbh->query($sql)->fetchAll(PDO::FETCH_CLASS, \App\Models\RowUser::class);

if (!empty($users)) :?>
<tr>
    <td colspan=6 align=center valign=center>
        <?php foreach($users as $user) : ?>
            <?php anketa_usr_row($myrow, $user); ?>
        <?php endforeach; ?>
    </td>
</tr>


<?php endif;?>
</table>
<table border=0 width=100%>
<tr>
<td colspan=6 align=center valign=center height=35 bgcolor=#cad9ff><b>TOP 5 рейтинга</b> (рейтинг по количеству баллов)</td>
</tr>
<?php

$sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
    u.real_status, u.visibility, u.hot_time, u.regdate,
    u.vip_time, u.now_status, u.hot_text,
    u.vipsmile,u.admin, u.moderator, u.city,
    u.login, u.fname, u.gender, u.about,
    ut.last_view
from users u
  join users_timestamps ut on ut.id = u.id and ut.last_view >= date_sub(NOW(), INTERVAL 30 DAY) 
where 
  u.status=1 
order by u.rate desc limit 5';

$users = $dbh->query($sql)->fetchAll(PDO::FETCH_CLASS, \App\Models\RowUser::class);

if (!empty($users)) :?>
    <tr>
        <td colspan=6 align=center valign=center>
            <?php foreach($users as $user) : ?>
                <?php anketa_usr_row($myrow, $user); ?>
            <?php endforeach; ?>
        </td>
    </tr>
<?php endif;?>
<tr>
<td colspan=6 align=center valign=center height=35 bgcolor=#cad9ff><b>Новые анкеты на сайте</b></td>
</tr>
<?php
$sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
    u.real_status, u.visibility, u.hot_time, u.regdate,
    u.vip_time, u.now_status, u.hot_text,
    u.vipsmile,u.admin, u.moderator, u.city,
    u.login, u.fname, u.gender, u.about,
    ut.last_view 
from users u
  join users_timestamps ut on ut.id = u.id 
where u.status=1 
order by u.regdate desc limit 10';

$users = $dbh->query($sql)->fetchAll(PDO::FETCH_CLASS, \App\Models\RowUser::class);

if (!empty($users)) :?>
<tr>
    <td colspan=6 align=center valign=center>
    <?php foreach($users as $user) : ?>
        <?php anketa_usr_row($myrow, $user); ?>
    <?php endforeach; ?>
    </td>
</tr>
<?php endif;?>
</table>
</td>
</tr>
</table>
<?php $this->stop(); ?>
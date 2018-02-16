<div id="preload-left" class="border-box-tip">
	<span class="tooltip-pointer3"></span>
	<div id="response-preload-left"></div>
</div>

<table border=0 width=100%>
<?php if(($adverts_1 = $this->cache->get('advert_1')) === false) {

    $sth = $this->dbh->query('select url, target, img from advert_baner where status = 1 and position = 1 order by date_start desc');

    if($sth->rowCount()) {
        ob_start();
        while ($row = $sth->fetch()) {?>
            <tr>
                <td align=center>
                    <table width=100% style="border:1px solid #AAA;">
                        <tr>
                            <td bgcolor=#e1eaff align=center style="border:1px solid #AAA; height: 20px; padding: 5px;">
                                <strong>---</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 5px;">
                                <a href="<?= $row['url']; ?>" target="<?= $row['target']; ?>">
                                    <img class="border-box" style="padding: 0" src="/<?= $row['img']; ?>" width="170" alt="adi">
                                </a>
                            </td>
                        </tr>
                    </table>
                    <br />
                </td>
            </tr>
        <?php }
        $adverts_1 = ob_get_clean();
    } else {
        $adverts_1 = '';
    }
    $this->cache->set('advert_1', $adverts_1);
}
echo $adverts_1;
?>
<tr>
<td align=center>

<table width=100% style="border:1px solid #AAA;">
<tr><td bgcolor=#e1eaff align=center style="border:1px solid #AAA; height: 20px; padding: 5px;">
<b>Анонсы вечеринок (<a href="/all_events">все</a>)</b>
</td></tr>
<tr><td style="padding: 10px 5px;">
<?php
if(!$events = $this->cache->get('party_events')) {
    $sql = 'select id,title,img,begin_date,end_date,club,v_count,city,address 
          from `events` 
          where end_date > now() 
          and `status` = 1 
        order by begin_date asc';

    $sth = $this->dbh->query($sql);

    $events = [];

    while ($row = $sth->fetch()) {
        $row['ts_b'] = strtotime($row['begin_date']);
        $row['ts_e'] = strtotime($row['end_date']);
        $events[] = $row;
    }

    $this->cache->set('party_events',$events,3600);
}

$u_events = [];

if (!empty($events) && $this->myrow->isUser()) {

    $u_city = mb_strtolower($this->myrow->city);

    foreach ($events as $key => &$value) {
        if (strcmp($u_city, mb_strtolower($value['city'])) === 0) {
            $value['city'] = '<i style="color: #0000FF">' . html($value['city']) . '</i>';
            $u_events[] = $value;
            unset($events[$key]);
        }
    }

    unset($value);
}

$all_events = array_merge($u_events,$events);

unset($u_city,$u_events,$events);

if(!empty($all_events)) {
    foreach ($all_events as $value) {?>
    <div class="maindiv border-box">
        <a href="/event_<?=$value['id'];?>">
            <span><?=date('d-m-Y',strtotime($value['begin_date']));?></span>
            <span><?= $value['city']; ?></span>
            <span><?php echo subText($value['title'],45,'&nbsp;...'); ?></span>
        </a>
    </div>
<?php }}?>
<br>
<div style="text-align: center;"><a href="/my_events" style="font-weight: bold;">&gt;&gt; Добавить анонс &lt;&lt;</a></div>
</td>
</tr>
</table>

</td>
</tr>
<?php if(($adverts_2 = $this->cache->get('advert_2')) === false) {
    $sth = $this->dbh->query('select url, target, img from advert_baner where status = 1 and position = 2 order by date_start desc');

    if($sth->rowCount()) {
        ob_start();
        while ($row = $sth->fetch()) {?>
            <tr>
                <td align=center>
                    <table width=100% style="border:1px solid #AAA;">
                        <tr>
                            <td bgcolor=#e1eaff align=center style="border:1px solid #AAA; height: 20px; padding: 5px;">
                                <strong>---</strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 10px 5px;">
                                <a href="<?= $row['url']; ?>" target="<?= $row['target']; ?>">
                                    <img class="border-box" style="padding: 0" src="/<?= $row['img']; ?>" width="170" alt="adi">
                                </a>
                            </td>
                        </tr>
                    </table>
                    <br />
                </td>
            </tr>
        <?php }
        $adverts_2 = ob_get_clean();
    } else {
        $adverts_2 = '';
    }
    $this->cache->set('advert_2', $adverts_2);
}
echo $adverts_2;
?>
<tr>
<td align=center>


<table width=100% style="border:1px solid #AAA;">
<tr>
<td bgcolor=#e1eaff align=center style="border:1px solid #AAA; height: 20px; padding: 5px;">
<b>Именинник</b> <b><a href="/birthday">(все)</a></b>
</td>
</tr>
<tr>
<td style="padding: 10px 5px;">

<?php
$hash = (date('m')*31) + date('j');
$sql = 'select u.login, u.regdate, u.photo_visibility, u.id, u.pic1, u.gender, u.city, u.birthday 
from users u 
join users_timestamps ut on ut.id = u.id 
where u.birthday_hash = ' . $hash . ' 
and u.pic1 <> \'\' and u.status <> 2 and u.pic1 <> \'net-avatara.jpg\' and u.gender <> 1 
order by rand() desc limit 1';

$user_birthday = $this->dbh->query($sql)->fetch();
?>
<?php if(!empty($user_birthday)) : ?>
<table width="100%" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 0 solid black;">
<tr>
<td align="center">
<table border="0">
<tr>
<td width="100" height="100" align="center" valign="center" bgcolor="#fff6d8">
<div id="imgth">
    <a href="<?php echo $this->myrow->isUser() ? '/id' . $user_birthday['id'] : '#showimagemsg'; ?>">
        <div class="avatar" style="background-image: url(<?php echo avatar($this->myrow, $user_birthday['pic1'], $user_birthday['photo_visibility']); ?>)"></div>
    </a>
    <b><?php echo html($user_birthday['login']); ?></b>
    <br>
    <?php echo \Swing\Arrays\Genders::$gender[$user_birthday['gender']]; ?>
    <br>
    <span class="u-city-<?php echo (int)(mb_strtolower($this->myrow->city) === mb_strtolower($user_birthday['city'])); ?>"><?php echo html($user_birthday['city']); ?></span>
</div>
</td>
</tr>
</table>
</td>
</tr>
</table>
<?php endif;?>
</td>
</tr>
</table>
</td>
</tr>
<?php if($this->myrow->isUser()) {?>
<tr>
<td align=center>
<table width=100% style="border:1px solid #AAA;">
<tr>
    <td bgcolor=#e1eaff align=center style="border:1px solid #AAA; height: 20px; padding: 5px;">
        <strong>Отвлекись и улыбнись</strong><br><a href="/viewugthread_1770_1">Перлы из реальных анкет</a>
    </td>
</tr>
<tr>
    <td style="padding: 10px 5px;">
        <div class="maindiv border-box">
            <?php
            if(!$sm_res = $this->cache->get('smiles')) {
                $sm_res = $this->dbh->query('select title,text from smile order by id')->fetchAll();
                $this->cache->set('smiles',$sm_res);
            }
            $sm_res = $sm_res[array_rand($sm_res)];
            ?>
            <strong><?php echo $sm_res['title']; ?></strong>:
            <br>
            <em><?php echo nl2br($sm_res['text']); ?></em>
        </div>
        <div style="text-align: center;margin-top: 10px">
            <button id="m-smile" class="btn btn-default">Еще ...</button>
        </div>
    </td>
</tr>
</table>
</td>
</tr>
<?php }?>
<tr>
<td align=center>


<table width=100% style="border:1px solid #AAA;">
<tr><td bgcolor=#e1eaff align=center style="border:1px solid #AAA; height: 20px; padding: 5px;">
<b>Новые группы <a href="/ugrouplist_new_1">(все)</a></b>
</td></tr>

<tr><td style="padding: 10px 5px;">
<?php
if(!$group = $this->cache->get('new_group')) {
	$sth = $this->dbh->query('SELECT `users`.`login`, `users`.`id`, `users`.`gender`, `ugroup`.`ugroup_id`, `ugroup`.`ug_title`
      FROM `ugroup`,`users` WHERE `users`.`id`=`ugroup`.`user_id`
      AND `ug_dlt`= 0 
    ORDER BY ugroup_id DESC LIMIT 3');
	ob_start();
	while ($myrow_diary_m = $sth->fetch()) {?>
        <div class="maindiv border-box">
            <a href="/viewugroup_<?=$myrow_diary_m['ugroup_id'];?>"><?=nickartGlobal(smile($parse->parse(subText($myrow_diary_m['ug_title'],35,'&nbsp;...'))));?></a>
            <br>
            <a class="hover-tip" href="/id<?=$myrow_diary_m['id'];?>">
                <img src="/img/info_small_<?php echo $myrow_diary_m['gender'];?>.png" width = "15" height="14">
            </a>
            <strong><?=$myrow_diary_m['login'];?></strong>
        </div>
    <?php }
	$group = ob_get_clean();
	$this->cache->set('new_group',$group);
	}	
	echo $group;	
?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td align=center>
<table width=100% style="border:1px solid #AAA;">
<tr>
<td bgcolor=#e1eaff align=center style="border:1px solid #AAA; height: 20px; padding: 5px;">
<b>Новые комментарии в дневниках  <a href="/last_comments?getLastDiaryComment">(все)</a></b>
</td></tr>
<tr><td style="padding: 10px 5px;">
<?php
if(!$new_com_diary = $this->cache->get('new_com_diary')) {
$sth = $this->dbh->query('select u.id,u.login,u.gender,dc.diary_id,dc.comment_id,dc.comment_text
  FROM diary_comments dc
    JOIN users u ON u.id = dc.user_id
    join diary d on d.id_di = dc.diary_id
  where dc.deleted = 0 and d.deleted = 0
  ORDER BY dc.comment_id
  DESC LIMIT 5');
ob_start();

while ($myrow_diary_k = $sth->fetch()) {
    $myrow_diary_k['comment_text'] = preg_replace('~<img.+?>~', '', $myrow_diary_k['comment_text'])
?>
<div id="diarycom" class="maindiv border-box">
	<a class="hover-tip-left" data-rel="<?=$myrow_diary_k['comment_id'];?>" href="/viewdiary_<?=$myrow_diary_k['diary_id'];?>"><?=nickartGlobal(smile($parse->parse(subText($myrow_diary_k['comment_text'],45,'&nbsp;...'))));?></a>
	<a class="hover-tip" href="/id<?=$myrow_diary_k['id'];?>"><img src="/img/info_small_<?=$myrow_diary_k['gender'];?>.png" width="15" height="14" alt="gender" /></a>&nbsp; <strong><?=$myrow_diary_k['login'];?></strong>
</div>
<?php }
$new_com_diary = ob_get_clean();
	$this->cache->set('new_com_diary',$new_com_diary);
}
echo $new_com_diary;
?>

</td></tr>
</table>


<br />


</td>
</tr>

<tr>
<td align=center>

<table width=100% style="border:1px solid #AAA;">
<tr><td bgcolor=#e1eaff align=center style="border:1px solid #AAA; height: 20px; padding: 5px;">
<b>Новые комментарии в статьях <a href="/last_comments?getLastStoryComment">(все)</a></b>
</td></tr>
<tr><td style="padding: 10px 5px;">
<?php
if(!$new_com_story = $this->cache->get('new_com_story')) {
	$sth = $this->dbh->query('SELECT u.id,u.login,u.gender,sc.stories_id,sc.comment_id,sc.comment_text FROM stories_comments sc JOIN users u ON u.id = sc.user_id ORDER BY sc.comment_date DESC LIMIT 5');
	ob_start();
	while ($myrow_diary_k = $sth->fetch()){?>
<div id="storycom" class="maindiv border-box">
	<a class="hover-tip-left" data-rel="<?=$myrow_diary_k['comment_id'];?>" href="/viewstory_<?=$myrow_diary_k['stories_id'];?>"><?=nickartGlobal(smile($parse->parse(subText($myrow_diary_k['comment_text'],45,'&nbsp;...'))));?></a>
	<a class="hover-tip" href="/id<?=$myrow_diary_k['id'];?>"><img src="/img/info_small_<?= $myrow_diary_k['gender'];?>.png" width="15" height="14" alt="gender" /></a>&nbsp; <strong><?=$myrow_diary_k['login'];?></strong>
</div>
<?php }
	$new_com_story = ob_get_clean();
	$this->cache->set('new_com_story',$new_com_story);
}
echo $new_com_story;
?>
</td></tr>
</table>
<br />
</td>
</tr>
<tr>
<td align=center>
<?php
if ($this->myrow->rate >= 50) {?>
<table width=100% style="border:1px solid #AAA;">
<tr><td bgcolor=#e1eaff align=center style="border:1px solid #AAA; height: 20px; padding: 5px;">
<b>Новые комментарии в фотоальбомах  <a href="/photo_comm_1">(все)</a></b>
</td></tr>
<tr><td style="padding: 10px 5px;">
<?php
if(!$new_com_photo = $this->cache->get('new_com_photo')) {
	$sth = $this->dbh->query('SELECT cc.pid,p.aid,u.id,u.login,u.gender,cc.msg_id,cc.msg_body FROM cpg_comments cc JOIN users u on u.id = cc.author_id join photo_pictures p on p.pid = cc.pid  ORDER BY cc.msg_date DESC LIMIT 6');
	ob_start();
	while ($myrow_diary_f = $sth->fetch()) {?>
<div id="photocom" class="maindiv border-box">
	<a class="hover-tip-left" data-rel="<?=$myrow_diary_f['msg_id'];?>" href="/albums_<?=$myrow_diary_f['aid'];?>_<?=$myrow_diary_f['pid'];?>"><?=nickartGlobal(smile($parse->parse(subText($myrow_diary_f['msg_body'],45,'&nbsp;...'))));?></a>
	<a class="hover-tip" href="/id<?=$myrow_diary_f['id'];?>"><img src="/img/info_small_<?=$myrow_diary_f['gender'];?>.png" width="15" height="14" alt="gender" /></a>&nbsp; <strong><?=$myrow_diary_f['login'];?></strong>
</div>
<?php }
	$new_com_photo = ob_get_clean();
	$this->cache->set('new_com_photo',$new_com_photo);
}
 echo $new_com_photo;
?>
</td></tr>
</table>
<?php }?>
<br />
</td>
</tr>
</table>
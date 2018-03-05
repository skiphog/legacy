<?php
/**
* @var \Swing\System\View $this
*/

use Swing\Exceptions\NotFoundException;

$dbh = db();
$myrow = auth();

$g_id = request()->getInteger('id');

$sql = 'select g.ug_title g_title,g.ug_descr g_content, g.ug_avatar g_avatar,g.ug_club g_club,g.ug_hidden g_hidden,
  u.id g_master_id,u.login g_master_login,u.gender g_master_gender,a.ugu_permission g_access,
  (select count(*) from ugusers where ugu_permission = 1 and ugroup_id = g.ugroup_id) g_cnt_user,
  (select count(*) from ugthread where ugt_dlt=0 and ugroup_id = g.ugroup_id) g_cnt_thread,
  (select sum(ug_comments_count) from ugthread where ugt_dlt=0 and ugroup_id = g.ugroup_id) g_cnt_message
from ugroup g
join users u on u.id = g.user_id
left join ugusers a on a.ugroup_id = g.ugroup_id and a.user_id = ' . $myrow->id . '
where g.ugroup_id = ' . $g_id . ' and g.ug_dlt = 0 limit 1';

$sth = $dbh->query($sql);

if(!$sth->rowCount()) {
    throw new NotFoundException('Группы не существует или удалена');
}

$group = mysql_fetch_assoc($result);


if($group['g_club'] == 1) {
    $group['g_belongs'] = $group['g_hidden']? 'Закрытый клуб':'Открытый клуб';
}else{
    $group['g_belongs'] = $group['g_hidden']? 'Закрытая группа':'Открытая группа';
}

$g_access = [
    'user' => $group['g_access'] == 1,
    'waiter' => $group['g_access'] === '0',
    'master' => $group['g_master_id'] == $myrow['id']
];
$g_access['access'] = !$group['g_hidden'] || $g_access['user'] || $g_access['master'];


if($g_access['master']) {
    $sql = 'select count(*) from ugusers where ugroup_id = '.$g_id.' and ugu_permission = 0';
    $result = mysql_query($sql) or die();
    $waiters = mysql_result($result, 0);
}

if($g_access['access'] || $myrow['moderator']) {
    $sql = 'select
          a.ugthread_id t_id,a.ugt_title t_title,a.ug_comments_count t_cnt,a.ugt_date t_date,a.polls_exist t_poll,a.v_count t_v_cnt,a.party t_party,a.sticky t_sticky,
          c.id t_master_id,
          d.ugc_date c_date,
          e.id c_master_id, e.login c_master_login,e.gender c_master_gender,
          if(d.ugcomments_id is null, a.ugt_date, d.ugc_date) sort_date
        from
          ugthread a
          join users c on a.user_id = c.id
          left join (
              select
                c1.ugthread_id,
                MAX( c1.ugcomments_id) max_id
              from
                ugcomments c1
                join ugthread t1 on t1.ugthread_id = c1.ugthread_id and t1.ugroup_id = '.$g_id.'
              where c1.ugc_dlt = 0
              group by  t1.ugthread_id) b on  a.ugthread_id = b.ugthread_id
          left join ugcomments d on  a.ugthread_id = d.ugthread_id and d.ugcomments_id = b.max_id
          left join users e on d.user_id = e.id
        
        where
          a.ugroup_id = '.$g_id.' and a.ugt_dlt = 0
        order by a.sticky desc ,sort_date desc';

    $result = mysql_query($sql) or die;

    $threads = [];

    while ($row = mysql_fetch_assoc($result)) {
        $threads[] = $row;
    }

}
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?><?php $this->stop(); ?>
<?php $this->start('description'); ?><?php $this->stop(); ?>

<?php $this->start('style'); ?>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>
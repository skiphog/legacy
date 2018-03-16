<?php
/**
* @var \System\View $this
*/
use App\Components\SwingDate;
use App\Components\Parse\All as AllParse;

$dbh = db();
$myrow = auth();

$parse = new AllParse();

$sql = 'select distinct b.ugroup_id
from ugroup b
  join ugusers a on a.ugroup_id=b.ugroup_id
where b.user_id = ' . $myrow->id . ' or a.user_id = ' . $myrow->id . ' and a.ugu_permission=1 and ug_dlt = 0';

if ($groups_list = $dbh->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0)) {

    $groups_list = implode(',', $groups_list);

    $sql = 'select t.ugt_title, t.ugt_date, t.ugthread_id, t.ugroup_id, g.ug_title
      from ugthread t
        join ugroup g on g.ugroup_id = t.ugroup_id and t.ugroup_id in ( ' . $groups_list . ' )
        where g.ug_dlt = 0 and t.ugt_dlt = 0
      order by ugt_date desc
    limit 5';

    $sth = $dbh->query($sql);
    $threads = [];

    while ($row = $sth->fetch()) {
        $row['date'] = new SwingDate($row['ugt_date']);
        $threads[] = $row;
    }

    if (!empty($threads)) {
        $sql = 'select c.ugthread_id, c.ugc_date, c.ugc_text,
          u.login commlogin, u.pic1 commpic1, u.gender commgender, u.photo_visibility commphoto_visibility, u.id commid,
          t.ugt_title, t.ugroup_id, t.ug_comments_count cnt, t.ugt_hidden,
          g.ug_title, g.ug_hidden
            from ugcomments c
              join ugthread t on t.ugthread_id = c.ugthread_id
              join ugroup g on g.ugroup_id = t.ugroup_id
              join users u on u.id = c.user_id
            where ugc_dlt = 0 and t.ugt_dlt = 0 and g.ug_dlt = 0 and t.ugroup_id in ('. $groups_list .')
            order by ugc_date desc
        limit 0, 30';

        $comments = $dbh->query($sql)->fetchAll();
    }

}

?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Акстивность в моих группах<?php $this->stop(); ?>
<?php $this->start('description'); ?>Активность в моих группах<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<?php var_dump($threads, $comments); ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>


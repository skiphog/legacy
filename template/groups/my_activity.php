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

    $data = implode(',', $groups_list);

    $sql = 'select t.ugt_title, t.ugt_date, t.ugthread_id, t.ugroup_id, g.ug_title
      from ugthread t
        join ugroup g on g.ugroup_id = t.ugroup_id and t.ugroup_id in ( ' . $data . ' )
        where g.ug_dlt = 0 and t.ugt_dlt = 0
      order by ugt_date desc
    limit 5';

    $themes = $dbh->query($sql)->fetchAll();


}

?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Акстивность в моих группах<?php $this->stop(); ?>
<?php $this->start('description'); ?>Активность в моих группах<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<?php var_dump($themes); ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>


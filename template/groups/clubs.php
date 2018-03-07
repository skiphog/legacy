<?php
/**
 * @var \System\View $this
 */

$dbh = db();
$myrow = auth();

$sql = 'select g.ugroup_id, g.ug_title, g.ug_descr, g.ug_avatar, g.ug_hidden, tcnt, ucnt, u.city
from ugroup g
  left join (select ugroup_id, count(ugthread_id) as tcnt
    from ugthread where ugt_dlt = 0
  group by ugroup_id) b on g.ugroup_id = b.ugroup_id
  left join (select ugroup_id, count(user_id) as ucnt
    from ugusers where ugu_permission = 1
  group by ugroup_id) d on g.ugroup_id = d.ugroup_id
  join users u on u.id = g.user_id
where g.ug_dlt = 0 and ug_club = 1
order by d.ucnt desc, g.ugroup_id desc';

$sth = $dbh->query($sql);
$clubs = $user_clubs = [];

while ($row = $sth->fetch()) {
    strCompare($myrow->city, $row['city']) ? $user_clubs[] = $row : $clubs[] = $row;
}
$groups = array_merge($user_clubs, $clubs);
?>

<?php $this->extend('groups/layout'); ?>

<?php $this->start('title'); ?>Региональные клубы<?php $this->stop(); ?>
<?php $this->start('description'); ?>Все региональные клубы на сайте<?php $this->stop(); ?>

<?php $this->start('content-group'); ?>
<?php if(!empty($groups)) : ?>
    <?php echo render('partials/groups', compact('groups')) ?>
<?php else : ?>
    Нет Клубов
<?php endif; ?>
<?php $this->stop(); ?>
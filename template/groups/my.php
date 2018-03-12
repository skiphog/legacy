<?php
/**
* @var \System\View $this
*/

$dbh = db();
$myrow = auth();

// Получить свои группы + получить количество пользователей, которые ждут модерации
$sql = 'select g.ugroup_id, g.ug_title, g.ug_descr, g.ug_avatar, g.ug_hidden, g.user_id, tcnt, ucnt, uncnt
from ugroup g
  left join (select ugroup_id, count(ugthread_id) as tcnt
     from ugthread where ugt_dlt = 0
     group by ugroup_id) b on g.ugroup_id = b.ugroup_id
  left join (select ugroup_id, count(user_id) as ucnt
     from ugusers where ugu_permission = 1
     group by ugroup_id) d on g.ugroup_id = d.ugroup_id
  left join (select ugroup_id, count(user_id) as uncnt
     from ugusers where ugu_permission = 0
     group by ugroup_id) f on g.ugroup_id = f.ugroup_id
where g.user_id = '. $myrow->id .' and g.ug_dlt = 0
order by d.ucnt desc, g.ugroup_id';

$master_groups = $dbh->query($sql)->fetchAll();
// Получить группы, в которых состою исключая группы, которые создал
$sql = 'select g.ugroup_id, g.ug_title, g.ug_descr, g.ug_avatar, g.ug_hidden,  g.user_id, tcnt, ucnt
from ugusers ug
  join ugroup g on g.ugroup_id = ug.ugroup_id and g.user_id <> ug.user_id
  left join (select ugroup_id, count(ugthread_id) as tcnt
     from ugthread where ugt_dlt = 0
     group by ugroup_id) b on g.ugroup_id = b.ugroup_id
  left join (select ugroup_id, count(user_id) as ucnt
     from ugusers where ugu_permission = 1
     group by ugroup_id) d on g.ugroup_id = d.ugroup_id
where ug.user_id = '. $myrow->id .' and ug.ugu_permission = 1 and g.ug_dlt = 0';

$my_groups = $dbh->query($sql)->fetchAll();
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Мои группы<?php $this->stop(); ?>
<?php $this->start('description'); ?>Мои группы<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<a class="btn btn-primary" href="/groups/create">Создать группу</a>
<?php if(!empty($master_groups)) : ?>
    <h2>Мои группы</h2>
    <?php echo render('partials/groups', ['groups' => $master_groups]) ?>
<?php endif; ?>
<?php if(!empty($my_groups)) : ?>
    <h2>Состою в группах</h2>
    <?php echo render('partials/groups', ['groups' => $my_groups]) ?>
<?php else : ?>
    <h2>Вы не состоите ни в одной группе</h2>
    <p><a href="/groups">Перейти к списку групп</a></p>
<?php endif; ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>
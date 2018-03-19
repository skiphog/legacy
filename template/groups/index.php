<?php
/**
 * @var \System\View $this
 */

$dbh = db();
$myrow = auth();
$page = request()->getInteger('page');

$sql = 'select count(*) from ugroup where ug_dlt = 0';
if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, $page, 20, 2);

    $sql = 'select g.ugroup_id, g.ug_title, g.ug_descr, g.ug_avatar, g.ug_hidden, g.user_id, tcnt, ucnt
        from ugroup g
          left join (select ugroup_id, count(ugthread_id) as tcnt
            from ugthread where ugt_dlt = 0
          group by ugroup_id) b on g.ugroup_id = b.ugroup_id
          left join (select ugroup_id, count(user_id) as ucnt
            from ugusers where ugu_permission = 1
          group by ugroup_id) d on g.ugroup_id = d.ugroup_id
        where g.ug_dlt = 0
        order by d.ucnt desc, g.ugroup_id desc
    limit '.$pagination->offset().', 20';

    $sth = $dbh->query($sql);

    if (!$sth->rowCount()) {
        exit('Внутренняя ошибка сайта.Пожалуйста повторите попытку');
    }

    $groups = $sth->fetchAll();
    $paging = $pagination->build();

    $paging_page = 'Одна страница';
    if (!empty($paging)) {
        $paging_page = render('partials/paginate', ['paginate' => $paging, 'link' => '/groups']);
    }
}
?>

<?php $this->extend('groups/layout'); ?>

<?php $this->start('title'); ?>Все группы<?php $this->stop(); ?>
<?php $this->start('description'); ?>Все группы на сайте<?php $this->stop(); ?>

<?php $this->start('content-group'); ?>
<?php if(!empty($groups)) : ?>
    <?php echo $paging_page; ?>
    <?php echo render('groups/partials/groups', compact('groups')) ?>
    <?php echo $paging_page; ?>
<?php else : ?>
    Нет груп
<?php endif; ?>

<?php $this->stop(); ?>
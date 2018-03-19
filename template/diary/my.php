<?php
/**
 * @var \System\View $this
 */

use App\Components\Parse\All as Allparse;

$dbh = db();
$myrow = auth();
$parse = new Allparse();

$page = request()->getInteger('page');
$paging_page = '';

$sql = 'select count(*) from diary where user_di = ' . $myrow->id . ' and deleted = 0';
if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, $page, 10, 2);

    $sql = 'select d.id_di, d.title_di, d.text_di, d.data_di,d.v_count,
      u.id, u.login, u.gender, u.pic1, u.city, u.fname,u.photo_visibility
      from diary d
        join users u on u.id = d.user_di
      where d.user_di = ' . $myrow->id . ' and d.deleted = 0 
    order by d.id_di desc limit ' . $pagination->offset() . ', 10';

    $sth = $dbh->query($sql);

    if (!$sth->rowCount()) {
        exit('Внутренняя ошибка сайта.Пожалуйста повторите попытку');
    }

    $diaries = $sth->fetchAll();
    $paging = $pagination->build();

    if (!empty($paging)) {
        $paging_page = render('partials/paginate', ['paginate' => $paging, 'link' => '/my/diaries']);
    }
}
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Мой дневник<?php $this->stop(); ?>
<?php $this->start('description'); ?>Мой дневнинк<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .my-diary {
        padding: 5px;
        margin-bottom: 20px;
        box-shadow: rgba(0,0,0,.09) 0 2px 0;
    }
    .my-diary-header{
        margin-bottom: 20px;
    }

    .my-diary-title {
        margin: 0;
        font-size: 20px;
    }
    .my-diary-control{
        margin: 0;
    }

    .my-diary-title > a {

    }

    .my-diary-date {
        margin: 0;
        color: #A99898;
        font-size: 0.9em;
    }
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<a class="btn btn-primary" href="/diaries/create">Добавить запись</a>
<h2>Мой дневник
    <?php if ($count > 0) : ?>
        (<?php echo $count; ?>&nbsp;<?php echo plural($count, 'запись|записи|записей'); ?>)
    <?php endif; ?>
</h2>
<?php echo $paging_page; ?>
<?php if (!empty($diaries)) : ?>
    <?php foreach ($diaries as $diary) : ?>
        <article class="my-diary">
            <header class="my-diary-header">
                <h2 class="my-diary-title">
                    <a href="/viewdiary_<?php echo $diary['id_di']; ?>"><?php echo html($diary['title_di']) ?></a></h2>
                <p class="my-diary-date">
                    <?php echo date('d-m-Y H:i', strtotime($diary['data_di'])); ?> |
                    <strong><?php echo (new \App\Components\SwingDate($diary['data_di']))->getHumans(); ?></strong>
                </p>
                <p class="my-diary-control">
                    Просмотров: <strong><?php echo $diary['v_count']; ?></strong>
                    <a href="/diaries/<?php echo $diary['id_di']; ?>/edit">Редактировать</a> &bull;
                    <a class="red" href="/diaries/<?php echo $diary['id_di']; ?>/delete" onclick="return window.confirm('Вы уверены что хотите удалить этот дневник?')">Удалить</a>
                </p>
            </header>
            <div><?php echo nl2br(imgart(smile($parse->parse($diary['text_di'])))); ?></div>
        </article>
    <?php endforeach; ?>
<?php else: ?>
    <p>Вы еще не делали запись</p>
<?php endif; ?>
<?php echo $paging_page; ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>

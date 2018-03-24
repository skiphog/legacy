<?php
/**
 * @var \System\View $this
 */

$dbh = db();
$myrow = auth();
$paging_page = '';

$page = request()->getInteger('page');
$parse = new \App\Components\Parse\NoSession();

$sql = 'select count(*) from diary where deleted = 0';

if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, $page, 10, 2);

    $sql = 'select d.id_di, d.title_di, d.text_di, d.data_di,
      u.id, u.login, u.gender, u.pic1, u.city, u.fname,u.photo_visibility
      from diary d
        join users u on u.id = d.user_di
      where d.deleted = 0 
    order by d.id_di desc limit ' . $pagination->offset() . ',10';

    $sth = $dbh->query($sql);

    if (!$sth->rowCount()) {
        exit('Внутренняя ошибка сайта.Пожалуйста повторите попытку');
    }

    $diaries = $sth->fetchAll();
    $paging = $pagination->build();

    if (!empty($paging)) {
        $paging_page = render('partials/paginate', ['paginate' => $paging, 'link' => '/diaries']);
    }
}?>

<?php $this->extend('layout/layout') ?>

<?php $this->start('title') ?>Дневники<?php $this->stop() ?>
<?php $this->start('description') ?>Дневники свингеров. Рассыказы и реальные истории.<?php $this->stop() ?>

<?php $this->start('content') ?>
<table width=100%>
    <tr><td><h2>Дневники</h2></td></tr>
    <tr><td><?php echo $paging_page; ?></td></tr>
    <tr><td height=2 bgcolor=#e1eaff></td></tr>
    <?php if(!empty($diaries)) : ?>
        <?php foreach($diaries as $diary) : ?>
            <tr>
                <td style="padding:5px;">
                    <h1>  <a href="/viewdiary_<?=$diary['id_di']?>"><?php echo html($diary['title_di']); ?></a></h1>
                    <div class="avatar" style="float:left;background-image: url(<?php echo avatar($myrow, $diary['pic1'], $diary['photo_visibility']); ?>)"></div>
                    <div>
                        <i><?php echo $diary['data_di']?></i>
                        <br>
                        Автор: <img src="/img/info_small_<?php echo $diary['gender'];?>.png" width="15" height="14">
                        <a href="/id<?=$diary['id']?>"><?=html($diary['login'])?></a>
                        <br>
                        <?php echo html($diary['fname']); ?>
                    </div>
                    <div style="clear: both;"></div>
                    <div>
                        <?php echo nl2br(smile(imgart_no_reg($parse->parse($diary['text_di']))));?>
                        <br>
                        <br>
                        <?php if($myrow->id === (int)$diary['id'] || $myrow->isModerator()) :?>
                            <a href="/diaries/<?=$diary['id_di'];?>/edit">редактировать</a>
                        <?php endif;?>
                    </div>
                </td>
            </tr>
            <tr><td height=2 bgcolor=#e1eaff></td></tr>
        <?php endforeach; ?>
        <tr><td><?php echo $paging_page; ?></td></tr>
    <?php else : ?>
    <tr>
        <td>Нет записей</td>
    </tr>
    <?php endif; ?>
</table>
<?php $this->stop() ?>
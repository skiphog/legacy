<?php
/**
* @var \System\View $this
*/

$dbh = db();
$myrow = auth();
$page = request()->getInteger('page');
$paging_page = '';

$sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility, u.real_status, u.visibility, u.hot_time, u.regdate, 
  u.vip_time, u.now_status, u.hot_text, u.vipsmile,u.admin, u.moderator, u.city, u.login, u.fname, u.gender, u.about, 
  ut.last_view
    from friends f
    join users u on u.id = f.fr_kto 
    join users_timestamps ut on ut.id = u.id 
  where f.fr_kogo = ' . $myrow->id . ' and f.fr_podtv_kto = 1 and f.fr_podtv_kogo = 0 
order by u.id';

$candidates = $dbh->query($sql)->fetchAll(PDO::FETCH_CLASS, \App\Models\RowUser::class);

$sql = 'select count(*) from friends where fr_kto = ' . $myrow->id . ' and fr_podtv_kto = 1 and fr_podtv_kogo = 1';
if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, $page, 20, 2);

    $sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility, u.real_status, u.visibility, u.hot_time, u.regdate, 
      u.vip_time, u.now_status, u.hot_text, u.vipsmile,u.admin, u.moderator, u.city, u.login, u.fname, u.gender, u.about, 
      ut.last_view
        from friends f
        join users u on u.id = f.fr_kto 
        join users_timestamps ut on ut.id = u.id 
      where f.fr_kogo = ' . $myrow->id . ' and f.fr_podtv_kto = 1 and f.fr_podtv_kogo = 1 
    order by ut.last_view desc limit ' . $pagination->offset() . ', 20';

    $sth = $dbh->query($sql);

    if (!$sth->rowCount()) {
        exit('Внутренняя ошибка сайта.Пожалуйста повторите попытку');
    }

    $friends = $sth->fetchAll(PDO::FETCH_CLASS, \App\Models\RowUser::class);
    $paging = $pagination->build();

    if (!empty($paging)) {
        $paging_page = render('partials/paginate', ['paginate' => $paging, 'link' => '/my/friends']);
    }
}
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Мои друзья<?php $this->stop(); ?>
<?php $this->start('description'); ?>Друзья<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<?php if(!empty($candidates)) : ?>
    <h1>Заявки в друзья <span class="red">(+<?php echo count($candidates); ?>)</span></h1>
    <?php foreach($candidates as $candidate) : ?>
        <?php anketa_usr_row($myrow, $candidate) ?>
        <div class="friends-control">
            <div class="btn-group">
                <button type="button" class="action btn btn-success">Принять в друзья</button>
                <button type="button" class="action btn btn-danger">Отклонить заявку</button>
            </div>
        </div>
        <hr>
    <?php endforeach; ?>
<?php endif; ?>

<h1>
    <a href="/id<?php echo $myrow->id; ?>">Моя сраница</a>
    &bull;
    Мои друзья <?php if ($count > 0) : ?>(<?php echo $count; ?>)<?php endif; ?>
</h1>
<?php if(!empty($friends)) : ?>
    <?php echo $paging_page; ?>
    <table width="100%">
        <?php foreach($friends as $friend) : ?>
            <tr>
                <td><?php anketa_usr_row($myrow, $friend) ?></td>
                <td><a class="del-comm">Удалить</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php echo $paging_page; ?>
<?php else : ?>
    <p>У вас пока нет друзей</p>
<?php endif; ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>
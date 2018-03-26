<?php
/**
* @var \System\View $this
*/

use App\Exceptions\NotFoundException;

$dbh = db();
$myrow = auth();

[$user_id, $page] = request()->getValuesInteger(['user_id', 'page']);
$paging_page = '';

$sql = 'select u.id, u.login from users u where u.id = ' . $user_id . ' limit 1';

if (!$user = $dbh->query($sql)->fetch()) {
    throw new NotFoundException('Пользователя не существует');
}

$sql = 'select count(*) from friends where fr_kto = ' . $user_id . ' and fr_podtv_kto = 1 and fr_podtv_kogo = 1';
if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, $page, 20, 2);

    $sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility, u.real_status, u.visibility, u.hot_time, u.regdate, 
      u.vip_time, u.now_status, u.hot_text, u.vipsmile,u.admin, u.moderator, u.city, u.login, u.fname, u.gender, u.about, 
      ut.last_view
        from friends f
        join users u on u.id = f.fr_kto 
        join users_timestamps ut on ut.id = u.id 
      where f.fr_kogo = ' . $user_id . ' and f.fr_podtv_kto = 1 and f.fr_podtv_kogo = 1 
    order by ut.last_view desc limit ' . $pagination->offset() . ', 20';

    $sth = $dbh->query($sql);

    if (!$sth->rowCount()) {
        exit('Внутренняя ошибка сайта.Пожалуйста повторите попытку');
    }

    $friends = $sth->fetchAll(PDO::FETCH_CLASS, \App\Models\RowUser::class);
    $paging = $pagination->build();

    if (!empty($paging)) {
        $paging_page = render('partials/paginate', ['paginate' => $paging, 'link' => '/friends/user/' . $user_id]);
    }

    if($user_id !== $myrow->id) {
        $sql = 'select count(*)
          from friends f
          join friends mf on mf.fr_kogo = f.fr_kogo
          where f.fr_kto = '. $myrow->id . ' and f.fr_podtv_kto = 1 and f.fr_podtv_kogo = 1 and mf.fr_kto = '.$user_id.' 
        and mf.fr_podtv_kto = 1 and mf.fr_podtv_kogo = 1';

        $count_mutual = $dbh->query($sql)->fetchColumn();
    }
}
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Друзья <?php echo html($user['login']); ?><?php $this->stop(); ?>
<?php $this->start('description'); ?>Друзья <?php echo html($user['login']); ?><?php $this->stop(); ?>

<?php $this->start('style'); ?>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<h1>
    <a href="/id<?php echo $user['id']; ?>"><?php echo html($user['login']) ?></a>
    &bull;
    Друзья
    <?php if ($count > 0) : ?>
        (<?php echo $count; ?>)
        <?php if(!empty($count_mutual)) : ?>
        &bull;
            <a href="/friends/user/<?php echo $user_id; ?>/mutual">Общие друзья (<?php echo $count_mutual; ?>)</a>
        <?php endif; ?>
    <?php endif; ?>
</h1>
<?php if(!empty($friends)) : ?>
    <?php echo $paging_page; ?>
    <?php foreach($friends as $friend) : ?>
        <?php anketa_usr_row($myrow, $friend) ?>
    <?php endforeach; ?>
    <?php echo $paging_page; ?>
<?php else : ?>
<p>У пользователя пока нет друзей</p>
<?php endif; ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>


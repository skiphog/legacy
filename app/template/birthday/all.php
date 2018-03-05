<?php
/**
 * @var \Swing\System\View $this
 */

$dbh = db();
$myrow = auth();

$hash = ((int)date('m') * 31) + (int)date('j');

$sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility, u.real_status, u.visibility, u.hot_time, u.regdate,
  u.vip_time, u.now_status, u.hot_text, u.vipsmile,u.admin, u.moderator, u.city, u.login, u.fname, u.gender, u.about,
  ut.last_view 
  from users u join users_timestamps ut on ut.id = u.id 
  where u.birthday_hash = ' . $hash . ' and `status` = 1  
order by ut.last_view desc';

$users = $dbh->query($sql)->fetchAll(PDO::FETCH_CLASS, \Swing\Models\RowUser::class);
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Именинники<?php $this->stop(); ?>
<?php $this->start('description'); ?>Именинники на сайте<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<h1>Именинники сегодня (<?php echo date('d-m-Y'); ?>) - <?php echo $count = count($users) ?> <?php echo plural($count,'анкета|анкеты|анкет'); ?></h1>
<?php if (!empty($users)) : ?>
    <?php foreach ($users as $user) : ?>
        <?php anketa_usr_row($myrow, $user); ?>
    <?php endforeach; ?>
<?php else : ?>
    Сегодня нет именинников
<?php endif; ?>
<?php $this->stop(); ?>
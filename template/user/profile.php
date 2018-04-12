<?php
/**
 * @var \System\View $this
 */

$dbh = db();
$myrow = auth();

$sql = 'select u.id, u.admin, u.moderator,u.assistant, u.login, u.fname, u.birthday,
  u.gender, u.purposes, u.email, u.country, u.city, u.marstat,
  u.child, u.height, u.weight, u.hcolor, u.ecolor, u.etnicity,
  u.religion, u.smoke, u.drink, u.education, u.job, u.hobby,
  u.about, u.about_search, u.sgender, u.setnicity, u.sreligion,
  u.agef, u.aget, u.heightf, u.heightt, u.weightf, u.weightt,
  u.pic1, u.regdate, u.status, u.rate, u.real_status, u.visibility,
  u.photo_visibility, u.now_status, u.moder_text, u.moder_zametka,
  u.vipsmile, u.vip_time,u.id_vip, u.por_login,HEX(uuid) as uuid,
  ut.last_view, ut.ip
from users u
  join users_timestamps ut on ut.id = u.id
where u.id = ' . $myrow->id;

$user = $dbh->query($sql)->fetchObject(\App\Models\Profile::class);

?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Моя страница<?php $this->stop(); ?>
<?php $this->start('description'); ?>Моя страница<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<?php var_dump($user) ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>
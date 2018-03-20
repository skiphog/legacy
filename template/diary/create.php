<?php
/**
 * @var \System\View $this
 */

use App\Exceptions\ForbiddenException;

$dbh = db();
$myrow = auth();

if (!$myrow->isActive()) {
    throw new ForbiddenException('Данный раздел не доступен т.к. ваша анкета не прошла модерацию.');
}

?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Добавить запись в дневник<?php $this->stop(); ?>
<?php $this->start('description'); ?>Добавить запись в дневник<?php $this->stop(); ?>

<?php $this->start('style'); ?>
    <link rel="stylesheet" href="/js/wysibb/wbbtheme.css">
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<?php require __PATH__ . '/template/diary/form.php'; ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
    <script src="/js/wysibb/jquery.wysibb.js"></script>
    <script src="/js/wysibb/script.js"></script>
<?php $this->stop(); ?>
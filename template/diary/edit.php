<?php
/**
* @var \System\View $this
*/

use App\Exceptions\NotFoundException;
use App\Exceptions\ForbiddenException;

$dbh = db();
$myrow = auth();

$diary_id = request()->getInteger('id');

$sql = 'select user_di, title_di, text_di, data_di, v_count
  from diary
  where id_di = ' . $diary_id . ' 
and deleted = 0 limit 1';

if (!$diary = $dbh->query($sql)->fetch()) {
    throw new NotFoundException('Дневник не найден');
}

if((int)$diary['user_di'] !== $myrow->id && !$myrow->isModerator()) {
    throw new ForbiddenException('Доступ запрещен');
}
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Редактированик дневника: <?php echo html($diary['title_di']); ?><?php $this->stop(); ?>
<?php $this->start('description'); ?>Редактирование дневника<?php $this->stop(); ?>

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
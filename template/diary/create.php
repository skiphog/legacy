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
    <h2>Добавить дневник</h2>
    <form method="post">
        <label for="title">Заголовок:</label>
        <br>
        <input id="title" style="width: 90%; border: 1px solid #888; padding: 5px;" type="text" name="title" required>
        <br>
        <label for="editor">Текст:</label>
        <br>
        <textarea style="width: 90%; height: 150px; border: 1px solid #888;padding: 5px;" name="text" id="editor"></textarea>
        <br>
        <button class="btn btn-primary" type="submit">Опубликовать</button>
    </form>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
    <script src="/js/wysibb/jquery.wysibb.js"></script>
    <script src="/js/wysibb/script.js"></script>
<?php $this->stop(); ?>
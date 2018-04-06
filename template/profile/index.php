<?php
/**
* @var \System\View $this
*/

$dbh = db();
$myrow = auth();



?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Моя страница<?php $this->stop(); ?>
<?php $this->start('description'); ?>Моя страница<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>
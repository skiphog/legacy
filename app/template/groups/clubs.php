<?php
/**
* @var \Swing\System\View $this
*/

$dbh = db();
$myrow = user();


?>

<?php $this->extend('groups/layout'); ?>

<?php $this->start('content-group'); ?>
<?php var_dump($_SERVER) ?>
<?php $this->stop(); ?>

<?php
/**
 * @var \System\View $this
 */

$uri = request()->uri();

$links = [
    'my/groups/activity' => 'Мои новости',
    'groups/activity'    => 'Лента групп',
    'groups'             => 'Все группы',
    'groups/new'         => 'Новые группы',
    'groups/clubs'       => 'Региональные клубы'
];

?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('style'); ?>
<style>
.group-nav{
    padding: 10px;
    background-color: #e1eaff;
    margin-bottom: 10px;
    border-radius: 4px;
    box-shadow: rgba(0, 0, 0, .09) 0 2px 0;
}
.group-nav::after{
    content: '';
    display: table;
    clear: both;
}
.group-list {
    margin: 0;
    padding: 0;
    list-style-type: none;
    font-size: 0;
}
.group-list li {
    display: inline-block;
    vertical-align: top;
    font-size: 16px;
    font-weight: 700;
    padding: 5px;
}
.group-list li:not(:last-child)::after{
    content: ' •';
}
.group-list li.active a{
    color: #222;
}
.group-create{
    float: right;
}
.group-content{padding:5px;}
</style>
<?php echo $this->renderBlock('style-group') ?>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<div class="group-nav">

    <div class="group-create">
        <a class="btn btn-primary" href="/groups/create">Создать группу</a>
    </div>

    <ul class="group-list">
        <?php foreach ($links as $key => $value) : ?>
            <li <?php if($key === $uri) : ?>class="active"<?php endif; ?>><a href="/<?php echo $key; ?>"><?php echo html($value); ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="group-content">
<?php echo $this->renderBlock('content-group') ?>
</div>
<?php $this->stop(); ?>
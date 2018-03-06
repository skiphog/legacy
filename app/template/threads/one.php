<?php
/**
* @var \Swing\System\View $this
*/

$dbh = db();
$myrow = auth();



?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?><?php $this->stop(); ?>
<?php $this->start('description'); ?><?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .t-breadcrumbs,.t-title,.t-body,.t-comment,.t-vote{margin-bottom: 10px;}
    .t-breadcrumbs{padding: 5px;font-style: italic;}
    .t-head .border-box{background-color: #EBEBF3;}
    .t-avatar,.t-user-info,.t-titles{display: table-cell;vertical-align: top;padding-right: 5px;}
    span.ww{display: block;margin: 10px 0 10px 40px;}
    .t-titles>h1{margin: 18px 0 0 20px;font-size: 26px;}
    .t-avatar>img,.t-uc-info>img{padding: 0;vertical-align: middle;}
    .t-date{color: #A99898;font-size: 0.9em;}
    .ug-inner > label,.t-vote-head{display: block;margin: 5px;font-weight: bold;}
    #polls{display: inline-block;min-width: 400px;}
    .t-vote-head{font-size: 1.3em;}
    .t-vote-footer{margin: 5px;}
    .vote-relative{position: relative;z-index: 999;}
    .vote-relative>div{position: absolute;z-index: -999;min-width: 1%;border-radius: 0 50px 50px 0;box-shadow: 0 1px 5px #CCC;}
    .vote-relative>span{font-weight: bold;margin-left: 5px;font-size: 0.9em;}
    .vote-box{display: block;margin-bottom: 5px;background-color: #fff !important;border-radius: 4px;font-style: italic;}
    .vote-box>input{vertical-align: top;}
    label.vote-box{cursor: pointer;}
    .vote-box>input:checked+span{font-weight: bold;color: #1b09e9;}
    .mask>form{opacity: 0.3;}
    .mask{background: url('/img/load_green.gif') center no-repeat;}
    .del-comm,.red-comm{color: #CCC;float: right;cursor: pointer;padding: 2px;}
    .del-comm:hover{color:#D21B1B}
    .red-comm:hover{color:#1b09e9;}
    .div-del{background-color: #EBEBF3;}
    .div-del > span {cursor: pointer;color: green}
    .del-mask{background: url('/img/loader_button.gif');}
    .rform{min-height: 200px;}
    .jopa{border: 2px solid #FF0;}
    .rew-buttons{margin: 15px;}
    #addpicture{min-height:100px;}
    #addpicture.upload{background: url('/img/load_green.gif') no-repeat 0 45%;}
    button[data-like^="-"]{color: red;}
    button[data-like^="0"]{color: #555;}
    .green{color: green}
    .like-btn .btn,.btn.green {line-height: 1;}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>


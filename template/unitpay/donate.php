<?php
/**
* @var \System\View $this
*/
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Поддержать сайт<?php $this->stop(); ?>
<?php $this->start('description'); ?>Поддержать сайт<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .donate p{font-size:14px;line-height:20px;}
    .donate__iframe {width:450px;height:303px;margin-bottom:20px;}
    .donate__thanks {font-size:16px;font-weight:700;}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<h1>Поддержать сайт</h1>

<article class="donate">
    <p>Эта страничка для тех, кому небезразлична судьба сайта, кто хочет поддержать проект и помочь в его развитии.</p>

    <p>Более 15 лет этот <strong>сайт живет благодаря помощи пользователей</strong>, и альтруистов- модераторов, программистов, помощников.<br>
        И мы очень благодарны вам за помощь! Без нее было бы не справиться точно!</p>

    <p>На данный момент у нас много планов и целей по развитию проекта. <b>И вы можете помочь нам ускорить этот процесс</b>, так как на реализацию нужны финансы!</p>

    <p>Абсолютно все деньги пойдут на поддержку сайта и написание новых функций, чтобы знакомиться стало еще удобнее.</p>

    <iframe class="donate__iframe" src="https://money.yandex.ru/quickpay/shop-widget?writer=seller&targets=%D0%94%D0%BE%D0%B1%D1%80%D0%BE%D0%B2%D0%BE%D0%BB%D1%8C%D0%BD%D0%B0%D1%8F%20%D0%BF%D0%BE%D0%BC%D0%BE%D1%89%D1%8C%20%D1%81%D0%B0%D0%B9%D1%82%D1%83%20swing-kiska&targets-hint=&default-sum=&button-text=14&payment-type-choice=on&mobile-payment-type-choice=on&comment=on&hint=%D0%A2%D1%83%D1%82%20%D0%B2%D1%8B%20%D0%BC%D0%BE%D0%B6%D0%B5%D1%82%D0%B5%20%20%D0%BD%D0%B0%D0%BF%D0%B8%D1%81%D0%B0%D1%82%D1%8C%20%D0%B2%D0%B0%D1%88%D0%B8%20%D0%BF%D0%BE%D0%B6%D0%B5%D0%BB%D0%B0%D0%BD%D0%B8%D1%8F%20%D0%B4%D0%BB%D1%8F%20%D1%81%D0%B0%D0%B9%D1%82%D0%B0.&successURL=https%3A%2F%2Fswing-kiska.ru&quickpay=shop&account=41001278738519" width="450" height="303" frameborder="0" allowtransparency="true" scrolling="no"></iframe>

    <p class="donate__thanks">Спасибо за ваш вклад в развитие сайта!</p>

</article>
<?php $this->stop(); ?>
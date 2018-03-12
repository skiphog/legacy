<?php
/**
 * @var string $site_title
 * @var string $site_description
 * @var \System\View $this
 */
?>
<?php if ($this->ensure('title')) : ?><?php echo html($site_title); ?><?php $this->stop(); endif; ?>
<?php if ($this->ensure('description')) : ?><?php echo html($site_description); ?><?php $this->stop(); endif; ?>
<meta charset="utf-8">
<title><?php echo $this->renderBlock('title'); ?></title>
<meta name="description" content="<?php echo $this->renderBlock('description'); ?>">
<link rel="stylesheet" href="/css/main-1475653197766.css">
<link rel="stylesheet" href="/css/new.css">
<?php echo $this->renderBlock('style') ?>
<link rel="apple-touch-icon" sizes="57x57" href="/img/favicon/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/img/favicon/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/img/favicon/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/img/favicon/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/img/favicon/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/img/favicon/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/img/favicon/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/img/favicon/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon-180x180.png">
<link rel="apple-touch-icon" href="/img/favicon/apple-touch-icon.png">
<link rel="apple-touch-icon" href="/img/favicon/apple-touch-icon-precomposed.png">
<link rel="icon" type="image/png" href="/img/favicon/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="/img/favicon/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="/img/favicon/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="/img/favicon/favicon-16x16.png" sizes="16x16">
<link rel="manifest" href="/img/favicon/manifest.json">
<link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="/img/favicon/mstile-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta name="msapplication-config" content="/img/favicon/ieconfig.xml">
<script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
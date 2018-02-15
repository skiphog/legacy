<?php
$global = $this->dbh->query('select site_title, site_keywords, site_description, site_main,background,theme from site where site_id = 1')->fetch();

$site_title = $this->myrow->isGuest() ? $global['site_title'] : 'Добро пожаловать!';
$site_description = $global['site_description'];
$sback = $global['background'];

if (isset($_GET['storyid'])) {

    $global = $this->dbh->query('select meta_title as site_title,keywords as site_keywords,description as site_description,topic,title,time,hometext,bodytext from stories where sid = ' . (int)$_GET['storyid'])->fetch();
    $site_title = !empty($global['site_title']) ? $global['site_title'] : $site_title;
    $site_description = !empty($global['site_description']) ? $global['site_description'] : $site_description;
}
?>
<meta charset="utf-8">
<title><?php echo $site_title; ?></title>
<meta name="description" content="<?php echo html($site_description); ?>">
<link rel="stylesheet" href="/css/main-1475653197766.css">
<link rel="stylesheet" href="/css/new.css">
<link rel="apple-touch-icon" sizes="57x57" href="/img/favicon/apple-touch-icon-57x57.png"/>
<link rel="apple-touch-icon" sizes="60x60" href="/img/favicon/apple-touch-icon-60x60.png"/>
<link rel="apple-touch-icon" sizes="72x72" href="/img/favicon/apple-touch-icon-72x72.png"/>
<link rel="apple-touch-icon" sizes="76x76" href="/img/favicon/apple-touch-icon-76x76.png"/>
<link rel="apple-touch-icon" sizes="114x114" href="/img/favicon/apple-touch-icon-114x114.png"/>
<link rel="apple-touch-icon" sizes="120x120" href="/img/favicon/apple-touch-icon-120x120.png"/>
<link rel="apple-touch-icon" sizes="144x144" href="/img/favicon/apple-touch-icon-144x144.png"/>
<link rel="apple-touch-icon" sizes="152x152" href="/img/favicon/apple-touch-icon-152x152.png"/>
<link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-touch-icon-180x180.png"/>
<link rel="apple-touch-icon" href="/img/favicon/apple-touch-icon.png"/>
<link rel="apple-touch-icon" href="/img/favicon/apple-touch-icon-precomposed.png"/>
<link rel="icon" type="image/png" href="/img/favicon/favicon-32x32.png" sizes="32x32"/>
<link rel="icon" type="image/png" href="/img/favicon/android-chrome-192x192.png" sizes="192x192"/>
<link rel="icon" type="image/png" href="/img/favicon/favicon-96x96.png" sizes="96x96"/>
<link rel="icon" type="image/png" href="/img/favicon/favicon-16x16.png" sizes="16x16"/>
<link rel="manifest" href="/img/favicon/manifest.json"/>
<link rel="mask-icon" href="/img/favicon/safari-pinned-tab.svg" color="#5bbad5"/>
<meta name="msapplication-TileColor" content="#da532c"/>
<meta name="msapplication-TileImage" content="/img/favicon/mstile-144x144.png"/>
<meta name="theme-color" content="#ffffff"/>
<meta name="msapplication-config" content="/img/favicon/ieconfig.xml"/>
<script type="text/javascript" src="/js/jquery-1.12.4.min.js"></script>
<?php
/**
 * @var \App\System\View $this
 */

use App\Exceptions\NotFoundException;

$dbh = db();
$myrow = auth();

$category_id = (int)request()->get('id');

$sql = 'select topicname from stories_cat where topicid = ' . $category_id;

if (!$category_name = $dbh->query($sql)->fetchColumn()) {
    throw new NotFoundException('Категории не существует');
}

$sql = 'select count(*) from stories where topic = ' . $category_id;

if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, (int)request()->get('page'), 10, 2);

    $sql = 'select sid, title, hometext, `time`
      from stories
      where topic = ' . $category_id . '
    order by sid desc limit ' . $pagination->offset() . ', 10;';

    $sth = $dbh->query($sql);

    if (!$sth->rowCount()) {
        exit('Внутренняя ошибка сайта.Пожалуйста повторите попытку');
    }

    $articles = $sth->fetchAll();
    $paging = $pagination->build();

    $paging_page = 'Одна страница';
    $write = '';
    if (!empty($paging)) {
        ob_start(); ?>
        <ul class="pagination">
            <?php foreach ($paging as $link => $name) { ?>
                <li class="<?php echo $name; ?>">
                    <a href="/story_<?php echo $category_id; ?>_<?php echo $link; ?>"><?php echo $link; ?></a>
                </li>
            <?php } ?>
        </ul>
        <?php $paging_page = ob_get_clean();
    }
} ?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?><?php echo html($category_name); ?><?php $this->stop(); ?>
<?php $this->start('description'); ?>Статьи о свинге, новости и события. <?php echo html($category_name); ?><?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .ya-page_js_yes .ya-site-form_inited_no{display:none}
    .yandex-search{height:35px;margin-bottom:10px}
    .articles{color:#444}
    .article-post{padding:10px;margin-bottom:40px;border: 1px solid #ccc;border-radius:4px}
    .article-header{padding:5px;background-color:#ebebf3;margin-bottom:5px;border-radius:4px}
    .article-title{font-size:28px;margin-bottom:20px}
    .article-title a{color:inherit}
    .article-time{font-style:italic;color:#777;margin:0}
    .article-more{display:block;margin:20px 0}
    .article-more::before{display:table;content:'';clear:both}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<h1><?php echo html($category_name); ?></h1>
<div class="yandex-search">
    <div class="ya-site-form ya-site-form_inited_no"
            onclick="return {'bg': '#cce5ff', 'target': '_self', 'language': 'ru', 'suggest': true, 'tld': 'ru', 'site_suggest': true, 'action': 'https://swing-kiska.ru/search', 'webopt': false, 'fontsize': 14, 'arrow': false, 'fg': '#000000', 'searchid': '1930276', 'logo': 'rb', 'websearch': false, 'type': 2}">
        <form action="//yandex.ru/sitesearch" method="get" target="_self">
            <input type="hidden" name="searchid" value="1930276"/>
            <input type="hidden" name="l10n" value="ru"/>
            <input type="hidden" name="reqenc" value=""/>
            <input type="text" name="text" value=""/>
            <input type="submit" value="Найти"/>
        </form>
    </div>
</div>
<?php if (!empty($articles)) : ?>
    <?php echo $paging_page; ?>
    <div class="articles">
        <?php foreach ($articles as $article) : ?>
            <article class="article-post">
                <header class="article-header">
                    <h2 class="article-title">
                        <a href="/viewstory_<?php echo $article['sid']; ?>"><?php echo html($article['title']); ?></a>
                    </h2>
                    <p class="article-time"><?php echo (new \App\Components\SwingDate($article['time']))->format('d-m-Y'); ?></p>
                </header>
                <div><?php echo $article['hometext']; ?></div>
                <p class="article-more"><a href="/viewstory_<?php echo $article['sid']; ?>">Подробнее ...</a></p>
                <?php if ($myrow->isAdmin()) : ?>
                    <p><a href="/edit_story_<?php echo $article['sid']; ?>" style="color:green">Редактировать</a></p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
    <?php echo $paging_page; ?>
<?php else : ?>
    <p>Записей нет</p>
<?php endif; ?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script>
  (function (w, d, c) {
    var s = d.createElement('script'), h = d.getElementsByTagName('script')[0], e = d.documentElement;
    (' ' + e.className + ' ').indexOf(' ya-page_js_yes ') === -1 && (e.className += ' ya-page_js_yes');
    s.type = 'text/javascript';
    s.async = true;
    s.charset = 'utf-8';
    s.src = (d.location.protocol === 'https:' ? 'https:' : 'http:') + '//site.yandex.net/v2.0/js/all.js';
    h.parentNode.insertBefore(s, h);
    (w[c] || (w[c] = [])).push(function () {Ya.Site.Form.init();});
  })(window, document, 'yandex_site_callbacks');
</script>
<?php $this->stop(); ?>
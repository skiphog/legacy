<?php
/**
 * @var \Swing\System\View $this
 */

use Swing\Exceptions\NotFoundException;

$dbh = db();
$myrow = user();

$category_id = (int)request()->get('id');

$sql = 'select topicname from stories_cat where topicid = ' . $category_id;

if (!$category_name = $dbh->query($sql)->fetchColumn()) {
    throw new NotFoundException('Категории не существует');
}

$sql = 'select count(*) from stories where topic = ' . $category_id;

if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, (int)request()->get('page'), 10, 2);

    $sql = 'select sid, meta_title, description, title, hometext, bodytext, title, `time`
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
<?php $this->start('description'); ?>Статьи о свинге, новости и события.<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .ya-page_js_yes .ya-site-form_inited_no { display: none; }
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>

<?php var_dump($articles); ?>

<?php $this->stop(); ?>

<?php $this->start('script'); ?>
    <script>
      (function(w,d,c){var s=d.createElement('script'),h=d.getElementsByTagName('script')[0],e=d.documentElement;(' '+e.className+' ').indexOf(' ya-page_js_yes ')===-1&&(e.className+=' ya-page_js_yes');s.type='text/javascript';s.async=true;s.charset='utf-8';s.src=(d.location.protocol==='https:'?'https:':'http:')+'//site.yandex.net/v2.0/js/all.js';h.parentNode.insertBefore(s,h);(w[c]||(w[c]=[])).push(function(){Ya.Site.Form.init()})})(window,document,'yandex_site_callbacks');
    </script>
<?php $this->stop(); ?>
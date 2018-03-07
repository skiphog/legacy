<?php
/**
 * @var \System\View $this
 */

use App\Exceptions\NotFoundException;

$dbh = db();
$myrow = auth();

$article_id = (int)request()->get('id');

$sql = 'select s.sid, s.topic, s.meta_title, s.description, s.title, s.hometext, s.bodytext, s.`time`, sc.topicname
from stories s
  left join stories_cat sc on sc.topicid = s.topic
where s.sid = ' . $article_id . ' limit 1';

if (!$article = $dbh->query($sql)->fetch()) {
    throw new NotFoundException('Статья не найдена');
}
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?><?php echo html($article['meta_title']); ?><?php $this->stop(); ?>
<?php $this->start('description'); ?><?php echo $article['description']; ?><?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .article-post{color:#444;padding:10px;margin-bottom:40px;}
    .article-header{padding:5px;background-color:#ebebf3;margin-bottom:5px;border-radius:4px;box-shadow:rgba(0, 0, 0, .09) 0 2px 0;}
    .article-title{font-size:28px;margin-bottom:20px;font-family:inherit}
    .article-title a{color:inherit}
    .article-time{font-style:italic;color:#777;margin:0}
    .article-form textarea{
        font-size: 14px;
        font-family: inherit;
        width: 100%;
        height: 80px;
        display: block;
        background: #fafafa;
        border: none;
        border-radius: 2px;
        box-shadow: inset 0 1px 1px 0 rgba(0, 0, 0, .1);
        outline: 0;
        padding: 8px;
        margin-bottom: 10px;
    }

</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<div class="breadcrumbs">
    <a href="/story_<?php echo $article['topic']; ?>_1"><?php echo html($article['topicname']); ?></a>
</div>

<article class="article-post">
    <header class="article-header">
       <h1 class="article-title"><?php echo html($article['title']); ?></h1>
        <p class="article-time"><?php echo (new \App\Components\SwingDate($article['time']))->format('d-m-Y'); ?></p>
    </header>

    <main class="article-main">
        <?php echo $article['hometext']; ?>
        <?php echo $article['bodytext']; ?>
    </main>
</article>
<?php if($myrow->isUser()) : ?>
    <form class="article-form" method="post">
        <textarea id="article-message" name="message" placeholder="Ваш комментарий"></textarea>
        <button type="submit">Отправить</button>
    </form>
<?php endif; ?>

<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php $this->stop(); ?>
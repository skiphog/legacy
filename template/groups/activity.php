<?php
/**
 * @var \System\View $this
 */

use App\Components\SwingDate;

$dbh = db();
$myrow = auth();

$sql = 'select t.ugt_title, t.ugt_date, t.ugthread_id, t.ugroup_id, g.ug_title 
  from ugthread t
  join ugroup g on g.ugroup_id = t.ugroup_id 
where g.ug_dlt = 0 and g.ug_hidden = 0 and t.ugt_hidden = 0 and t.ugt_dlt = 0
order by t.ugthread_id desc limit 5';

$sth = $dbh->query($sql);
$threads = [];

while ($row = $sth->fetch()) {
    $row['date'] = new SwingDate($row['ugt_date']);
    $threads[] = $row;
}

$hidden = ' and t.ugt_hidden = 0 and g.ug_hidden = 0';

if($myrow->isModerator()) {
    $hidden = '';
}


$sql = /** @lang text */ 'select c.ugthread_id,c.ugc_date,c.ugc_text, 
  u.login as commlogin,u.pic1 as commpic1,u.gender as commgender,
  u.photo_visibility as commphoto_visibility,u.id as commid,
  t.ugt_title,t.ugroup_id,t.ug_comments_count as cnt,t.ugt_hidden,g.ug_title,g.ug_hidden 
  from ugcomments c 
    join users u on u.id = c.user_id 
    join ugthread t on t.ugthread_id = c.ugthread_id 
    join ugroup g on g.ugroup_id = t.ugroup_id  
  where ugc_dlt = 0 and t.ugt_dlt = 0 and g.ug_dlt = 0 ' . $hidden . '
order by ugc_date desc limit 0,30';

$comments = $dbh->query($sql)->fetchAll();

?>
<?php $this->extend('groups/layout'); ?>

<?php $this->start('title'); ?>Лента активности<?php $this->stop(); ?>
<?php $this->start('description'); ?>Лента активности<?php $this->stop(); ?>

<?php $this->start('style-group'); ?>
<?php echo render('groups/partials/style'); ?>
<?php $this->stop(); ?>

<?php $this->start('content-group'); ?>
<table class="group-new-thread" border="0" cellspacing="20" width="100%">
    <tr>
        <td valign="top">
            <h1>Новые темы</h1>

            <?php foreach ($threads as $thread) : ?>
                <?php echo $thread['date']->format('d-m-Y'); ?> |
                <small><?php echo $thread['date']->getHumansPerson(); ?></small>
                <br>
                <a style="font-weight:700;font-size:16px;" href="/viewugthread_<?php echo $thread['ugthread_id']; ?>"><?php echo html($thread['ugt_title']); ?></a>
                <?php if ($thread['date']->modify('+1 day') > new DateTimeImmutable()) : ?>
                    <img src="/img/newred.gif" border="0">
                <?php endif; ?>
                <br>
                группа: <span style="font-weight: bold"><?php echo html($thread['ug_title']); ?></span>
                <br>
                <br>
            <?php endforeach; ?>
        </td>

        <td valign="top" width="40%">
            <h1>Региональные клубы <a href="/groups/clubs">(Все)</a></h1>
            Хотите найти свинг клуб в своем городе? Тогда Вам <a href="/groups/clubs">сюда</a>!!!
            <h2>Интересные группы</h2>
            <p>
                <a href="/groups/286">
                    <img alt="Доктор" src="https://swing-kiska.ru/avatars/user_thumb/86397%2F06291301a2619834544fd94415e3a4f3.jpg" style="float:left; margin:5px;width: 60px"></a>
                <a href="/groups/286">Доктор свинг</a>
                <br>
                группа для анонимных консультаций
                <br>
                задавайте вопросы и отвечайте оставаясь анонимным.
            </p>

            <p>
                <a href="/groups/38">
                    <img alt="Эротика" src="https://swing-kiska.ru/avatars/group_thumb/38avatar.jpg" style="float:left; margin:5px; width:60px;"></a>
                <a href="/groups/38">У меня есть что показать! Эротика</a>
                <br>
                Если у вас есть что показать?
            </p>

            <p>
                <a href="/groups/97">
                    <img alt="Играем" src="https://swing-kiska.ru/avatars/group_thumb/97avatar.jpg" style="float:left; margin:5px; width:60px;"></a>
                <a href="/groups/97">Играем на сайте!</a>
                <br>
                Группа с играми и конкурсами
            </p>
        </td>
    </tr>
</table>
<div id="response">
    <section id="result">
        <?php echo render('groups/partials/comments', compact('comments')) ?>
    </section>
</div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script>
  $(document).ready(function () {
    var $scrollTop = $('<div/>').
      addClass('scrollTop').
      attr({style: 'z-index:9999; position:fixed;color:#FFF;text-decoration:none'}).
      html('<b>НАВЕРХ<b>').
      appendTo('body');
    var num = 20, id = 4, scc = false;
    $scrollTop.click(function (e) {
      e.preventDefault();
      $('html:not(:animated),body:not(:animated)').animate({scrollTop: 0}, 500);
    });
    $(window).scroll(function () {
      if ($(window).scrollTop() + $(window).height() >= $('#result').height() + 440 && !scc) {
        $.ajax({
          url: 'get_com_news.php',
          type: 'GET',
          data: {'num': num, 'id': id, 'sleep': 0},
          beforeSend: function () {
            scc = true;
            $('.scrollTop').addClass('spinner scrop').text('');
          },
          success: function (response) {
            if (response == 0 || response == 1 || response == 2) {
              response = '<br /><b>Нет данных для вывода</b><br />';
            } else {
              scc = false;
              num += 20;
            }
            $('#result').append(response);
            $('.scrollTop').removeClass('spinner scrop').html('<b>НАВЕРХ<b>');
          }
        });
      }
      ($(window).scrollTop() > 440) ? $scrollTop.stop().animate({bottom: 10}, 100) : $scrollTop.stop().
        animate({bottom: -30}, 100);
    });
  });
</script>
<?php echo render('/partials/scroll-top'); ?>
<?php $this->stop(); ?>
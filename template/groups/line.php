<?php
/**
 * @var \System\View $this
 */

use App\Components\SwingDate;
use App\Components\Parse\All as AllParse;

$dbh = db();
$myrow = auth();

$parse = new AllParse();

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
<style>
    #response {
        margin-top: 20px;
    }

    #loadsa {
        text-align: center;
        margin-bottom: -40px
    }

    .fixed {
        position: fixed;
        top: 2px;
    }

    .spinner {
        background-color: #2E8CE3;
        -webkit-animation: rotateplane 1.2s infinite ease-in-out;
        animation: rotateplane 1.2s infinite ease-in-out;
    }

    @-webkit-keyframes rotateplane {
        0% {
            -webkit-transform: perspective(120px)
        }
        50% {
            -webkit-transform: perspective(120px) rotateY(180deg)
        }
        100% {
            -webkit-transform: perspective(120px) rotateY(180deg) rotateX(180deg)
        }
    }

    @keyframes rotateplane {
        0% {
            transform: perspective(120px) rotateX(0deg) rotateY(0deg);
            -webkit-transform: perspective(120px) rotateX(0deg) rotateY(0deg);
        }
        50% {
            transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
            -webkit-transform: perspective(120px) rotateX(-180.1deg) rotateY(0deg);
        }
        100% {
            transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
            -webkit-transform: perspective(120px) rotateX(-180deg) rotateY(-179.9deg);
        }
    }

    section a, .scrollTop {
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s
    }

    section a {
        text-decoration: none;
        color: #11638C;
    }

    section a:hover {
        color: #00F;
    }

    section label {
        font-size: 1.5em;
        font-weight: bold;
    }

    .scrollTop {
        cursor: pointer;
        opacity: 0.5;
        background: #2E8CE3;
        width: 60px;
        text-align: center;
        bottom: -30px;
        height: 15px;
        padding: 5px;
        left: 50%;
    }

    .scrollTop:hover, .scrop {
        opacity: 1;
        background: #24b662;
    }

    .borderkis, .friend-foto {
        border: 1px solid #CCC;
        box-shadow: 0 1px 5px #CCC;
        -moz-box-shadow: 0 1px 5px #CCC;
        -webkit-border-shadow: 0 1px 5px #CCC;
    }

    .padkis, div.padkis table {
        padding: 20px;
        margin-bottom: 15px;
    }

    div.padkissmall {
        padding: 10px;
        margin-bottom: 5px;
    }

    div.padkissmall > a {
        font-weight: bold;
        text-transform: uppercase;
    }

    div.padkissmall span {
        display: block;
        margin-left: 15px;
    }

    div.padkis table {
        background-color: rgb(244, 244, 244);
        border: 1px solid rgb(209, 209, 209);
        box-shadow: 0 1px 5px rgb(209, 209, 209);
        -moz-box-shadow: 0 1px 5px rgb(209, 209, 209);
        -webkit-border-shadow: 0 1px 5px rgb(209, 209, 209);
    }

    .red-border {
        border: 1px solid #FF8B8B !important;
        box-shadow: 0 1px 5px #FF8B8B !important;
        -moz-box-shadow: 0 1px 5px #FF8B8B !important;
        -webkit-border-shadow: 0 1px 5px #FF8B8B !important;
    }

    .group-new-thread {
        background-color: #F4F4F4;
        margin-bottom: 20px;
        border-radius: 4px;
        box-shadow: rgba(0, 0, 0, .09) 0 2px 0;
    }
</style>
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
            <h1>Региональные клубы <a href="/ugrouplist_clubs">(Все)</a></h1>
            Хотите найти свинг клуб в своем городе? Тогда Вам <a href="/ugrouplist_clubs">сюда</a>!!!
            <h2>Интересные группы</h2>
            <p>
                <a href="/viewugroup_286">
                    <img alt="Доктор" src="https://swing-kiska.ru/avatars/user_thumb/86397%2F06291301a2619834544fd94415e3a4f3.jpg" style="float:left; margin:5px;width: 60px"></a>
                <a href="/viewugroup_286">Доктор свинг</a>
                <br>
                группа для анонимных консультаций
                <br>
                задавайте вопросы и отвечайте оставаясь анонимным.
            </p>

            <p>
                <a href="/viewugroup_38">
                    <img alt="Эротика" src="https://swing-kiska.ru/avatars/group_thumb/38avatar.jpg" style="float:left; margin:5px; width:60px;"></a>
                <a href="/viewugroup_38">У меня есть что показать! Эротика</a>
                <br>
                Если у вас есть что показать?
            </p>

            <p>
                <a href="/viewugroup_97">
                    <img alt="Играем" src="https://swing-kiska.ru/avatars/group_thumb/97avatar.jpg" style="float:left; margin:5px; width:60px;"></a>
                <a href="/viewugroup_97">Играем на сайте!</a>
                <br>
                Группа с играми и конкурсами
            </p>
        </td>
    </tr>
</table>
<div id="response">
    <section id="result">
        <?php foreach($comments as $row) : ?>
            <div class="borderkis padkis <?php if((int)$row['ugt_hidden'] === 1 || (int)$row['ug_hidden'] === 1) : ?>red-border<?php endif; ?>">
                <h2 class="noblock">Тема: <a href="/viewugthread_<?= $row['ugthread_id']; ?>"><?= $row['ugt_title']; ?></a></h2>
                <span class="count"> сообщений:<?= $row['cnt']; ?></span>
                <br/><br/>
                <table>
                    <tr>
                        <td valign="top" width="100">
                            <div class="avatar" style="background-image: url(<?php echo avatar($myrow, $row['commpic1'], $row['commphoto_visibility']); ?>);"></div>
                            <a href="/id<?= $row['commid']; ?>" class="hover-tip" target="_blank">
                                <img src="/img/info_small_<?= $row['commgender']; ?>.png" width="15" height="14"/>
                            </a>
                            <a href="/id<?= $row['commid']; ?>" target="_blank"><b><?= $row['commlogin']; ?></b></a>
                            <br/>
                            <?= $row['ugc_date']; ?>
                        </td>
                        <td>
                            <?= nl2br(nickart(imgart(smile($parse->parse($row['ugc_text']))))); ?>
                            <br/>
                        </td>
                    </tr>
                </table>
                <p>Групппа: <a href="viewugroup_<?= $row['ugroup_id']; ?>" target="_blank"><?= $row['ug_title']; ?></a>
                </p>
                <br/>
            </div>
        <?php endforeach; ?>
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
<?php $this->stop(); ?>

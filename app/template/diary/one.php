<?php
/**
 * @var \Swing\System\View $this
 */

use Swing\Exceptions\NotFoundException;

$dbh = db();
$myrow = user();

$diary_id = abs((int)request()->get('id'));

$sql = 'select d.title_di,d.text_di, d.data_di,d.likes,d.dislikes,d.v_count,
  u.id id_user,u.login,u.gender,u.pic1,u.photo_visibility
  from diary d
  join users u on u.id = d.user_di
  where d.id_di = ' . $diary_id . ' 
and d.deleted = 0 limit 1';

$sth = $dbh->query($sql);

if (!$sth->rowCount()) {
    throw new NotFoundException('Дневник не найден');
}

$diary = $sth->fetch();

$sql = 'select u.id,u.login,u.fname,u.gender,u.city,u.pic1,u.photo_visibility,
  dc.comment_id,dc.comment_text, dc.comment_date,dc.likes,dcl.vote
  from diary_comments dc 
  join users u on u.id = dc.user_id 
  left join diary_com_likes dcl on dcl.id_post = dc.comment_id and dcl.id_user = ' . ($myrow->id ?:0) . '
  where dc.diary_id = ' . $diary_id . ' and dc.deleted = 0
order by dc.comment_id desc limit 0,31';

$comments = $dbh->query($sql)->fetchAll();

$com_status = 0;

if (array_key_exists(30, $comments)) {
    $com_status = 1;
    unset($comments[30]);
}

$access = false;
$vote = false;
$parse_class = \Swing\Components\Parse\NoSession::class;

if ($myrow->isUser()) {
    $access = $myrow->isModerator() || $myrow->id === (int)$diary['id_user'];
    $parse_class = \Swing\Components\Parse\All::class;

    $sql = 'select vote from diary_likes where id_post = ' . $diary_id . ' and id_user = ' . $myrow->id;

    $sth = $dbh->query($sql);

    if ($sth->rowCount()) {
        $vote = $sth->fetchColumn();
    }

    if ($myrow->id !== (int)$diary['id_user']) {
        $dbh->exec('update diary set v_count = v_count + 1 where id_di = ' . $diary_id);
    }
}

$hide_mobile = $myrow->isMobile() ? 'style="display:none;"': '';
/** @var \Swing\Components\Parse\GParse $parse */
$parse = new $parse_class();

?>
<?php $this->extend('layout/layout') ?>

<?php $this->start('title') ?><?= html($diary['title_di']); ?><?php $this->stop() ?>
<?php $this->start('description') ?><?= html($diary['title_di']); ?><?php $this->stop() ?>

<?php $this->start('style') ?>
<style>
    .d-breadcrumbs,.d-body,.t-comment{margin-bottom:10px}
    .d-breadcrumbs{font-size:1.2em;font-style:italic}
    .d-user-info > div,.t-avatar,.t-user-info{display:table-cell;padding-right:5px;vertical-align:top}
    .d-form button,.d-form{margin-top:10px}
    hr{color:#ccc;border-style:solid;margin:10px 0}
    span.ww{display:block;margin:10px 0 10px 40px}
    .msg-avatar > img,.t-avatar > img,.t-uc-info > img{border-radius:4px;vertical-align:middle;padding:0}
    .t-date{color:#A99898;font-size:.9em}
    #load-mes{text-align:center}
    .del-comm,.red-comm{color:#CCC;float:right;cursor:pointer;padding:2px}
    .del-comm:hover{color:#D21B1B}
    .date{display:block;color:#777}
    .del-mask{background:url(/img/loader_button.gif)}
    .div-del{background-color:#EBEBF3}
    .div-del > span{cursor:pointer;color:green}
    .btn-likes,.btn-likes > span,.btn-likes:before{vertical-align:middle}
    .btn-likes{background:none;border:none;box-shadow:none;display:inline-block;height:22px;outline:0;text-decoration:none;white-space:nowrap;word-wrap:normal;line-height:normal;cursor:pointer;padding:0 10px}
    .btn-likes::before{margin-right:5px;content:'';display:inline-block;background-size:auto;width:26px;height:22px}
    .btn-likes.like::before{background:no-repeat url(/img/likes.png) 0 0}
    .btn-likes.dislike::before{background:no-repeat url(/img/likes.png) -26px 0}
    .golos{font-weight:700;color: #0000ff}
    button[data-like^="-"]{color:#f00}
    button[data-like^="0"]{color:#555}
    .green{color:green}
    .like-btn .btn,.btn.green{line-height:1}
    span.u-bold{font-weight:700;color:#747474}
    .n-0{color:#f00!important}
</style>
<?php $this->stop() ?>

<?php $this->start('content') ?>
<div id="mass-content">
    <div class="d-breadcrumbs">
        <a href="/diary_1">Все дневники</a> &bull;
        <a href="/udiary_<?= $diary['id_user']; ?>_page_1">дневник <?= $diary['login']; ?></a>
    </div>

    <div class="d-user-info">
        <div class="msg-avatar">
            <img class="border-box" src="<?php echo avatar($myrow, $diary['pic1'],
                $diary['photo_visibility']); ?>" width="70" height="70" alt="avatar" draggable="false">
        </div>
        <div>
            <a class="hover-tip" href="/id<?= $diary['id_user']; ?>">
                <img src="/img/info_small_<?= $diary['gender']; ?>.png" width="15" height="14">
                <strong><?= $diary['login']; ?></strong>
            </a>
            <div class="date"><?= date('d-m-Y H:i', strtotime($diary['data_di'])); ?> |
                <strong><?= (new \Swing\Components\SwingDate($diary['data_di']))->getHumans(); ?></strong></div>
            <div>Посещений: <strong><?= $diary['v_count']; ?></strong></div>
            <div>
                <a href="/udiary_<?= $diary['id_user']; ?>_page_1">Весь дневник <?= $diary['login']; ?></a>
                <?php if ($access) { ?>
                    &bull; <a href="/reddiary_<?= $diary_id; ?>">Редактировать</a> &bull;
                    <?php if ($myrow->isModerator()) { ?>
                        <a href="/hidediary_<?= $diary_id; ?>" onclick="return window.confirm('Вы уверены что хотите скрыть этот дневник?')">Скрыть</a> &bull;
                    <?php } ?>
                    <a class="del-comm" href="/deldiary_<?= $diary_id; ?>" onclick="return window.confirm('Вы уверены что хотите удалить этот дневник?')">Удалить</a>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="d-body">
        <h1><?= html($diary['title_di']); ?></h1>
        <div>
            <?= nl2br(imgart(smile($parse->parse($diary['text_di'])))); ?>
        </div>
    </div>
    <hr>

    <div id="likes" class="likes" data-post="<?= $diary_id; ?>" data-action="setDiaryLike">
        <button class="btn-likes like <?php $vote !== 'likes' ?: print 'golos'; ?>" title="Нравится" value="likes"><?= $diary['likes']; ?></button>
        <button class="btn-likes dislike <?php $vote !== 'dislikes' ?: print 'golos'; ?>" title="Не нравится" value="dislikes"><?= $diary['dislikes']; ?></button>
    </div>


    <?php if ($myrow->isUser() && !$myrow->isInActive()) { ?>
        <?php if (empty($_SESSION['mobile'])) { ?>
    <link rel="stylesheet" href="/js/wysibb/wbbtheme.css" type="text/css"/>
        <script src="/js/wysibb/jquery.wysibb.js"></script>
        <script src="/js/wysibb/comments-script.js"></script>
    <?php }else { ?>
        <script type="text/javascript" src="/js/collapse.js"></script>
    <?php } ?>

        <form id="d-send" action="" method="post" class="d-form" name="addugcomment">
            <label for="editor">Добавить комментарий</label>
            <textarea style="width: 100%; height: 100px;" id="editor" name="content"></textarea>
            <input type="hidden" name="uscomid" value="<?= $diary_id; ?>">
            <button type="submit" class="btn btn-primary" name="butcommuserdiary">Сказать</button>
        </form>
    <?php } ?>
    <hr>

    <div id="response">
        <?php
        if (!empty($comments)) {
            foreach ($comments as $value) { ?>
                <div class="t-comment border-box">
                    <?php if ($access || (int)$value['id'] === $myrow->id) { ?>
                        <span class="del-comm" title="Удалить комментарий" data-value="<?php echo $value['comment_id']; ?>">Удалить</span>
                    <?php } ?>
                    <div class="t-avatar">
                        <img class="border-box" src="<?php echo avatar($myrow, $value['pic1'],
                            $value['photo_visibility']); ?>" alt="avatar" width="70" height="70">
                    </div>
                    <div class="t-user-info">
                        <a class="hover-tip" href="/id<?php echo $value['id']; ?>" target="_blank">
                            <img src="/img/info_small_<?= $value['gender']; ?>.png" width="15" height="14"> <?php echo $value['login']; ?>&nbsp;
                        </a>
                        <?php if ($myrow->isUser()) { ?>
                            <a href="#" onclick="return insertName('||<?= $value['login'] ?>||, ');"><img src="/img/ssss.jpg" width="20" height="19" alt="kat" title="Вставить ник"/></a>
                            &nbsp;
                            <a href="#" <?php echo $hide_mobile; ?> onclick="return insertQuote(this,'||<?= $value['login'] ?>||');"><img src="/img/quotes.jpg" width="22" height="19" alt="quote" title="Цитировать"/></a>
                        <?php } ?>
                        <br/>
                        <?php echo $value['fname']; ?>
                        <br/>
                        <span class="city-<?php echo getCityCompare($myrow->city,
                            $value['city']); ?>"><?php echo $value['city']; ?></span>
                        <br/>
                        <span class="t-date">
				        <?php echo date('d-m-Y H:i',
                            strtotime($value['comment_date'])); ?> | <strong><?php echo (new \Swing\Components\SwingDate($value['comment_date']))->getHumans(); ?></strong>
			        </span>
                    </div>
                    <span class="ww"><?php echo nl2br(nickart(smile($parse->parse($value['comment_text'])))); ?></span>
                    <?php if ($myrow->isUser()) { ?>
                        <?php if (empty($value['vote']) && $myrow->id !== (int)$value['id']) { ?>
                            <div class="like-btn btn-group" style="margin-top: 5px;" data-comment="<?php echo $value['comment_id']; ?>">
                                <button class="btn btn-success like-com" data-laction="+1" value="likes">+</button>
                                <button class="btn green btn-default like-center" disabled data-like="<?= $value['likes'] ?>"><?= $value['likes'] ?></button>
                                <button class="btn btn-danger like-com" data-laction="-1" value="dislikes">-</button>
                            </div>
                        <?php } else { ?>
                            <button class="btn green btn-default like-center" disabled data-like="<?= $value['likes'] ?>"><?= $value['likes'] ?></button>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php }
        }
        ?>
    </div>
</div>
<div id="load-mes" style="display: none"><img src="/img/load_green.gif" width="100" height="100" alt="loading"/></div>
<?php $this->stop() ?>

<?php $this->start('script') ?>
<script>
  var resblock = $('#response'), loading = $('#load-mes'), com_status = <?= $com_status; ?>;
  var $com = {
    send: true,
    more: true,
    cnt: 30,
    post: <?= $diary_id; ?>,
    getComments: function (action, params) {
      if (this.send === true) {
        this.send = false;
        loading.show();
        var data = {cntr: 'Comments', action: action, post: this.post};
        if (typeof params === 'object') {
          for (var cur in params) {
            if (params.hasOwnProperty(cur)) {
              data[cur] = params[cur];
            }
          }
        }
        $.getJSON('/ajax/', data, function (json) {
          loading.hide();
          $com.setComment(json);
        });
      }

    },
    setComment: function (json) {
      if (!json['status']) {
        this.more = false;
      }
      this.cnt += 30;
      resblock.append(json['html']);
      $com.send = true;
    }
  };
  if (com_status) {
    $(window).scroll(function () {
      var win = $(this);
      if (win.scrollTop() + win.height() >= $('#mass-content').height() && $com.send && $com.more) {
        $com.getComments('getDiaryComments', {num: $com.cnt});
      }
    });
  }


  <?php if($myrow->isUser()) {?>

  resblock.on('click', '.del-comm', function () {
    var comm = $(this);
    var $append = $('<div/>').addClass('t-comment border-box div-del del-mask').html('&nbsp;');
    comm.parent('div').after($append).hide();

    $.post('/ajax/', {cntr: 'ManageCom', action: 'setDiaryCommens', id: comm.data('value'), param: 1}, function () {
      $append.removeClass('del-mask').
        html('Комментарий удален. <span data-comm="' + comm.data('value') + '">Восстановить?</span>');
    });

  });
  resblock.on('click', '.div-del > span', function () {
    var comm = $(this);
    var $div = comm.parent('div').prev().show().addClass('del-mask');
    comm.parent('div').remove();
    $.post('/ajax/', {cntr: 'ManageCom', action: 'setDiaryCommens', id: comm.data('comm'), param: 0}, function () {
      $div.removeClass('del-mask');
    });
  });
  resblock.on('click', '.like-com', function () {
    var c = $(this), p = c.parent('div'), ce = p.find('.like-center'), cnt = ce.text() * 1 + c.data('laction') * 1;
    p.find('.like-com').remove();
    ce.attr('data-like', cnt).text(cnt);
    $.post('/ajax/', {
      cntr: 'Likes',
      action: 'setDiaryComLike',
      post: p.data('comment'),
      vote: c.val()
    });
  });


  <?php if(!$vote && $myrow->id !== (int)$diary['id_user']) { ?>

  $('#likes').one('click', '.btn-likes', function () {
    var like = $(this);
    var param = like.parent('div');
    like.text(like.text() * 1 + 1).addClass('golos');
    $.post('/ajax/', {
      cntr: 'Likes',
      action: param.data('action'),
      post: param.data('post'),
      vote: like.val()
    });
  });
  <?php }?>
  <?php }?>
</script>
<?php $this->stop() ?>
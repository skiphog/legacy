<?php
/**
* @var \System\View $this
*/

use App\Exceptions\NotFoundException;

$dbh = db();
$myrow = auth();

$sql = 'select g.ugroup_id as g_id, g.user_id as g_master_id, g.ug_title as g_title,g.ug_hidden as g_hidden,gu.ugu_permission,
  gt.ugthread_id as t_id, gt.user_id as t_master_id, gt.polls_exist,gt.ugt_title as t_title, gt.ugt_descr as t_text,
  gt.ug_comments_count as t_count,gt.ugt_date as t_date,
  u.login, u.fname, u.gender, u.city, u.pic1, u.moderator, u.admin, u.photo_visibility
  from ugroup g
    join ugthread gt on gt.ugthread_id = ' . request()->getInteger('id') . ' and gt.ugt_dlt = 0
    join users u on u.id = gt.user_id
    left join ugusers gu on gu.ugroup_id = gt.ugroup_id and gu.user_id = ' . $myrow->id . '
  where g.ugroup_id = gt.ugroup_id and g.ug_dlt = 0 
limit 1';

if (!$thread = $dbh->query($sql)->fetch()) {
    throw new NotFoundException('Темы не существует или удалена');
}

/**
 * Если группа открытая или принят в группу или мастер группы или модератор то пускаем, инчае доступ закрыт
 */
if(!(!$thread['g_hidden'] || $myrow->isModerator() || !empty($thread['ugu_permission']) || (int)$thread['g_master_id'] === $myrow->id)) {
    header('/groups/' . $thread['g_id']);
    die;
}

/**
 * Доступ к управлению темы или комментов
 */
$access = $myrow->isModerator() || (int)$thread['g_master_id'] === $myrow->id || (int)$thread['t_master_id'] === $myrow->id;
$parse = new \App\Components\Parse\All();

$polls = [];

if($thread['polls_exist']) {
    $sql = 'select id p_id,title p_title from polls where ugthread_id = '.(int)$thread['t_id'];
    if($polls = $dbh->query($sql)->fetch()) {
        $sql = 'select id v_id,poll_variant p_var,votes from polls_variants where poll_id = '.(int)$polls['p_id'];
        $polls['variants'] = $dbh->query($sql)->fetchAll();
        $sql = 'select pv.poll_variant
				from polls_users pu
				join polls_variants pv on pv.id = pu.poll_variant_id
			where pu.polls_id = '.(int)$polls['p_id'].' and pu.user_id = '.$myrow->id;
        $sth = $dbh->query($sql);
        $polls['my_vote'] = $sth->rowCount() ? $sth->fetchColumn() : null;

        if(!empty($polls['my_vote'])) {
            $tmp = array_column($polls['variants'],'votes');
            $min = min($tmp);
            $max = max($tmp);
            $polls['sum'] = array_sum($tmp);
            foreach($polls['variants'] as $key => $value) {
                $polls['variants'][$key]['proz'] = round($value['votes'] / $polls['sum'] * 100);
                $polls['variants'][$key]['color'] = '#B8D9F5';
                if($value['votes'] == $max) {
                    $polls['variants'][$key]['color'] = '#54E21D';
                }elseif($value['votes'] == $min) {
                    $polls['variants'][$key]['color'] = '#FFA0A0';
                }
            }
        }
    }
}

$page = request()->getInteger('page');
$paging_page = '';
$sql = 'select count(*) from ugcomments where ugthread_id = ' . (int)$thread['t_id'] . ' and ugc_dlt = 0';
if ($count = $dbh->query($sql)->fetchColumn()) {
    $pagination = new Kilte\Pagination\Pagination($count, $page, 20, 2);

    $sql = 'select ct.ugcomments_id as c_id,ct.ugc_text as c_text, ct.ugc_date as c_date, ct.likes,
        u.id,u.login,u.fname,u.gender,u.city,u.pic1,u.moderator,u.admin,u.photo_visibility,ucl.vote
        from ugcomments ct
        join users u on u.id = ct.user_id
        left join ug_com_likes ucl on ucl.id_post = ct.ugcomments_id and ucl.id_user = '.$myrow->id.'
        where ct.ugthread_id = '.(int)$thread['t_id'].' and ct.ugc_dlt = 0
    order by ct.ugcomments_id asc limit '.$pagination->offset().',20';
    $comments = $dbh->query($sql)->fetchAll();
    $paging = $pagination->build();

    if(!empty($paging)) {
        ob_start(); ?>
        <ul class="pagination">
            <?php foreach($paging as $link => $name) { ?>
                <li class="<?php echo $name; ?>">
                    <a href="/viewugthread_<?php echo $thread['t_id']; ?>_<?php echo $link; ?>"><?php echo $link; ?></a>
                </li>
            <?php } ?>
        </ul>
        <?php $paging_page = ob_get_clean();
    }
}?>

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
    .mask>form{opacity: 0.3}
    .mask{background: url(/img/load_green.gif) center no-repeat}
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
    #addpicture.upload{background: url(/img/load_green.gif) no-repeat 0 45%;}
    button[data-like^="-"]{color: red;}
    button[data-like^="0"]{color: #555;}
    .green{color: green}
    .like-btn .btn,.btn.green {line-height: 1}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<div class="t-head">
    <div class="t-breadcrumbs">
        <?php if(!empty($thread['ugu_permission'])) {?>
            <a href="/my/news">Мои новости</a> &bull; <a href="/my/groups">Мои группы</a> &bull;
        <?php }else{?>
            <a href="/groups/activity">Лента активности</a> &bull; <a href="/groups">Все группы</a> &bull;
        <?php }?>
        Группа: <a href="/groups/<?php echo $thread['g_id'] ;?>"><?php echo html($thread['g_title']) ;?></a>
    </div>

    <div class="t-title border-box">
        <div class="t-avatar">
            <img class="border-box" src="<?php echo avatar($myrow, $thread['pic1'], $thread['photo_visibility']);?>" alt="avatar" width="50" height="50">
        </div>
        <div class="t-user-info">
            <a class="hover-tip" href="/id<?php echo $thread['t_master_id'];?>" target="_blank">
                <img src="/img/info_small_<?php echo $thread['gender'];?>.png" width="15" height="14" alt="gender"> <?php echo html($thread['login']);?>&nbsp;
            </a>
            <br />
            <?php echo html($thread['fname']);?>
            <br />
            <span class="city-<?php echo getCityCompare($myrow->city, $thread['city']);?>"><?php echo $thread['city'];?></span>
        </div>
        <div class="t-titles">
            <h1><?php echo $thread['t_title'] ;?></h1>
        </div>
        <div class="t-date">
            <?php echo date('d-m-Y H:i',strtotime($thread['t_date']));?> | <strong><?php echo (new \App\Components\SwingDate($thread['t_date']))->getHumans(); ?></strong>
        </div>
        <div>
            <?php if($access) {?>
                <a class="edit" href="/redugthread_<?php echo $thread['t_id']; ?>">Редактировать</a> &bull;
                <a class="edit" href="/redugthread_<?php echo $thread['t_id']; ?>#poll-target">Голосование</a> &bull;
                <a class="delete" href="/delugthread_<?php echo $thread['t_id']; ?>" onclick="return window.confirm('Вы действительно хотите удалить тему?')">Удалить</a>
            <?php }?>
        </div>
    </div>

    <?php if (!empty($thread['t_text'])) {?>
        <div class="t-body border-box">
            <?php echo nl2br(imgart(smile($parse->parse($thread['t_text'])))); ?>
        </div>
    <?php	}?>

    <?php if(!empty($polls)) {?>

        <div class="t-vote border-box">
            <div class="t-vote-head">
                <?php echo $polls['p_title']; ?>
            </div>
            <div id="polls">
                <?php if(!empty($polls['my_vote'])) {?>
                    <?php foreach($polls['variants'] as $value) {?>
                    <div class="vote-box border-box">
                        <?php echo $value['p_var'] ;?>
                        <div class="vote-relative">
                            <div style="width: <?php echo $value['proz'] ;?>%;background-color: <?php echo $value['color'] ;?>">&nbsp;</div>
                            <span><?php echo $value['votes'] ;?> (<?php echo $value['proz'] ;?> %)</span>
                        </div>
                    </div>
                <?php }?>
                    <div class="t-vote-footer">
                        Ваш голос: <strong><?php echo $polls['my_vote'] ;?></strong>
                        <br />
                        Всего проголосовало: <strong><?php echo $polls['sum'] ;?></strong>
                    </div>
                <?php }else{?>
                    <form action="" id="form-polls" method="post">
                        <?php foreach($polls['variants'] as $value) {?>
                            <label class="vote-box border-box">
                                <input type="radio" name="vote" value="<?php echo $value['v_id'] ;?>"> <span><?php echo $value['p_var'] ;?></span>
                            </label>
                        <?php }?>
                        <div class="t-vote-footer" style="display: none">
                            <button type="submit" class="btn btn-success">Голосовать</button>
                        </div>
                    </form>

                <?php }?>
            </div>
        </div>
    <?php }?>

</div>
<div><?php echo $paging_page; ?></div>

<div class="t-comments">
    <?php if(!empty($comments)) {
        $hide_mobile = $myrow->isMobile() ? 'style="display:none;"': '';
        foreach($comments as $value) {?>
            <div id="com<?php echo $value['c_id'] ;?>" class="t-comment border-box">
                <?php if($access || (int)$value['id'] === $myrow->id) {?>
                    <span class="del-comm" title="Удалить комментарий" data-value="<?php echo $value['c_id'] ;?>">Удалить</span>
                <?php }?>
                <?php if((int)$value['id'] === $myrow->id && (strtotime($value['c_date']) + 3600 > $_SERVER['REQUEST_TIME'])) {?>
                    <span class="red-comm" <?php echo $hide_mobile ;?> title="Редактирование комментария" data-value="<?php echo $value['c_id'] ;?>">Редактировать</span>
                <?php }?>
                <div class="t-avatar">
                    <img class="border-box" src="<?php echo avatar($myrow,$value['pic1'],$value['photo_visibility']);?>" alt="avatar" width="70" height="70">
                </div>
                <div class="t-user-info">
                    <a class="hover-tip" href="/id<?php echo $value['id'];?>" target="_blank">
                        <img src="/img/info_small_<?php echo $value['gender'];?>.png" width="15" height="14" alt="gender" /> <?php echo html($value['login']);?>&nbsp;
                    </a>
                    <a href="#" onclick="return insertName('||<?=html($value['login'])?>||, ');"><img src="/img/ssss.jpg" width="20" height="19" alt="kat" title="Вставить ник" /></a>
                    &nbsp;
                    <a href="#" <?php echo $hide_mobile ;?> onclick="return insertQuote(this,'||<?=html($value['login'])?>||');"><img src="/img/quotes.jpg" width="22" height="19" alt="quote" title="Цитировать"></a>
                    <br />
                    <?php echo html($value['fname']);?>
                    <br />
                    <span class="city-<?php echo getCityCompare($myrow->city,$value['city']);?>"><?php echo html($value['city']);?></span>
                    <br />
                    <span class="t-date">
				<?php echo date('d-m-Y H:i', strtotime($value['c_date']));?> | <strong><?php echo (new \App\Components\SwingDate($value['c_date']))->getHumans(); ?></strong>
			</span>
                </div>
                <span class="ww"><?php echo nl2br(nickart(imgart(smile($parse->parse($value['c_text'])))));?></span>
                <?php if(empty($value['vote']) && $myrow->id !== (int)$value['id']) {?>
                    <div class="like-btn btn-group" style="margin-top: 5px;" data-comment="<?php echo $value['c_id'] ;?>">
                        <button class="btn btn-success like-com" data-laction="+1" value="likes">+</button>
                        <button class="btn green btn-default like-center" disabled data-like="<?= $value['likes']?>"><?= $value['likes']?></button>
                        <button class="btn btn-danger like-com" data-laction="-1" value="dislikes">-</button>
                    </div>
                <?php }else{?>
                    <button class="btn green btn-default like-center" disabled data-like="<?= $value['likes']?>"><?= $value['likes']?></button>
                <?php }?>
            </div>
        <?php	}?>
    <?php }else {?>
        <p>Пока нет комментариев...</p>
    <?php }?>
</div>
<div><?php echo $paging_page; ?></div>
<?php if(!$myrow->isMobile()) {?>
    <link rel="stylesheet" href="/js/wysibb/wbbtheme.css" type="text/css" />
    <script src="/js/wysibb/jquery.wysibb.js"></script>
    <script src="/js/wysibb/script-test-2-1455821700506.js"></script>
    <form class="ug-form border-box" name="addugcomment" method="post">
        <div class="ug-inner">
            <label for="editor">Добавить комментарий:</label>
            <input type="hidden" name="ugroup_id" value="<?php echo $thread['g_id']?>">
            <input type="hidden" name="ugthread_id" value="<?php echo $thread['t_id']?>">
            <textarea id="editor" name="ugc_text"></textarea>
            <br>
            <input class="btn btn-success" type="submit" name="addugcomments" value="Отправить">
        </div>
    </form>
<?php }else{?>
    <script type="text/javascript" src="/js/mobile/ajaxupload-1456216758186.js"></script>
    <script type="text/javascript" src="/js/mobile/group_script-1456217863629.js"></script>
    <form class="ug-form" name="addugcomment" method="post">
        <div class="ug-inner">
            <h3>Добавить комментарий:</h3>
            <input type="hidden" name="ugroup_id" value="<?php echo $thread['g_id']?>" />
            <input type="hidden" name="ugthread_id" value="<?php echo $thread['t_id']?>" />
            <textarea id="editor" name="ugc_text"></textarea>
            <br>
            <input type="submit" name="addugcomments" value="Отправить" />
            <div style="margin-top: 20px">
                <a href="javascript:collapsElement('addpicture')" title="" rel="nofollow">Добавить картинки</a>
                <div id="addpicture" style="display: none;">
                    <div style="vertical-align:top;">
                        <div>
                            Загруженные картинки (<span style="color: #f00;font-weight: bold">нажмите на картинку</span>):
                        </div>
                        <ul id="files"></ul>
                        <br />
                        <strong>Чтобы загрузить фото:</strong>
                        <br />
                        1. Нажми "Загрузить".
                        <br />
                        2. Установите курсор мышки на место в тексте, где должна быть картинка.
                        <br />
                        3. Нажми на картинку.
                        <br />
                        4. В окне появится такое {{название файла.jpg}} Рядом можно написать текст
                        <br />
                        5. Нажмите отправка
                        <br />
                    </div>

                    <div style="margin-top: 20px">
                        <div id="uploadButton" class="button">Загрузить картинку</div>
                    </div>

                </div>
            </div>
        </div>
    </form>
<?php }?>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<?php if(!empty($polls) && empty($polls['my_vote'])) : ?>
<script>
      var sendPoll = true;
      $('#form-polls').on('change','input',function(){
        $('.t-vote-footer').show();
      });
      $('#polls').on('submit','#form-polls',function(e){
        e.preventDefault();
        if(sendPoll) {
          var form = $(this);
          var div = form.parent('div');
          var request = 'cntr=Polls&action=setPoll&' + form.serialize();
          $.ajax({url:'/ajax/',type:'post',dataType:'json',data: request,
            beforeSend: function(){
              sendPoll = false;
              div.addClass('mask');
            },
            success: function(json){
              div.removeClass('mask');
              if(json['status'] == 1) {
                setPolls(div,json);
              }
            }
          });
        }
      });
      function setPolls(o,json) {
        var data = json['html']['variants'];
        var str = '';
        for(var i in data) {
          str += '<div class="vote-box border-box">' +
            data[i]["poll_variant"] +
            '<div class="vote-relative">' +
            '<div class="vote-in" data-proz="' + data[i]["proz"] + '" style="background-color: ' + data[i]["color"] + '">&nbsp;</div>' +
            '<span>' + data[i]["votes"] + '('+ data[i]["proz"] + '%)</span>' +
            '</div>' +
            '</div>';
        }
        str += '<div class="t-vote-footer">' +
          'Ваш голос: <strong>' + json['html']['my_vote'] + '</strong><br />' +
          'Всего проголосовало: <strong>' + json['html']['sum'] + '</strong>' +
          '</div>';
        o.html(str);
        animatePoll();
      }
      function animatePoll() {
        $('.vote-in').each(function(i,o) {
          var div = $(o);
          var proz = div.data('proz');
          div.animate({width:proz + '%'},1000);
        });
      }
    </script>
<?php endif; ?>
<script>
  $('.t-comments').on("click", ".like-com",function () {
    var c=$(this),p=c.parent('div'),ce=p.find('.like-center'),cnt=ce.text()*1+c.data('laction')*1;
    p.find('.like-com').remove();
    ce.attr('data-like',cnt).text(cnt);
    $.post('/ajax/',{
      cntr: 'Likes',
      action: 'setUgComLike',
      post : p.data('comment'),
      vote : c.val()
    });
  });
</script>
<?php $this->stop(); ?>
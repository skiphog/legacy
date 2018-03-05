<?php
/**
 * @var \Swing\System\View $this
 */

use Swing\Exceptions\NotFoundException;

$dbh = db();
$myrow = auth();

[$id_album, $id_photo] = request()->getValuesInteger(['album_id', 'photo_id']);

$sql = 'select a.aid,a.pass,a.albvisibility,a.description,a.title,
  p.aid,u.id,u.login,u.gender,u.pic1,u.fname,u.city,u.photo_visibility,u.vip_time
  from photo_albums a
  join users u on a.us_id_album = u.id
  join photo_pictures p on p.pid = ' . $id_photo . ' and p.aid = ' . $id_album . '
where a.aid = p.aid';

if (!$albom = $dbh->query($sql)->fetch()) {
    throw new NotFoundException('Нет такого альбома');
}

$albom['avatar'] = avatar($myrow, $albom['pic1'], $albom['photo_visibility']);

$albom['background'] = '#FFF';

if (strtotime($albom['vip_time']) - $_SERVER['REQUEST_TIME'] >= 0) {
    $sql = 'select v.background
      from `option` o
      left join vip_background v on v.id = o.vip_background
    where o.u_id = ' . (int)$albom['id'];

    $sth = $dbh->query($sql);

    $albom['background'] = $sth->rowCount() ? 'url(' . $sth->fetchColumn() . ')' : 'url(/img/vip.jpg)';
}

// Доступ к самому альбому
$fails = [];

if (!empty($albom['pass']) && !$myrow->isModerator()) {
    $fails[] = 'Альбом закрыт паролем';
} else {
    if ((int)$albom['albvisibility'] === 3 && !$myrow->isReal()) {
        $fails[] = 'Альбом доступен для владельцев статуса реальности';
    }
    if ((int)$albom['albvisibility'] !== 0 && $myrow->isInActive()) {
        $fails[] = 'Ваша анкета не прошла модерацию';
    }
    if ($myrow->rate < 50 && (int)$albom['albvisibility'] !== 0) {
        $fails[] = 'Для просмотра требуется 50 баллов';
    }
}

//Получаем все альбомы юзера
$sql = 'select api.aid,api.pid,
  a.title,a.pass,a.albvisibility,a.count,
  p.filepath,p.filename
  from 
    (select pp.aid,min(pp.pid) pid 
      from photo_albums pa
      join photo_pictures pp on pp.aid = pa.aid
      where pa.us_id_album = ' . $albom['id'] . '
    group by pp.aid) api
  join photo_pictures p on p.pid = api.pid
  join photo_albums a on a.aid = api.aid
order by api.aid desc';

$sth = $dbh->query($sql);

//Если ничего нет, то это какая-то хуйня. Такого не может быть
if (!$sth->rowCount()) {
    throw new InvalidArgumentException('Альбомы не найдены');
}

$alboms = [];

while ($row = $sth->fetch()) {
    if ((!empty($row['pass']) && !$myrow->isModerator()) ||
        ((int)$row['albvisibility'] === 3 && !$myrow->isReal()) ||
        (($myrow->status === 2 || $myrow->rate < 50) && (int)$row['albvisibility'] !== 0)
    ) {
        $row['photo_path'] = $row['filepath'] . md5($row['filename']) . '.jpg';
        $row['class'] = 'grayscale';
    } else {
        $row['photo_path'] = $row['filepath'] . 'thumb_' . rawurlencode($row['filename']);
        $row['class'] = '';
    }
    $row['photo_path'] = is_file(__PATH__ . '/' . $row['photo_path']) ? $row['photo_path'] : 'images/album/thumb_no_al_ava.jpg';

    $alboms[] = $row;
}

//Получаем все фотки в альбоме
$sql = 'select pid,aid,filepath,filename 
  from photo_pictures 
  where aid = ' . $id_album . ' 
order by pid';

$sth = $dbh->query($sql);

//Если нет фото то ошибку
if (!$sth->rowCount()) {
    throw new NotFoundException('В альбоме нет ни одной фотографии');
}

$photos = [];

$while_photo = empty($fails) ?
    function ($a, $b) {
        $file = $a . 'thumb_' . rawurlencode($b);

        return [is_file(__PATH__ . '/' . $file) ? $file : 'images/album/thumb_no_al_ava.jpg', ''];
    } :
    function ($a, $b) {
        $file = $a . md5($b) . '.jpg';

        return [is_file(__PATH__ . '/' . $file) ? $file : 'images/album/thumb_no_al_ava.jpg', 'grayscale'];
    };

while ($row = $sth->fetch()) {
    $row['img_path'] = $while_photo($row['filepath'], $row['filename']);
    $photos[] = $row;
}

$access_album = [
    0 => 'Доступен для всех',
    2 => 'Доступен для пользователей с анкетами',
    3 => 'Доступен для пользователей со статусом реальности',
];
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Альбомы<?php $this->stop(); ?>
<?php $this->start('description'); ?>Альбомы<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    #albums,#response-photo{width:850px}
    #albums,#load-photo{margin:auto}
    #response-photo{position:relative;min-height:430px;text-align:center;margin:0 auto 10px;padding-top:20px;border-radius:4px}
    .albums-info{display:table-cell;vertical-align:top}
    img.border-box{padding:0}
    .info-left{width:600px}
    .info-right{width:240px;padding-left:10px;padding-top:46px}
    .albums-data ul{list-style:none;padding:0;margin:0}
    .albums-data ul>li{display:inline-block;cursor:pointer;text-align:center;padding:0;margin:0 5px 5px 0}
    .user-alboms ul>li{width:70px;height:95px}
    .main-photo-page{width:850px;margin:auto}
    .blue{color:#0000FF}
    .info-album{font-weight:bold;padding:5px}
    .albums-info > img{vertical-align:middle}
    #user-photo{position:relative;overflow-y:scroll;max-height:186px;padding:5px 0 0 5px;border-top:2px solid #ccc;border-bottom:2px solid #ccc}
    #user-photo ul>li img,.user-alboms ul>li{opacity:.6}
    #user-photo ul>li img{width:50px;height:50px}#info-user{width:226px}
    .user-alboms ul>li img{width:70px;height:70px}
    .air{padding-left:10px}
    .opacity-photo,.active-photo,.user-alboms ul>li:hover,#user-photo ul>li img:hover,.f-flip:hover,.heart-1,.mods:hover,.check-0:hover,.check-1{opacity:1 !important}
    .active-photo,.user-alboms ul>li:hover,#user-photo ul>li img:hover{border:1px solid #F00 !important;box-shadow:0 0 8px #F00 !important}
    .heat-bottom{width:450px;margin:0 auto;text-align:center}
    .heat-bottom ul{list-style:none;padding:0;margin:0;font-weight:bold}
    .heat-bottom > div{margin:10px 0;color:red;font-size:16px;font-weight:bold}
    .loading{position:relative;cursor:default;text-shadow:none !important;color:transparent !important;opacity:1;pointer-events:auto;-webkit-transition:all 0s linear,opacity .1s ease;transition:all 0s linear,opacity .1s ease}
    .loading:before{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;border-radius:500rem;border:.2em solid rgba(0,0,0,.15)}
    .loading:after{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;-webkit-animation:button-spin .6s linear;animation:button-spin .6s linear;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;border-radius:500rem;border:.2em solid transparent;border-top-color:#FFF;box-shadow:0 0 0 1px transparent}
    @-webkit-keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
    @keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
    .grayscale{-webkit-filter:grayscale(100%);-moz-filter:grayscale(100%);-ms-filter:grayscale(100%);-o-filter:grayscale(100%);filter:grayscale(100%);filter:gray}
    #load-photo{position:relative;display:block;margin-bottom:10px}
    .albums-data{clear:both}
    .p-botom,.mods{display:inline-block}
    .p-botom{background:url('/img/spritesheet-5.png') no-repeat;padding-left:22px;margin-right:10px}
    .mods,.check-0,.check-1{background:url('/img/spritesheet-6.png') no-repeat}
    .ploading,.f-flip,.modsh,.check-0,.check-1{position:absolute}
    .ploading,.f-flip{top:0}
    .f-left,.ploading{left:0}
    .f-right{right:0}
    .modsh,.check-0,.check-1{bottom:0}
    .ploading{display:block;background:#fff url('/img/ellipsis.gif') center center no-repeat;opacity:0.7;width:100%;height:100%}
    .f-flip,.mods,.check-0,.check-1{cursor:pointer;opacity:0.3;width:50px;height:50px;transition:.3s}
    .f-flip{background:url('/img/spritesheet-4.png') no-repeat}
    .modsh{width:105px;height:50px}
    .m-g{background-position:-5px -125px}
    .m-y{background-position:-5px -185px}
    .wise{background-position:-5px -125px}
    .normal{background-position:-5px -185px}
    .heart-0{background-position:-5px -305px}
    .heart-1{background-position:-5px -245px}
    .pb-1{background-position:-2px -27px}
    .pb-2{background-position:-2px -3px}
    .pb-3{background-position:-2px -75px}
    .pb-4{background-position:-2px -98px}
    .check-0{background-position:-5px -65px}
    .check-1{background-position:-5px -5px}
    .t-comment{margin-bottom:10px}
    .del-comm,.red-comm{color:#CCC;float:right;cursor:pointer;padding:2px}
    .del-comm:hover{color:#D21B1B}
    .d-user-info>div,.t-avatar,.t-user-info{display:table-cell;padding-right:5px;vertical-align:top}
    .t-avatar>img{border-radius:4px;padding:0;vertical-align:middle}
    .t-date{color:#A99898;font-size:0.9em}
    span.ww{display:block;margin:10px 0 10px 40px}
    .p-form{margin:10px 0}
    .p-form>textarea{width:98%}
    span.u-bold{font-weight:bold;color:#747474}
    .n-0{color:red !important}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
    <div class="main-photo-page">
        <div id="albums">
            <div id="response-photo">
                <?php if(!empty($fails)) {?>
                    <div id="heart">
                        <img src="/img/heart-stop.png" width="439" height="400" alt="heart-stop" />
                        <?php if (!empty($albom['pass'])) {?>
                            <div id="user-password" style="margin-top: -100px;">
                                <form method="post" action="">
                                    <input id="input-pass" class="input-main" data-aid="<?= $albom['aid']?>" style="width: 120px;" type="number" required="required" pattern="[0-9]*" placeholder="Скажи пароль &hearts;"  title="Только цифры" autocomplete="off"/>
                                    <button type="submit" class="btn btn-primary">СКАЗАТЬ</button>
                                </form>
                            </div>
                        <?php }else{?>
                            <div class="heat-bottom">
                                <div>Вы не можете просматривать фотографии по причине:</div>
                                <ul>
                                    <?php foreach ($fails as $value) {?>
                                        <li><?= $value?></li>
                                    <?php }?>
                                </ul>
                            </div>
                        <?php }?>
                    </div>
                <?php }else{?>
                    <a style="width: 400px;height: 400px"></a>
                <?php }?>
            </div>
            <div class="albums-data">
                <div class="albums-info info-left">
                    <div class="info-album">
                        Альбом: <span class="blue"><?= html($albom['title']) ?: 'Без названия';?></span>
                        <br>
                        <?php if(empty($albom['pass'])) {?>
                            <span style="color: #7E7E7E"><?= $access_album[$albom['albvisibility']]; ?></span>
                        <?php }else{?>
                            <span class="red">ПОД ПАРОЛЕМ</span>
                        <?php }?>
                        <?php if($myrow->isModerator()) {?>
                            <a style="display: inline-block;float: right;" href="/moder_viewalbum_<?= $albom['aid']; ?>_page_1">Модерировать</a>
                        <?php }?>
                    </div>
                    <div id="user-photo">
                        <ul>
                            <?php foreach ($photos as $value) {?><li><a href="/albums_<?= $value['aid']; ?>_<?= $value['pid']; ?>"><img class="border-box<?php if($value['pid'] == $id_photo) {echo ' active-photo opacity-photo';} ?> <?= $value['img_path'][1];?>" width="50" height="50" src="/<?= $value['img_path'][0]; ?>" alt="<?= $value['pid']; ?>"></a></li><?php }?>
                        </ul>
                    </div>
                    <div id="user-comment-photo">

                    </div>

                </div>
                <div class="albums-info info-right">
                    <div id="info-user" style="background: <?= $albom['background']; ?>">
                        <div class="albums-info">
                            <img class="border-box" src="<?= $albom['avatar']; ?>" width="70" height="70" alt="avatar">
                        </div>
                        <div class="albums-info air">
                            <a class="hover-tip" href="/id<?= $albom['id']; ?>">
                                <img src="/img/info_small_<?= $albom['gender'];?>.png" width="15" height="14" alt="gender"> <?= $albom['login']; ?>
                            </a>
                            <br>
                            <?= html($albom['fname']); ?>
                            <br>
                            <span class="c-<?= (int)(bool)strcmp(mb_strtolower($myrow->city),mb_strtolower($albom['city'])); ?>"><?= html($albom['city']); ?></span>
                        </div>
                    </div>
                    <div style="color: #7E7E7E;margin-bottom: 5px;font-weight: bolder;clear: both">Альбомы:</div>
                    <div class="user-alboms">
                        <ul>
                            <?php foreach ($alboms as $value) {?><li class="border-box<?php if($value['aid'] == $id_album) {echo ' active-photo';} ?>"><a href="/albums_<?= $value['aid']; ?>_<?= $value['pid']; ?>"><img class="<?= $value['class']; ?>" src="/<?= $value['photo_path']; ?>" width="70" height="70" alt="alboms"></a><br><span class="<?= $value['pass'] ? 'a-1' : 'm-' . $value['albvisibility'];?>"><?= $value['count']; ?> фото</span></li><?php }?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script src="/js/history.min.js"></script>
<script>
  jQuery.fn.extend({insertAtCaret:function(myValue){return this.each(function(){if(document.selection){this.focus();var sel=document.selection.createRange();sel.text=myValue;this.focus();}
    else if(this.selectionStart||this.selectionStart=='0'){var startPos=this.selectionStart;var endPos=this.selectionEnd;var scrollTop=this.scrollTop;this.value=this.value.substring(0,startPos)+myValue+this.value.substring(endPos,this.value.length);this.focus();this.selectionStart=startPos+myValue.length;this.selectionEnd=startPos+myValue.length;this.scrollTop=scrollTop;}else{this.value+=myValue;this.focus();}})}});

  function insertName(nick){comments.find('textarea').insertAtCaret(nick);return false;}
  function supports_html5_storage(){try{return'localStorage'in window&&window['localStorage']!==null;}catch(e){return false;}}

  var rPhoto = $('#response-photo'),
    uPhoto = $('#user-photo'),
    comments = $('#user-comment-photo'),
    pswd = 0,pend,
    storage = supports_html5_storage(),
    lsrtImg = (storage)? localStorage.getItem('photo'): null;
  access = <?= (int)empty($fails); ?>;

  $(window).load(function () {
    var location = window.history.location || window.location;
    var motor = {
      cache : {},
      next : 0,
      curent: 0,
      panding: true,
      wise : lsrtImg === null ? 'wise' : lsrtImg,
      getPage : function () {
        var a = location.pathname.split('_')[1] || window.location.pathname.split('_')[1];
        var b = location.pathname.split('_')[2] || window.location.pathname.split('_')[2];
        this.observer(a,b);
      },
      getClick: function (aid) {
        this.observer(aid,this.next);
      },
      observer : function (aid,pid) {
        this.setImagActive(pid);
        if(pid in this.cache) {
          this.addPhoto(this.cache[pid],aid);
        }else{
          this.getPhoto(aid,pid);
        }
      },
      setHeart: function (o) {
        var a = o.data('set'),b = !a *1,c= o.data('pid'),v = o.parent('a').next('div').find('.pb-3');
        this.cache[c]['heart'] = a;
        this.cache[c]['likes'] += (a?1:-1);
        o.removeClass('heart-' + b).addClass('heart-' + a).data('set',b);
        v.text(this.cache[c]['likes']);
        $.post('/ajax/',{cntr:'Likes',action:'setLapa',data:{pid:c,attr:a}},'json');
      },
      setWidth: function (o) {
        this.wise = (this.wise == 'normal')?'wise':'normal';
        if(storage) {
          localStorage.setItem('photo',this.wise);
        }
        motor.getPage();
      },
      setImagActive: function (pid) {
        var f=uPhoto.find('img'),c=f.length-1;
        f.each(function (k) {
          var i = $(this);
          i.removeClass('active-photo');
          if(i.attr('alt') == pid) {
            motor.next =  f.eq(k<c?k+1:0).attr('alt');
            motor.curent = k+1;
            i.addClass('active-photo opacity-photo');
            if(k % 10 == 0) {
              uPhoto.scrollTop(k*5+k);
            }
          }
        });
      },
      getPhoto: function (aid,pid) {
        if(access && this.panding) {
          $.ajax({
            type: 'post',
            url: "/ajax/",
            data: {cntr:'Albums',action:'getPhoto',data:{pid:pid,pass:pswd,aid:aid}},
            dataType:'json',
            beforeSend: function(){
              motor.panding = false;
              pend = setTimeout(function () {
                rPhoto.children('a').append('<b class="ploading"></b>');
              },50);
            },
            success: function(data){
              clearTimeout(pend);
              if(data['status'] === 1) {
                motor.addPhoto(data,aid);
                motor.cache[pid] = data;
              }else{
                rPhoto.html(data['html']);
                //userCom.text('');
              }
              motor.panding = true;
            }
          });
        }

      },
      addPhoto : function (data) {
        var width = data['img'][this.wise]['width'],height = data['img'][this.wise]['height'];
        var str = '<a href="/albums_'+ data['data'][1] +'_'+ this.next +'" id="load-photo" style="width:'+ width +'px;height:'+ height +'px;background:#FFF url('+ data['img']['src'] +') center no-repeat;background-size: '+ width +'px '+ height +'px">' +
          '<img class="border-box"  src="/img/user-photo.gif" style="width:'+ (width - 1) +'px; height:'+ (height - 1) +'px;">' +
          '<div class="f-flip f-right '+ this.wise +'" data-action="setWidth"></div>' +
          '<div class="f-flip f-left heart-'+ data['heart'] +'" data-action="setHeart" data-set="'+!data['heart'] *1+'" data-pid="'+ data['data'][0] +'" title="Лайк"></div>' + data['caption'] +
          '<div id="mcp" class="check-'+ data['m_check'] +' f-left" data-set="'+!data['m_check'] *1+'" data-pid="'+ data['data'][0] +'"></div>'+
          '</a>' +
          '<div><span class="p-botom pb-1" title="Фото">'+ motor.curent +' из '+ data['count'] +'</span>' +
          '<span class="p-botom pb-2" title="Просмотры">'+ data['view'] +'</span>' +
          '<span class="p-botom pb-3" title="Лайки">'+ data['likes'] +'</span>'+
          '<span class="p-botom pb-4" title="Комментарии">'+ data['com'] +'</span>'+
          '</div>';
        rPhoto.html(str);
        this.addComments(data['comments']);
      },
      addComments: function (c) {
        comments.html(c);
      },
      start:function (href,e) {
        e.preventDefault();
        if (this.panding) {
          if(href != location.href) {
            history.pushState(null, null, href);
            history.replaceState(null,null,href);
          }
          this.getPage();
        }
      }
    };

    $(window).on('popstate', function() {
      if (motor.panding) {
        motor.getPage();
      }
    });

    uPhoto.on('click','a',function(e) {
      motor.start(this.href,e);
    });

    rPhoto.on('click','a#load-photo',function (e) {
      motor.start(this.href,e);
    });

    rPhoto.on('click','.f-flip',function (e) {
      e.preventDefault();
      e.stopPropagation();
      var o = $(this);
      var a = o.data('action');
      if(a in motor) {
        motor[a](o);
      }
    });

    comments.on('click','.del-comm',function () {
      if(motor.panding && confirm('Удалить комментарий?') === true) {
        motor.panding = !motor.panding;
        var a=$(this),b=a.data('value'),c=a.data('pid');
        a.addClass('loading');
        $.post('/ajax/',{cntr:'ManageCom',action:'setPhotoComments',id:b,param:1},function (data) {
          motor.panding = !motor.panding;
          if(data['status'] == 1){delete motor.cache[c];a.parent().slideUp()}
        },'json');
      }
    });

    comments.on('submit','form',function (e) {
      e.preventDefault();
      if(motor.panding) {
        motor.panding = !motor.panding;
        var a=$(this);
        a.find('button').addClass('loading');
        $.post('/usAjaxPhoto.php',a.serialize(),function (json) {
          motor.panding = !motor.panding;
          if(json['status'] == 1) {
            delete motor.cache[json['pid']];
            motor.getPage();
          }
        },'json');
      }
    });

    <?php if(!empty($fails) && !empty($albom['pass'])) :?>

    var sendpass = 1;
    var input = $('#input-pass');

    if(storage) {
      input.val(localStorage.getItem('alb'+ input.data('aid')));
    }

    input.on('input propertychange',function () {
      input.removeClass('form-danger');
    });

    input.parent('form').submit(function (e) {
      e.preventDefault();
      var but = input.next('button');
      var data = [];
      data.push(input.data('aid') * 1);
      data.push(input.val() * 1);
      if(data[0] && data[1] && sendpass && !input.hasClass('form-danger')) {
        $.ajax({
          type: 'post',
          url: "/ajax/",
          data: {cntr:'UsPhPass',action:'getPhotoPass',aid:data[0],pass:data[1]},
          dataType:'json',
          beforeSend: function(){
            sendpass = !sendpass;
            but.addClass('loading');
          },
          success: function(json) {
            if(json['status'] == 1) {
              var p = [];
              $.each(json['data'],function (k,v) {
                p.push('<li><a href="/albums_'+v.aid+'_'+v.pid+'"><img class="border-box" width="50" height="50" src="/'+ v.img +'" alt="'+ v.pid +'"></a></li>');
              });
              $('.user-alboms').find('li.active-photo').find('img').attr('src',json['data'][0]['img']).removeClass('grayscale');
              uPhoto.find('ul').html(p.join(''));
              access = true;
              pswd = data[1];
              motor.getPage();
              if(storage) {
                localStorage.setItem('alb' + data[0],data[1])
              }
            }else{
              if(storage && localStorage.getItem('alb'+ data[0]) === data[1]) {
                localStorage.removeItem('alb'+ data[0]);
              }
              sendpass = !sendpass;
              but.removeClass('loading');
              input.addClass('form-danger');
            }
          }
        });
      }
    });
    <?php endif;?>
    <?php if($myrow->isModerator()) :?>
    rPhoto.on('click','.mods',function (e) {
      e.preventDefault();
      e.stopPropagation();
      var a = $(this).data('action');
      window.open(a);
    });
    rPhoto.on('click','#mcp',function (e) {
      e.preventDefault();
      e.stopPropagation();
      var o =$(this);
      var a = o.data('set'),b = !a *1,c= o.data('pid');
      motor.cache[c]['m_check'] = a;
      o.removeClass('check-' + b).addClass('check-' + a).data('set',b);
      $.post('/ajax/',{cntr:'Moderator',action:'setCheckPhoto',data:{pid:c,attr:a}},'json');
    });
    <?php endif;?>

    motor.getPage();
  });
</script>
<?php $this->stop(); ?>
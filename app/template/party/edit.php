<?php
/**
 * @var \Swing\System\View $this
 */

use Swing\Exceptions\NotFoundException;
use Swing\Exceptions\ForbiddenException;

$dbh = db();
$myrow = user();

$id_event = (int)request()->get('id');


$sql = 'select id_user, city, title, `text`, img, `status`, timer, maper, 
      address, begin_date, end_date, club, coords, v_count, price, site, email, vkontakte, tel
      from `events` 
      where id = ' . $id_event .' and `status` <> 0 
    limit 1';

$sth = $dbh->query($sql);

if(!$sth->rowCount()) {
    throw new NotFoundException('Анонса не существует');
}

$event = $sth->fetch();

if((int)$event['id_user'] !== $myrow->id && !$myrow->isModerator()) {
    throw new ForbiddenException('Доступ запрещен');
}

foreach (['price','site','tel','coords'] as $value) {
    $event[$value] = json_decode($event[$value]);
}
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Редатировать анонс<?php $this->stop(); ?>
<?php $this->start('description'); ?>Редактировать анонс вечеринки<?php $this->stop(); ?>

<?php $this->start('style'); ?>
    <link rel="stylesheet" href="/js/jtime/jquery.datetimepicker.min.css">
    <link rel="stylesheet" href="/js/Trumbowyg/ui/trumbowyg-sw.css">
    <link rel="stylesheet" href="/js/countdown/jquery.countdown.css">
    <style>
        .t-breadcrumbs{padding:5px;font-style:italic;margin-bottom:10px}
        #event{margin:10px}
        #event hr{border-style:groove;margin:20px 0}
        .e-label,.e-price label{color:#366176}
        .e-label{display:inline-block;margin-bottom:10px}
        .e-input{font:14px Arial,Tahoma,Verdana,sans-serif;padding:4px;border:1px solid #ccc;border-radius:2px}
        .e-input:focus{border-color:#99baca;background:#f5fbfe}
        .e-title{width:470px;font-weight:700;font-size:16px;padding:5px}
        .e-input.date{width:112px}
        .form-danger{border-color:#dc8d99!important;background:#fff7f8!important;color:#d85030!important}
        .form-success{border-color:#8ec73b!important;background:#fafff2!important;color:#659f13!important}
        .e-table{display:table-cell;position:relative;vertical-align:top;padding-right:20px}
        .e-ava,.e-file{vertical-align:middle}
        .e-file{display:inline-block;position:relative;overflow:hidden}
        #file,.e-ava{display:block}
        .e-ava{width:250px;height:auto;padding:0;border:1px solid #ccc;border-radius:2px}
        #file{position:absolute;top:0;z-index:1;width:100%;opacity:0;cursor:pointer;left:0;font-size:500px;line-height:1.42}
        .mask{background:url(/img/load_green.gif) center no-repeat}
        .mask>img{visibility:hidden}
        .e-del-ava{position:absolute;top:0;right:20px;width:30px;height:30px;cursor:pointer;opacity:.3;background:url('/img/del_ban.png') no-repeat center;transition:.3s;}
        .e-del-ava:hover{opacity:1 !important;}
        .loading{position:relative;cursor:default;text-shadow:none !important;color:transparent !important;opacity:1;pointer-events:auto;-webkit-transition:all 0s linear,opacity .1s ease;transition:all 0s linear,opacity .1s ease}
        .loading:before{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;border-radius:500rem;border:.2em solid rgba(0,0,0,.15)}
        .loading:after{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;-webkit-animation:button-spin .6s linear;animation:button-spin .6s linear;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;border-radius:500rem;border:.2em solid transparent;border-top-color:#FFF;box-shadow:0 0 0 1px transparent}
        @-webkit-keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
        @keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
        .e-price input{width:350px}
        .e-price{border-spacing:5px}
        .e-price caption,.e-head{text-align:left;font-size:16px;font-weight:700;padding:2px 0}
        .e-tel{margin-top:10px}
        .e-tel ul {list-style-type:none; margin:0; padding:0;}
        .tel-capt{width: 250px}
        #vkontakte{width:220px;height:300px;margin:10px}
    </style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<div class="t-breadcrumbs">
    <a href="/my_events">Мои встречи</a> &bull; <?= html($event['title'])?>
</div>
<h2>Редактировать встречу</h2>
<form id="event" action="" method="post">
    <hr>
    <input type="hidden" name="id_event" value="<?= $id_event; ?>">
    <div class="e-table" style="text-align: center">
        <img class="e-ava" src="/<?= $event['img']; ?>" alt="avatar">
        <a class="e-file" target="_self">Сменить постер<input id="file" type="file"></a>
        <input type="hidden" name="img" value="<?= $event['img']; ?>">
        <div class="e-del-ava" title="Удалить картинку" onclick="delEventImg(this);"></div>
    </div>
    <div class="e-table">
        <label class="e-label">
            Наименование
            <br>
            <input class="e-input e-title" type="text" name="title" title="Наименование" value="<?= html($event['title']); ?>" placeholder="Например: Самая крутая вечеринка в Мире" required>
        </label>

        <div>
            <label class="e-label">
                Дата начала
                <br>
                <input id="s-date" class="e-input date" type="text" name="s-date" value="<?= date('d.m.Y H:i', strtotime($event['begin_date']));?>" title="Дата начала" placeholder="Выберите дату" autocomplete="off" required>
            </label>
            &mdash;
            <label class="e-label">
                Дата окончания
                <br>
                <input class="e-input date" type="text" name="e-date" value="<?= date('d.m.Y H:i',strtotime($event['end_date']));?>" title="Дата окончания" placeholder="Выберите дату" autocomplete="off" required>
            </label>
        </div>

        <div>
            <label class="e-label">
                Город
                <br>
                <input style="width: 180px" class="e-input" type="text" name="city" value="<?= html($event['city']); ?>" title="Город" placeholder="Укажите город" required>
            </label>
        </div>
        <div>
            <label class="e-label">
                Место
                <br>
                <input id="club" style="width: 320px" class="e-input" type="text" name="club" value="<?= html($event['club']); ?>" title="Место проведения" placeholder="Например: клуб &quot;Эдем&quot;, Кафе, Сауна, Коттедж" required>
            </label>
        </div>

    </div>
    <hr>

    <div>
        <textarea id="editor" name="text" placeholder="Описание" required><?= $event['text']; ?></textarea>
    </div>
    <hr>

    <table class="e-price">
        <caption>Дополнительная информация <small>(Необязательный блок)</small></caption>
        <tr>
            <td><label for="g3">Для пар МЖ:</label></td>
            <td><input id="g3" class="e-input" type="text" name="price[]" value="<?= html($event['price'][0]); ?>" title="Цена для пар МЖ" placeholder="Например: от 3000 р.- предоплата"></td>
        </tr>
        <tr>
            <td><label for="g2">Для девушек:</label></td>
            <td><input id="g2" class="e-input" type="text" name="price[]" value="<?= html($event['price'][1]); ?>" title="Цена для Девушек" placeholder="Например: Бесплатно"></td>
        </tr>
        <tr>
            <td><label for="g1">Для мужчин:</label></td>
            <td><input id="g3" class="e-input" type="text" name="price[]" value="<?= html($event['price'][2]); ?>" title="Цена для мужчин" placeholder="Например: 1000 $ США - по рекомендации"></td>
        </tr>
    </table>
    <hr>

    <div class="e-table">
        <div class="e-head" style="margin-bottom:20px">Контакты <small>(Необязательный блок)</small></div>
        <div>
            <label class="e-label">
                Сайт
                <br>
                <input class="e-input" style="width: 260px" type="text" name="site" value="<?php if(!empty($event['site'][0])){echo html($event['site'][0]);}?>" title="Сайт" placeholder="Ссылка на тему в группе или свой сайт">
            </label>
            &mdash;
            <label class="e-label">
                Анкор
                <br>
                <input class="e-input" style="width: 175px" type="text" name="ancor" value="<?php if(!empty($event['site'][1])){echo html($event['site'][1]);}?>" title="Анкор" placeholder="Например: клуб &quot;Фортуна&quot;">
            </label>
        </div>
        <div>
            <label class="e-label">
                email
                <br>
                <input class="e-input" style="width: 300px" type="email" name="email" value="<?= html($event['email']); ?>" title="email" placeholder="Например: vasya@vsehchpoknu.ru">
            </label>
        </div>
        <div>
            <label class="e-label">
                Добавить свой виджет Вконтакте
                <br>
                <input id="e-vkontakte" class="e-input" style="width: 300px" type="number" name="vkontakte" value="<?php if(!empty($event['vkontakte'])){echo (int)$event['vkontakte'];}?>" title="Вконтакте" placeholder="id группы (только цифры)">
                &nbsp;
                <a href="https://vk.com/pages?oid=-4489985&p=%D0%9A%D0%B0%D0%BA_%D1%83%D0%B7%D0%BD%D0%B0%D1%82%D1%8C_id_%D0%B3%D1%80%D1%83%D0%BF%D0%BF%D1%8B_%D0%B8%D0%BB%D0%B8_%D0%BF%D1%80%D0%BE%D1%84%D0%B8%D0%BB%D1%8F%3F" target="_blank">Как узнать id группы?</a>
            </label>
        </div>

        <div class="e-tel">
            <ul>
                <?php if(!empty($event['tel'])) {?>
                    <?php foreach($event['tel'] as $value) {?>
                        <li>
                            <label class="e-label">
                                Телефон
                                <br>
                                <input class="e-input e-mask" type="tel" name="tel[]" value="<?= html($value[0]); ?>" placeholder="+7 (920) 552-27-97">
                            </label>
                            <label class="e-label">
                                Примечание
                                <br>
                                <input class="e-input tel-capt" type="text" name="tel-capt[]" value="<?= html($value[1]); ?>" placeholder="Дарья (мобильный/WhatsАpp/Viber)">
                            </label>
                            <button type="button" class="btn btn-default" onclick="delTel(this)">&nbsp;-&nbsp;</button>
                        </li>
                    <?php }?>
                <?php }else{?>
                    <li>
                        <label class="e-label">
                            Телефон
                            <br>
                            <input class="e-input e-mask" type="tel" name="tel[]" placeholder="+7 (920) 552-27-97">
                        </label>
                        <label class="e-label">
                            Примечание
                            <br>
                            <input class="e-input tel-capt" type="text" name="tel-capt[]" placeholder="Дарья (мобильный/WhatsАpp/Viber)">
                        </label>
                        <button type="button" class="btn btn-default" onclick="delTel(this)">&nbsp;-&nbsp;</button>
                    </li>
                <?php }?>
            </ul>
            <button id="add-tel" type="button" class="btn btn-default">Добавить еще телефон</button>
        </div>
    </div>
    <div class="e-table">
        <div id="vkontakte"></div>
    </div>
    <hr>

    <div>
        <label class="e-label" style="color: #b4b4b4">
            <input type="checkbox" name="gallery" value="1" disabled>Добавить фотографии для создания галереи <small>(будет доступно позже)</small>
        </label>
    </div>
    <div>
        <input type="hidden" name="timer" value="0">
        <label class="e-label">
            <input id="change-timer" type="checkbox" name="timer" value="1" <?php if($event['timer']){echo 'checked';}?>>Добавить таймер обратного отсчета до начала мероприятия
        </label>
        <div id="countdown" class="countdownHolder" style="display: none;"></div>
    </div>
    <div>
        <input type="hidden" name="maper" value="0">
        <label class="e-label">
            <input id="change-maper" type="checkbox" name="maper" value="1" <?php if($event['maper']){echo 'checked';}?> disabled>Добавить Яндекс-карту
            <input id="address" type="hidden" name="address" value="<?= html($event['address']); ?>">
            <input id="coords" type="hidden" name="coords" value="<?php if(!empty($event['coords'])) {echo implode(',', $event['coords']);} ?>">
        </label>
        <div style="display: none;">
            <?php if($event['maper']) {?>
                <div id="res-adr">
                    Вы выбрали: <span class="green"><?= html($event['address']); ?></span>
                </div>
            <?php }else{?>
                <div id="res-adr" style="visibility: hidden">Выбор адреса</div>
            <?php }?>
            <small class="red">Выберите адрес с помощью поиска или щелкните мышкой на нужном элементе. Так же можно перетаскивать метку.</small>
            <div id="map" style="width: 600px; height: 340px;margin-bottom: 10px"></div>
        </div>
    </div>

    <div id="e-errors" class="red"></div>
    <hr>
    <button class="btn btn-primary">Сохранить</button>

</form>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script src="/js/jtime/jquery.datetimepicker.full.min.js"></script>
<script src="/js/Trumbowyg/trumbowyg-sw.js"></script>
<script src="/js/countdown/jquery.countdown.js"></script>
<script src="/js/jquery.maskedinput.min.js"></script>
<script src="//vk.com/js/api/openapi.js?136"></script>
<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script>
  var templateTel = '<li><label class="e-label">Телефон<br><input class="e-input e-mask" type="tel" name="tel[]" placeholder="+7 (920) 552-27-97"></label> <label class="e-label">Примечание<br><input class="e-input tel-capt" type="text" name="tel-capt[]" placeholder="Дарья (мобильный/WhatsАpp/Viber)"></label> <button type="button" class="btn btn-default" onclick="delTel(this)">&nbsp;-&nbsp;</button></li>';
  var timer = $('#change-timer');

  $('#add-tel').on('click',function () {
    $(this).prev('ul').append(templateTel);
    $('.e-mask').mask("+9 (999) 999-99-99");
  });

  function delTel(e){
    $(e).parent('li').remove();
  }

  $('.e-mask').mask("+9 (999) 999-99-99");

  timer.on('change',function () {
    countdown();
    $(this).parent('label').next().toggle();
  });

  if(timer.is(':checked')) {
    timer.trigger('change');
  }

  function countdown() {
    var a = $('#s-date'),
      d = a.val().split(/[. :]/);
    if(d == '' || d == undefined) {
      a.focus();
      return;
    }
    $('#countdown').html('').countdown({
      timestamp : new Date(d[2],d[1]-1,d[0],d[3],d[4])
    });
  }

  function delEventImg(o) {
    $(o).parent('div').find('img').attr('src','img/party-defolt.jpg').nextAll().eq(1).val('img/party-defolt.jpg');
  }


  $('.date').datetimepicker({
    dayOfWeekStart:1,
    format:'d.m.Y H:i',
    closeOnWithoutClick:false,
      <?php if($myrow->isMobile()) {echo 'inline:true,';} ?>
    onChangeDateTime:countdown
  });
  $.datetimepicker.setLocale('ru');

  $('#editor').trumbowyg({
    lang: 'ru',
    autogrow: true,
    mobile: true,
    tablet: true,
    btnsDef: {
      image: {
        dropdown: ['insertImage', 'upload', 'noembed'],
        ico: 'insertImage'
      }
    },
    btns: [
      ['viewHTML'],
      ['undo', 'redo'],
      ['fontsize'],
      'btnGrp-design',
      ['foreColor'],
      ['link'],
      ['image'],
      ['emoji'],
      'btnGrp-justify',
      'btnGrp-lists',
      ['horizontalRule'],
      ['removeformat'],
      ['fullscreen']
    ],
    plugins: {
      upload: {
        serverPath: 'wupload.php',
        fileFieldName: 'neweditor',
        urlPropertyName: 'link',
        data: [{
          name: 'neweditor',
          value: 1
        }]
      }
    }
  });

  var send_event=true;

  $('#event').on('submit',function (e) {
    e.preventDefault();
    if(!send_event) {
      return;
    }
    var data = $(this);
    var bb = data.find('button.btn');
    $.ajax({
      url: "/ajax/?cntr=Party&action=editParty",
      type: 'post',
      dataType: 'json',
      data:data.serialize(),
      beforeSend:function () {
        send_event = false;
        bb.addClass('loading');
      },
      success:function (json) {
        if(!json['status']) {
          var t = [];
          $.each(json['html'],function (k,v) {
            t.push('<li>' +  v + '</li>');
          });
          $('#e-errors').html('<ul>'+ t.join('') +'</ul>');
          bb.removeClass('loading');
          return;
        }
        window.location.replace('/my_events');
      },
      error:function () {
        alert("Forbidden 403");
      },
      complete:function () {
        send_event = true;
      }

    });

  });

  $("#file").on("change",function() {
    if(!window.FormData || !send_event) {
      alert("Forbidden 403");
      return;
    }

    var t = $(this);
    var file=t.get(0).files[0];

    if(file === undefined || file === null) {
      return;
    }
    if(file.type.match("image.*")===null || file.name.match(new RegExp("^[^/]+.(jpg|jpeg|gif|png)$","i"))===null){
      alert("Допущены к загрузке только картинки");
      return;
    }
    var r = t.parent('a').prev('img');
    var data=new FormData();
    data.append("event","image");
    data.append("event",file);
    $.ajax({url:"wupload.php",type:"POST",data:data,processData:false,contentType:false,dataType:"json",
      beforeSend:function(){
        send_event=false;
        r.attr('src','/img/img-transparent.png');
        r.parent().addClass("mask");
      },
      success:function(json){
        if(!json.status){
          alert(json.link);
          return;
        }
        r.attr("src",json.link);
        t.parent('a').next('input').val(json.link);
        r.parent().removeClass("mask");
      },
      complete:function () {
        send_event=true;
      }
    });
  });

  ymaps.ready(function () {
    var map,placemark,cmap = $('#change-maper');

    cmap.removeAttr('disabled').on('change',function () {
      var t = $(this);
      if(!t.is(':checked')) {
        t.removeAttr('checked').parent('label').next().hide();
        return;
      }
      t.attr('checket','checked').parent('label').next().show();
      if(map) {
        return;
      }

      ymaps.geocode('<?= $myrow->city; ?>', {
        results: 1
      }).then(function(r) {
        var fg = r.geoObjects.get(0);
        var sc = new ymaps.control.SearchControl({
          options: {
            float: 'none',
            floatIndex: 100,
            maxWidth:400,
            size:'large',
            placeholderContent:'Выберите адрес встречи',
            suppressYandexSearch : true,
            noPlacemark: true
          }
        });

          <?php if(!empty($event['coords'])) {?>
        map = new ymaps.Map('map', {
          center: [<?= implode(',', $event['coords']); ?>],
          zoom: 15,
          controls: ['zoomControl']
        });

        addPlacemark([<?= implode(',', $event['coords']); ?>],'<?= html($event['club']); ?>','<?= html($event['address']); ?>');

          <?php }else{?>

        map = new ymaps.Map('map', {
          center: fg.geometry.getCoordinates(),
          zoom: 10,
          controls: ['zoomControl']
        });

          <?php }?>


        map.controls.add(sc);

        sc.events.add('resultselect', function(e) {
          var i = e.get('index');
          sc.getResult(i).then(function(d) {
            addPlacemark(
              d.geometry.getCoordinates(),
              d.properties.get('name'),
              d.properties.get('text')
            );
          });
        }, this);
        map.events.add('click', function (e) {
          addPlacemark(e.get('coords'),false,false);
        });

      });

      function addPlacemark(c,i,b) {
        if(!placemark) {
          placemark = createPlacemark(c);
          map.geoObjects.add(placemark);
          placemark.events.add('dragend', function () {
            getAddress(placemark.geometry.getCoordinates(),false,false);
          });

        } else {
          placemark.geometry.setCoordinates(c);
        }
        getAddress(c,i,b);
      }

      function createPlacemark(c) {
        return new ymaps.Placemark(c, {
          iconCaption: 'Поиск...'
        }, {
          preset: 'islands#blueDotIconWithCaption',
          draggable: true
        });
      }

      function getAddress(c,i,b) {
        placemark.properties.set('iconCaption', 'Поиск...');
        if(i && b) {
          placeMarcAddProperty(c,i,b);
          return;
        }

        ymaps.geocode(c).then(function(r) {
          var fg = r.geoObjects.get(0);
          placeMarcAddProperty(
            c,
            fg.properties.get('name'),
            fg.properties.get('text')
          );
        });
      }

      function placeMarcAddProperty(c,i,b) {

        var t = $('#club').val();

        t = t ? decodeHtml(t) : t;

        placemark.properties
        .set({
          iconCaption: decodeHtml(i),
          balloonContent: b,
          balloonContentHeader: t
        });
        addAddresAndCoords(c,b);
      }

      function addAddresAndCoords(c,b) {
        $('#address').val(b)
        .next('input').val(c)
        .parent('label').next('div')
        .find('#res-adr').css('visibility','visible')
        .html('Вы выбрали: ' + '<span class="green">'+ b +'</span>');
      }
    });


    if(cmap.is(':checked')) {
      cmap.trigger('change');
    }
  });

  function decodeHtml(t) {
    return t
    .replace(/&amp;/g, "&")
    .replace(/&lt;/g, "<")
    .replace(/&gt;/g, ">")
    .replace(/&quot;/g, '"')
    .replace(/&#039;/g, "'");
  }

  $(document).on('click','a:not([target])',function () {
    return window.confirm('Изменения не будут сохранены. Уйти со страницы?');
  });

  var vk = $('#vkontakte'),
    evk = $('#e-vkontakte'),
    vkt;
  evk.on('input propertychange',function () {
    clearTimeout(vkt);
    var g=$(this).val();
    g = g || 82614373;
    vkt = setTimeout(function () {
      vk.html('');
      VK.Widgets.Group("vkontakte", {mode: 0, width: "220",height:'300'}, g);
    },500);
  });

  VK.Widgets.Group("vkontakte", {mode: 0, width: "220",height:'300'}, evk.val() ? evk.val() : 82614373);

</script>
<?php $this->stop(); ?>

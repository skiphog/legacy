<?php
/**
* @var \System\View $this
*/
$myrow = auth();

?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Модерация<?php $this->stop(); ?>
<?php $this->start('description'); ?>Модерация<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<link rel="stylesheet" href="/js/nprogress/nprogress.min.css">
<style>
    .head-nav-message,.mimg{position:relative;}
    .closeTab,.m-check,.mimg{cursor:pointer;}
    .m-buttom-ava,.mdiv,.nav-tabs>li{text-align:center;}
    .closeTab,.m-albums-thumb>div,.m-city-1,.m-info-table td:first-child,.m-login,.m-photo,.mc-0{font-weight:700;}
    #popup,.mdiv,.nav-tabs{border-radius:4px;}
    .nav-tabs{background:#2E8CE3;margin-bottom:10px;list-style:none;float:left;padding:0;}
    .nav-active,.nav-tabs li>a:hover{background-color:#24b662;}
    .nav-tabs li>a:active{background-color:#1c8c4c;}
    #popup,.mdiv{background:#fff;padding:10px;}
    .nav-tabs>li{float:left;width:130px;}
    .nav-tabs>li>a{display:block;margin-left:-1px;text-transform:uppercase;border-left:1px solid #fff;border-right:1px solid #fff;color:#fff;letter-spacing:.1em;padding:10px;}
    .nav-tabs li:first-child>a{border-left:none;border-radius:4px 0 0 4px;margin-left:0;}
    .nav-tabs li:last-child>a{border-right:none;border-radius:0 4px 4px 0;}
    .mdiv{display:inline-block;width:100px;overflow:hidden;border:1px solid #CCC;white-space:nowrap;margin:0 5px 5px 0;}
    #popup,.closeTab{position:absolute;}
    #popup{left:10%;display:none;z-index:100;border:1px solid #ccc;}
    .closeTab{font-size:30px;top:12px;right:12px;color:#fff;opacity:.5;padding:15px;}
    #response,.moder-log{min-width:800px;position:relative;}
    .m-check{background:url(/img/spritesheet-6.png) no-repeat;position:absolute;width:50px;height:50px;transition:.3s;}
    .m-check-large{left:15px;bottom:70px;}
    .m-check-small{left:0;bottom:0;}
    .check-0,.check-1{opacity:.3;}
    .check-1,.closeTab:hover,.m-check-large.check-0:hover{opacity:1!important;}
    .check-0{background-position:-5px -65px;}
    .check-1{background-position:-5px -5px;}
    .btn-select{margin-right:10px;padding:10px 20px;}
    .m-albums-thumb img,.m-anket,.m-avatar{border:1px solid #ccc;border-radius:4px;}
    .m-anket{padding:5px;}
    .m-anket,.m-buttom-ava,.m-form-moder,.m-info-table,.m-select-wrong{margin-bottom:20px;}
    .m-buttom-ava{margin-top:10px;}
    .m-photo{margin:10px 0;}
    .m-anket hr{border-style:ridge;margin:5px 0;}
    .m-table-cell{display:table-cell;vertical-align:top;}
    .m-anketa-info,.m-info-table{width:100%;}
    .m-anketa-info{padding-left:20px;}
    .m-anketa-info img{vertical-align:text-bottom;}
    .m-avatar,.m-select{width:200px;}
    .m-select{padding:3px;}
    .m-albums-thumb>div,.m-login,.m-photo{font-size:16px;}
    .m-albums-thumb{position:relative;display:inline-block;}
    .m-albums-thumb img{vertical-align:bottom;}
    .m-time{color:#6f6f6f;}
    .m-info-table{border-collapse:collapse;border-spacing:0;}
    .m-info-table td{vertical-align:middle;border-bottom:1px solid #e5e5e5;padding:10px 5px;}
    .m-info-table td:first-child{width:60px;}
    .m-valid-email-0,.mi-2,.mi-2:hover{color:red;}
    .mis-1{color:#A52A2A!important;text-decoration:line-through;}
    .m-textarea{width:90%;height:60px;border:1px dashed #e5e5e5;background:0 0;font:12px/18px Arial,Tahoma,Verdana,sans-serif;color:#909090;}
    .m-textarea:focus{color:#333;}
    .m-albums-thumb>div{position:absolute;top:30px;left:40%;text-shadow:0 1px 5px #ff0;}
    .m-hidden{display:none;}
    .mc-0,.m-city-1{color:#00F;}
    .more-info,.m-form-moder label{display:block;}
    .btn-foam{margin-top:-70px;margin-left:60px;opacity:.5;}
    .btn-foam:hover{opacity:1}
    .mask{background:url(/img/load_green.gif) center no-repeat}
    .mask>img{visibility:hidden}
    #m-checkbox{margin:10px}
    #m-checkbox > label{cursor:pointer}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<div class="moder-log">
    <div class="head-nav-message">
        <div class="nav-message">
            <ul class="nav-tabs">
                <li><a class="links" href="/moderator/list?action=moderations" data-action="moderations">Анкеты</a></li>
                <li><a class="links" href="/moderator/list?action=photo" data-action="photo">Фото</a></li>
            </ul>
        </div>
        <div style="clear:both"></div>
        <div id="m-checkbox" style="display: none">
            <label>
                <input class="m-checkbox" type="checkbox" name="city" value="<?php echo html($myrow->city); ?>"><?php echo html($myrow->city); ?>
            </label>
            <label><input class="m-checkbox" type="checkbox" name="unverified" value="1">Непроверенные</label>
            <label><input class="m-checkbox" type="checkbox" name="real" value="1">Реальные</label>
            <label><input class="m-checkbox" type="checkbox" name="online" value="1">Онлайн</label>
            <label><input class="m-checkbox" type="checkbox" name="1" value="1">Мужчины</label>
            <label><input class="m-checkbox" type="checkbox" name="2" value="1">Девушки</label>
            <label><input class="m-checkbox" type="checkbox" name="3" value="1">Пары</label>
            <label><input class="m-checkbox" type="checkbox" name="4" value="1">Tрансы</label>
        </div>
    </div>

    <div id="response"></div>
    <div id="popup"></div>
</div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script src="/js/nprogress/nprogress.min.js"></script>
<script>
      var res = $('#response'), links = $('.links'), popup = $('#popup'), checkbox = $('.m-checkbox'),
        divmcheck = document.getElementById('m-checkbox'), num = 0, st = true,
        that;
      var m_select = {
        tmpl: [
          'Пожалуйста, корректно заполните поле *Немного о себе*',
          'Пожалуйста, корректно заполните поле *Я ищу*',
          'Пожалуйста, корректно заполните поле *Немного о себе* и поле *Я ищу*',
          'Разбираемся с дублем',
          'Прочитайте, пожалуйста, письмо от модератора в личке.'
        ],
        generate: null
      };

      function parseUrlQuery () {
        var data = {};
        if (location.search) {
          var pair = location.search.substr(1).split('&');
          for (var i = 0; i < pair.length; i++) {
            var param = pair[i].split('=');
            data[param[0]] = param[1];
          }
        }
        return data;
      }

      function getContent () {
        var data = parseUrlQuery();

        if (!(data['action'] in window)) {
          return res.html('Некорректная ссылка');
        }
        divmcheck.style.display = 'photo' === data['action'] ? 'block' : 'none';
        num = 0;
        res.html('');
        getAjax(data);
      }

      function setCurrentUrl (data) {
        links.each(function (i, o) {
          o.className = o.dataset['action'] === data['action'] ? 'links nav-active' : 'links';
        });

        checkbox.each(function (i, o) {
          o.checked = o.name in data;
        });
      }

      function changeSend () {
        st = !st;
      }

      function addButtonMore (more) {
        if (more === 0) {
          return;
        }
        num += 78;
        var $more = $('<button/>').addClass('more-info btn btn-success').html('Показать еще').appendTo(res);
        $more.on('click', function () {
          $more.remove();
          getAjax(parseUrlQuery());
        });
      }

      function photo (j) {
        var s = [];
        $.each(j['res'], function (i, o) {
          var tml = '<div class="mdiv"><div class="mimg" data-img="/' + o['filepath'] + o['filename'] + '" data-link="' +
            o.aid + '_' + o.pid + '" data-check="' + o.m_check + '" data-pid="' + o.pid + '">' +
            '<img src="/' + o['filepath'] + 'thumb_' + o['filename'] + '" width="100" height="100" alt="img">' +
            '<div class="m-check m-check-small check-' + o.m_check + '"></div></div>' +
            '<div class="minfo">' +
            '<div><a class="hover-tip" href="/id' + o.id + '" target="_blank"><img src="/img/info_small_' + o.gender +
            '.png" width="15" height="14" alt="gender"> ' + o.login + '</a></div>' +
            '<div class="mc-' + o.city_eq + '">' + o.city + '</div>' +
            '</div>' +
            '</div>';
          s.push(tml);
        });
        res.append(s.join(''));
        addButtonMore(j['more']);
      }

      function moderations (j) {
        res.append(j['res']);
        document.getElementById('moder-count').innerHTML = j['status'] || '';
      }

      function getAjax (data) {
        if (!st) {return;}
        data.num = num;
        $.ajax({
          url: '/ajax/?cntr=ModerLog',
          data: data,
          beforeSend: function () {
            changeSend();
            NProgress.start();
          },
          success: function (json) {
            setCurrentUrl(data);
            window[data['action']](json);
          },
          complete: function () {
            changeSend();
            setTimeout(NProgress.done, 300);
          },
          error: function () {
            return alert('Access denied');
          }
        });
      }

      if (window.history && history.pushState) {
        window.addEventListener('popstate', function () {
          getContent();
        });

        links.on('click', function (e) {
          e.preventDefault();
          if (this.href !== window.location.href) {
            history.pushState(null, '', this.href);
            history.replaceState(null, '', this.href);
          }
          getContent();
        });

        checkbox.on('change', function () {
          var data = parseUrlQuery();
          checkbox.each(function (index, item) {
            if (item.checked) {
              data[item.name] = item.value;
            } else {
              delete data[item.name];
            }
          });
          var href = location.href.split('#')[0].split('?')[0] + '?';
          for (var a in data) {
            href += a + '=' + data[a] + '&';
          }
          history.pushState(null, '', href.slice(0, -1));
          history.replaceState(null, '', href.slice(0, -1));
          getContent();
        });
      }

      res.on('click', '.mimg', function () {
        that = $(this);
        var img = that.data('img'), offset = that.position(), c = that.data('check');
        var html = '<span class="closeTab" onclick="popup.hide();">&times;</span><div><img src="' + img +
          '" /><div id="mcp" class="m-check m-check-large check-' + c + '" data-set="' + !c * 1 + '" data-pid="' +
          that.data('pid') + '"></div></div>' +
          '<div style="text-align:center"><a class="phgoogle" href="https://www.google.com/searchbyimage?image_url=swing-kiska.ru' +
          img + '" target="_blank"><img src="/img/phgoogle.jpg" width="32" height="32" title="Поискать в Гугл?"/></a>' +
          '&nbsp;<a class="phgoogle" href="https://yandex.ru/images/search?rpt=imageview&img_url=https://swing-kiska.ru' +
          img +
          '" target="_blank"><img src="/img/phyandex.jpg" width="32" height="32" title="Поискать в Яндекс?"/></a><br />' +
          '<a href="/albums_' + that.data('link') + '" target="_blank">Cылка на фото</a></div>';
        popup.html(html).css({'top': offset.top + 200 + 'px'}).show();
      });

      popup.on('click', '#mcp', function () {
        var o = $(this);
        var a = o.data('set'), b = !a * 1, c = o.data('pid');
        o.removeClass('check-' + b).addClass('check-' + a).data('set', b);
        that.data('check', a).find('.m-check').removeClass('check-' + b).addClass('check-' + a).data('set', b);
        $.post('/ajax/', {cntr: 'Moderator', action: 'setCheckPhoto', data: {pid: c, attr: a}}, 'json');
      });

      res.on('click', '.m-toggle', function () {
        var tmpl = m_select.generate || generateAnswer();
        $(this).parent().next().show().find('.m-select-wrong').html(tmpl);
      });

      function generateAnswer () {
        var tmpl = [];
        for (var i = 0, l = m_select.tmpl.length; i < l; i++) {
          tmpl.push('<button type="button" class="btn btn-default btn-select" value="' + i + '">' + (i + 1) + '</button>');
        }
        return m_select.generate = tmpl.join('');
      }

      res.on('click', '.btn-select', function () {
        var that = $(this);
        that.parent().prev().val(m_select.tmpl[that.val()]);
      });

      res.on('click', '.m-form-submit', function () {
        if (!st) {return;}
        changeSend();
        var that = $(this);
        var data = that.parents('.m-anket').serialize() + '&status=' + that.val();
        NProgress.start();
        $.post('/ajax/', data + '&cntr=ModerLog&action=setModeration', function () {
          changeSend();
          getContent();
        });
      });

      res.on('click', '.btn-foam', function () {
        if (!st || true !== confirm('Замылить аватар?')) {return;}
        changeSend();
        var that = $(this);
        var img = that.prev().find('img');
        img.css('opacity', 0.5).parent().parent().addClass('mask');
        var user_id = that.parents('.m-table-cell').prev().val();
        $.post('/ajax/', {cntr: 'Moderator', action: 'setFoamAvatar', user_id: user_id}, function (j) {
          var a = j || {status: 0, html: 'Wrong'};
          if (a['status'] === 1) {
            img.attr('src', '/avatars/user_pic/' + j['img']).css('opacity', 1).parent().parent().removeClass('mask');
          } else {
            alert(j['html']);
          }
          changeSend();
        }, 'json');
      });

      getContent();

    </script>
<?php $this->stop(); ?>
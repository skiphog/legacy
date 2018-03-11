<?php
/**
 * @var  \System\View $this
 */

$myrow = auth();
?>
<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Сообщения<?php $this->stop(); ?>
<?php $this->start('description'); ?>Сообщения<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .nav-tabs,.msg-div,.msg-body,.msg-avatar>img{border-radius:4px;}
    .nav-tabs li>a:hover,.nav-active{background-color:#FAFAFA;border-left-color:rgba(0,0,0,.1) !important;border-right-color:rgba(0,0,0,.1) !important;}
    .nav-down li>a:hover,.nav-down .nav-active{color: #fff;background-color: rgba(24, 148, 195, 0.73)}
    .nav-tabs{background:#EBEBF3;color:#11638c;border:1px solid rgba(0,0,0,.06);margin:0 0 10px 0;padding:0;list-style:none;font-weight:bolder;float:left;}
    .nav-tabs>li{float:left}
    .nav-tabs>li>a{display:block;padding:10px;margin-left:-1px;border:1px solid rgba(0, 0, 0, 0);-webkit-transition:none;-moz-transition:none;transition:none;}
    .nav-tabs li:first-child > a {border-left: none;border-radius: 4px 0 0 4px;margin-left: 0;}
    .nav-tabs li:last-child > a {border-right: none;border-radius: 0 4px 4px 0;}
    #load-mes{float: left;margin: 2px 0 0 2px;display: none;}
    .msg-div{color:#444;border:1px solid #DDDDE5;margin:10px auto;padding: 10px;}
    .msg-div-header,.msg-div-message,.msg-info{display: table-cell;vertical-align:top;}
    .msg-div-header,.msg-info{padding-right:10px;}
    .msg-info {width: 110px;overflow: hidden}
    .msg-avatar>img{padding:0;}
    .msg-one{float:left;}
    .msg-div-message>div>span{display:block;float:right;color:#A99898;font-size:0.9em;}
    .msg-body{border:1px solid #DDDDE5;background-color:#FAFAFA;padding:10px;margin-bottom:5px;font-size:1.3em;clear:both;}
    .msg-div-footer{margin-top:10px;}
    .msg-div-footer > img{cursor: pointer};
    .nof-date{width:80px;font-weight:bolder;}
    .msg-visible-0{border-color:#843534;-webkit-box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 6px #ce8483;box-shadow:inset 0 1px 1px rgba(0,0,0,.075),0 0 6px #ce8483;}
    .msg-visible-0:after{font-size:0.8em;color:#F00;content:"[Не прочитано]";}
    .online-offline-0,.online-offline-1{display:inline-block;color:#fff;-webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;padding:0 2px;font-size:0.9em;}
    .online-offline-0{background-color:#CCC;}
    .online-offline-1{background-color:#F00;}
    .online-offline-0:before{content:'offline';}
    .online-offline-1:before{content:'online';}
    .scrollTop{position:fixed;width:100%;bottom:-50px;height:50px;text-align:center;background:url('/img/up.png') no-repeat center rgba(46, 140, 227, 0.29);z-index:9999;cursor:pointer;}
    .head-nav-message{position:relative;}
    .nav-down{border: 1px solid rgba(24, 148, 195, 0.73);}
    .nav-down>li>a{padding:4px 10px;}
    .getNewMessage,.getNewNotification,.getContacts,.getHotuns{display: none;}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<h2>Сообщения</h2>

<div class="head-nav-message">
    <div class="nav-message">
        <ul class="nav-tabs links">
            <li><a href="/my/dialogs?getNewMessage">СООБЩЕНИЯ <span class="cnt count-mes"><?php echo $myrow->getCountMessage();?></span></a></li>
            <li><a href="/my/dialogs?getNewNotification">УВЕДОМЛЕНИЯ <span class="cnt count-nof"><?php echo $myrow->getCountNotify();?></span></a></li>
            <li><a href="/my/dialogs?getContacts">КОНТАКТЫ</a></li>
            <li><a href="/my/dialogs?getHotuns">ХОТЮНЫ</a></li>
        </ul>
    </div>
    <div id="load-mes"><img src="/img/loading.gif" alt="loading" width="32" height="32" /></div>
    <div style="clear:both"></div>
    <div style="height: 40px;">
        <div class="nav-message getNewMessage">
            <ul class="nav-tabs nav-down">
                <li><a href="#" data-action="getNewMessage" class="nav-active">НОВЫЕ</a></li>
                <li><a href="#" data-action="getSent" class="nav-active">ПОЛУЧЕННЫЕ</a></li>
                <li><a href="#" data-action="getReceived" class="nav-active">ОТПРАВЛЕННЫЕ</a></li>
            </ul>
        </div>
        <div class="nav-message getNewNotification">
            <ul class="nav-tabs nav-down">
                <li><a href="#" data-action="getNewNotification" class="nav-active">НОВЫЕ</a></li>
                <li><a href="#" data-action="getArchive">АРХИВ</a></li>
            </ul>
        </div>
        <div class="nav-message getContacts">
            <ul class="nav-tabs nav-down">
                <li><a href="#" data-action="getContacts" class="nav-active">ДИАЛОГИ</a></li>
                <li><a href="#" data-action="getFavorites">ИЗБРАННЫЕ</a></li>
                <li><a href="#" data-action="getAllIgnore">ИГНОР</a></li>
            </ul>
        </div>
        <div class="nav-message getHotuns">
            <ul class="nav-tabs nav-down">
                <li><a href="#" data-action="getHotuns" class="nav-active">ХОТЮНЫ</a></li>
                <li><a href="#" data-action="getSendHotuns">ОТПРАВЛЕННЫЕ</a></li>
            </ul>
        </div>
        <div style="clear:both"></div>
    </div>
</div>
<div id="response"></div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
    <script>
      var resblock = $('#response'),loading = $('#load-mes'),links = $('ul.links'),linksDown = $('ul.nav-down'),pr,count_mes = 0,all_count = 0;

      function setOldPrTimeoute() {
        clearTimeout(pr);
        pr = setTimeout("show_privat()",30000);
      }

      function setCurentUrl() {
        links.find('a').each(function(i,o){
          o.className = (o.href == window.location.href)?'nav-active':'';
        });
      }

      function setCurentLinkDown(data) {
        linksDown.find('a').each(function(i,o){
          o.className = ($(o).data('action') == data)?'nav-active':'';
        });
      }


      function showDelete(e) {
        $(e).parent().next().toggle();
      }

      function doWhere(json,callback){
        if(all_count == 0) {
          resblock.html(json['html']);
        }else{
          resblock.append(json['html']);
        }
        if(json['status'] == 1){
          all_count +=20;
          addButtonMore(callback,all_count);
        }
      }

      var motor = {
        send: true,
        getNewMessage: function(json){
          $(".count-mes").text((json['status'] != 0)? json['status']:'');
          count_mes = json['status'];
          clearTimeout(pr);
          pr = setTimeout("motor.getAjax('get','showPivateNew')",10000);
          resblock.html(json['html']);
          setCurentUrl();
        },
        getNewNotification: function(json){
          setOldPrTimeoute();
          $(".count-nof").text((json['status'] != 0)? json['status']:'');
          resblock.html(json['html']);
          setCurentUrl();
        },
        getContacts: function(json) {
          setOldPrTimeoute();
          doWhere(json,'getContacts');
          setCurentUrl();
        },
        getHotuns : function(json) {
          setOldPrTimeoute();
          doWhere(json,'getHotuns');
          setCurentUrl();
        },

        getSent : function(json){
          setOldPrTimeoute();
          doWhere(json,'getSent');
        },
        getReceived : function(json){
          setOldPrTimeoute();
          doWhere(json,'getReceived');
        },
        getArchive:function(json) {
          doWhere(json,'getArchive');
        },
        getFavorites: function(json){
          doWhere(json,'getFavorites');
        },
        getAllIgnore : function(json) {
          doWhere(json,'getAllIgnore');
        },
        getSendHotuns : function(json){
          doWhere(json,'getSendHotuns');
        },

        deleteIgnore : function(json) {
          if(json['status'] == 1) {
            $('#msgid' + json['value']).remove();
          }
        },
        deleteDialog : function(json){
          if(json['status'] == 1) {
            $('#msgid' + json['value']).remove();
          }
        },
        addFavorit : function(json){
          if(json['status'] == 1) {
            $('#msgid' + json['value']).remove();
          }
        },
        delFavorit : function(json){
          if(json['status'] == 1) {
            $('#msgid' + json['value']).remove();
          }
        },

        setVisibleNot: function(json) {
          if(json['status'] != 0) {
            motor.getAjax('get','getNewNotification');
          }
        },
        setVisibleMessage : function(json){
          if(json['status'] != 0) {
            motor.getAjax('get','showPivateNew');
          }
        },

        showPivateNew: function(json) {
          if(count_mes != json['status']) {
            count_mes = json['status'];
            clearTimeout(pr);
            $(".count-mes").text((json['status'] != 0)? json['status']:'');
            resblock.html(json['html']);
          }
          setTitleCount(json['status']);
          pr = setTimeout("motor.getAjax('get','showPivateNew')",20000);
        },
        getAjax: function(method,action,params) {
          if(this.send == true) {
            var data = {cntr:'Message',action:action};
            if(typeof params == 'object') {
              for(var cur in params) {
                if (params.hasOwnProperty(cur)) {
                  data[cur] = params[cur];
                }
              }
            }
            $.ajax({
              url: '/ajax/',
              type: method,
              dataType: 'json',
              data: data,
              beforeSend: function() {
                motor.send = false;
              },
              success: function(json){
                motor.send = true;
                motor[action](json);
                loading.hide();
              }
            });
          }
        }
      };

      if(window.history && history.pushState) {
        window.addEventListener('popstate', function() {
          getContent();
        });

        links.on('click','a',function(e){
          e.preventDefault();
          if(this.href != window.location.href) {
            history.pushState(null, '', this.href);
            history.replaceState(null,'',this.href);
          }
          getContent();
        });

      }

      linksDown.on('click','a',function(e){
        e.preventDefault();
        all_count = 0;
        var data = $(this).data('action');
        loading.show();
        motor.getAjax('get',data);
        setCurentLinkDown(data);
      });

      resblock.on('click','.action',function(){
        var active = $(this);
        loading.show();
        motor.getAjax('post',active.attr('name'),{value: active.val()});
      });

      var scrollTop = $("<div/>").addClass('scrollTop').appendTo('body');
      scrollTop.click(function(e){e.preventDefault();$('html:not(:animated),body:not(:animated)').animate({scrollTop: 400}, 500)});
      var windowSt = $(window);
      windowSt.scroll(function(){
        (windowSt.scrollTop() > 1200 ) ? scrollTop.stop().animate({bottom:0}, 100) : scrollTop.stop().animate({bottom:-50}, 100);
      });

      function addButtonMore(action,value) {
        var $more = $('<button/>').addClass('more-info btn btn-success').attr({name:action,value:value}).html('Показать еще').appendTo(resblock);
        $more.on('click',function() {
          var name = $more.attr('name');
          var value = $more.val();
          $more.remove();
          motor.getAjax('get',name,{num: value});
        });
      }

      function getContent() {
        var action = window.location.search.substr(1) || window.location.href.replace('http://swing/my/dialogs?','');
        if(action in motor) {
          setCurentLinkDown(action);
          loading.show();
          all_count = 0;
          motor.getAjax('get',action);
          $('.' + action).siblings().hide().end().show();
        }else{
          resblock.html('Некорректная ссылка');
        }
      }

      getContent();

    </script>
<?php $this->stop(); ?>
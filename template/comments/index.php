<?php
/**
* @var \System\View $this
*/
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Новые комментарии<?php $this->stop(); ?>
<?php $this->start('description'); ?>Новые комментарии<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .head-nav-message{position:relative;}
    .nav-tabs,.msg-div,.msg-body,.msg-avatar>img{border-radius:4px;}
    .nav-tabs li>a:hover,.nav-active{background-color:#FAFAFA;border-left-color:rgba(0,0,0,.1) !important;border-right-color:rgba(0,0,0,.1) !important;}
    .nav-tabs{background:#EBEBF3;color:#11638c;border:1px solid rgba(0,0,0,.06);margin:0 0 10px 0;padding:0;list-style:none;font-weight:bolder;float:left;}
    .nav-tabs>li{float:left}
    .nav-tabs>li>a{display:block;padding:10px;margin-left:-1px;border:1px solid rgba(0, 0, 0, 0);-webkit-transition:none;-moz-transition:none;transition:none;}
    .nav-tabs li:first-child > a {border-left: none;border-radius: 4px 0 0 4px;margin-left: 0;}
    .nav-tabs li:last-child > a {border-right: none;border-radius: 0 4px 4px 0;}
    #load-mes{float: left;margin: 2px 0 0 2px;display: none;}
    .msg-div{color:#444;border:1px solid #DDDDE5;margin:10px auto;padding: 10px;}
    .msg-div-header,.msg-div-message,.msg-info{display: table-cell;vertical-align:top;}
    .msg-div-header,.msg-info{padding-right:10px;}
    .msg-avatar>img{padding:0;}
    .msg-one{float:left;}
    .msg-div-message>div>span{display:block;float:left;color:#A99898;font-size:0.9em;}
    .msg-body{border:1px solid #DDDDE5;background-color:#FAFAFA;padding:10px;margin-bottom:5px;font-size:1.3em;clear:both;}
    .msg-div-footer{margin-top:10px;}
    .msg-div-footer > img{cursor: pointer};
    .nof-date{width:80px;font-weight:bolder;}
    a.btn:hover{color:#444;}
    .title-comment-out{font-weight: bold;font-size: 16px;font-style: italic;margin-bottom: 20px;}
    span.u-bold{font-weight: bold;color: #747474;}
    .n-0{color: red !important}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<h2>Последние комментарии</h2>
<div class="head-nav-message">
    <div class="nav-message">
        <ul class="nav-tabs links">
            <li><a href="/last_comments?getLastDiaryComment">В ДНЕВНИКАХ</a></li>
            <li><a href="/last_comments?getLastStoryComment">В СТАТЬЯХ</a></li>
            <li><a href="/last_comments?getLastPhotoComment">В ФОТОАЛЬБОМАХ</a></li>
        </ul>
    </div>
    <div id="load-mes"><img src="/img/loading.gif" alt="loading" width="32" height="32" /></div>
    <div style="clear:both"></div>
</div>
<div id="response"></div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script>
  var resblock = $('#response'),loading = $('#load-mes'),links = $('ul.links');
  function getContent() {
    var action = window.location.search.substr(1) || window.location.href.replace('https://swing-kiska.ru/last_comments?','');
    if(action in motor) {
      loading.show();
      motor['c_di'] = motor['c_st'] = motor['c_ph'] = 0;
      motor.getAjax('get',action);
      $('.'+ action).siblings().hide().end().show();
    }else{
      resblock.html('Некорректная ссылка');
    }
  }
  function setCurentUrl() {
    links.find('a').each(function(i,o){
      o.className = (o.href == window.location.href)?'nav-active':'';
    });
  }
  function addButtonMore(action,value) {
    var $more = $('<button/>').addClass('more-info btn btn-success').attr({name:action,value:value}).html('Показать еще').appendTo(resblock);
    $more.on('click',function(){
      var name = $more.attr('name');
      var value = $more.val();
      $more.remove();
      motor.getAjax('get',name,{num: value});
    });
  }
  var motor = {
    send: true,
    c_di: 0,
    c_st: 0,
    c_ph: 0,
    getLastDiaryComment: function(json) {
      motor.setOutputResblock('c_di','getLastDiaryComment',json);
    },
    getLastStoryComment: function(json) {
      motor.setOutputResblock('c_st','getLastStoryComment',json);
    },
    getLastPhotoComment: function(json) {
      motor.setOutputResblock('c_ph','getLastPhotoComment',json);
    },
    setOutputResblock: function(count,button,json) {
      if(motor[count] == 0) {
        resblock.html(json['html']);
      }else{
        resblock.append(json['html']);
      }
      if(json['status'] == 1){
        motor[count] +=30;
        addButtonMore(button,motor[count]);
      }
      setCurentUrl();
    },

    getAjax: function(method,action,params) {
      if(this.send == true) {
        var data = {cntr:'LastComment',action:action};
        if(typeof params == 'object') {
          for(var cur in params) {
            data[cur] = params[cur];
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
  getContent();
</script>
<?php echo render('/partials/scroll-top'); ?>
<?php $this->stop(); ?>
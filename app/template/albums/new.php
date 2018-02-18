<style>
    .head-nav-message{position:relative;}
    .nav-tabs,.msg-div,.msg-body,.msg-avatar>img{border-radius:4px;}
    .nav-tabs li>a:hover,.nav-active{background-color:#FAFAFA;border-left-color:rgba(0,0,0,.1) !important;border-right-color:rgba(0,0,0,.1) !important;}
    .nav-tabs{background:#EBEBF3;color:#11638c;border:1px solid rgba(0,0,0,.06);margin:0 0 10px 0;padding:0;list-style:none;font-weight:bolder;float:left;}
    .nav-tabs>li{float:left}
    .nav-tabs>li>a{display:block;padding:10px;margin-left:-1px;border:1px solid rgba(0, 0, 0, 0);-webkit-transition:none;-moz-transition:none;transition:none;}
    .nav-tabs li:first-child > a {border-left: none;border-radius: 4px 0 0 4px;margin-left: 0;}
    .nav-tabs li:last-child > a {border-right: none;border-radius: 0 4px 4px 0;}
    @-webkit-keyframes progress-bar-stripes {
        from {
            background-position: 40px 0;
        }
        to {
            background-position: 0 0;
        }
    }
    @-o-keyframes progress-bar-stripes {
        from {
            background-position: 40px 0;
        }
        to {
            background-position: 0 0;
        }
    }
    @keyframes progress-bar-stripes {
        from {
            background-position: 40px 0;
        }
        to {
            background-position: 0 0;
        }
    }
    #load-mes{
        height: 20px;
        margin-bottom: 20px;
        overflow: hidden;
        background-color: #f5f5f5;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
        box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
    }
    .progress-bar {
        float: left;
        width: 100%;
        height: 100%;
        font-size: 12px;
        line-height: 20px;
        color: #fff;
        text-align: center;
        /*background-color: #337ab7;*/
        background-color: #5cb85c;
        -webkit-box-shadow: inset 0 -1px 0 rgba(0,0,0,.15);
        box-shadow: inset 0 -1px 0 rgba(0,0,0,.15);
        -webkit-transition: width .6s ease;
        -o-transition: width .6s ease;
        transition: width .6s ease;
    }
    .progress-bar-striped {
        background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image: -o-linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        background-image: linear-gradient(45deg, rgba(255, 255, 255, .15) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .15) 50%, rgba(255, 255, 255, .15) 75%, transparent 75%, transparent);
        -webkit-background-size: 40px 40px;
        background-size: 40px 40px;
    }
    .progress-bar.active {
        -webkit-animation: progress-bar-stripes 2s linear infinite;
        -o-animation: progress-bar-stripes 2s linear infinite;
        animation: progress-bar-stripes 2s linear infinite;
    }
    .albums{
        width: 100%;
    }
    .albums td {
        text-align: center;
        vertical-align: top;
    }
    .albums td a:first-child:hover{
        color: #FFAD33;
    }
    .avatar {
        position: relative;
        margin: 0 auto 5px;
        width: 100px;
        height: 100px;
        border: 1px solid #CCC;
        border-radius: 4px;
        box-shadow: 0 1px 5px #CCC;
        -moz-box-shadow: 0 1px 5px #CCC;
        -webkit-border-shadow: 0 1px 5px #CCC;
    }
    .ar-3 {
        -webkit-filter: grayscale(100%);
        -moz-filter: grayscale(100%);
        -ms-filter: grayscale(100%);
        -o-filter: grayscale(100%);
        filter: grayscale(100%);
        filter: gray; /* IE 6-9 */
        border: 1px solid #000;
        box-shadow: 0 1px 5px #000;
        -moz-box-shadow: 0 1px 5px #000;
        -webkit-border-shadow: 0 1px 5px #000;
    }
    .ap-1{
        border: 1px solid #f00;
        box-shadow: 0 1px 5px #f00;
        -moz-box-shadow: 0 1px 5px #f00;
        -webkit-border-shadow: 0 1px 5px #f00;
    }

</style>

<div class="head-nav-message">
    <div class="nav-message">
        <ul class="nav-tabs links">
            <li><a href="/newalbums?getNewAlbums">НОВЫЕ АЛЬБОМЫ</a></li>
            <li><a href="/newalbums?getNewPhoto">НОВЫЕ ФОТОГРАФИИ</a></li>
        </ul>
    </div>
    <div style="clear:both"></div>
</div>
<div id="response"></div>
<div id="load-mes">
    <div class="progress-bar progress-bar-striped active">
        Подгружаем данные...
    </div>
</div>
<script>

  var resblock = $('#response'),loading = $('#load-mes'),windowSt = $(window),links = $('ul.links'),num = 0,action;

  function getContent() {
    action = window.location.href.replace(/https?:\/\/swing-kiska.ru\/newalbums\?/,'');
    num = 0;
    resblock.html('');
    loading.show();
    motor.getAjax();
  }

  function setCurentUrl() {
    links.find('a').each(function(i,o){
      o.className = (o.href == window.location.href)?'nav-active':'';
    });
  }

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

  var motor = {
    send: true,
    winst: true,
    getAjax: function() {
      if(this.send == true) {
        this.send = this.winst = false;
        $.ajax({
          url: '/ajax/',
          type: 'get',
          dataType: 'json',
          data: {cntr:'ImageInfo',action:action,num:num},
          success: function(json){
            if(json['status'] == 1) {
              num += 80;
              motor.winst = true;
            }
            motor.send = true;
            resblock.append(json['html']);
            setCurentUrl();
            loading.hide();
          }
        });
      }
    }
  };

  getContent();

  windowSt.scroll(function(){
    if(windowSt.scrollTop() + windowSt.height() >= resblock.height() + 400  && motor.winst) {
      loading.show();
      motor.getAjax();
    }
  });
</script>
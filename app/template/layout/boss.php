<style>
    #boss {
        position: fixed;
        width: 70px;
        height: 22px;
        margin: 0;
        padding: 2px;
        top: 5px;
        left: 20px;
        cursor: pointer;
        opacity: 0.5;
        transition-property: opacity;
        transition-duration: 0.2s;
    }

    #boss:hover {
        opacity: 1 !important;
    }

    .hideBackGround {
        background-image: none !important;
    }

    .hideImg {
        visibility: hidden !important;
    }
</style>
<button id="boss" title="Скрыть изображения">скрыть</button>

<script>
  $(document).ready(function () {
    var boss = $('#boss'), fav = $('head').children('link').eq(1);
    if (localStorage.getItem('img') === 'hide') {
      $('div,td').addClass('hideBackGround');
      $('img').addClass('hideImg');
      document.title = 'Википедия - свободная энциклопедия';
      fav.attr('href', 'img/wikipedia.ico');
      boss.text('показать').attr('title', 'Показать изображения').css('opacity', 1);
    }
    boss.on('click', function () {
      if (localStorage.getItem('img') === 'hide') {
        $('div,td').removeClass('hideBackGround');
        $('img').removeClass('hideImg');
        document.title = 'Добро пожаловать!';
        fav.attr('href', 'img/favicon.ico');
        localStorage.removeItem('img');
        boss.text('скрыть').attr('title', 'Cкрыть изображения').css('opacity', 0.5);
      } else {
        $('div,td').addClass('hideBackGround');
        $('img').addClass('hideImg');
        document.title = 'Википедия - свободная энциклопедия';
        fav.attr('href', 'img/wikipedia.ico');
        localStorage.setItem('img', 'hide');
        boss.text('показать').attr('title', 'Показать изображения').css('opacity', 1);
      }
    });
  });
</script>

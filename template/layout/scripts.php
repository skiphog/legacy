<script>
  var arrUsers = {};
  /* right */
  function showTipCom (json) {
    if (json.answer == 1) {
      $('#response-preload-left').
        html('<div><b style="font-style:italic">' + json.title + '</b></div><div>...<br />' + json.com + '</div>');
    } else {
      $('#response-preload-left').html('Комментарий удален');
    }
  }

  var typeFunctions = {
    photocom: function (json) {
      if (json.answer == 1) {
        $('#response-preload-left').
          html('<div><img src="' + json.photo + '" width="250" alt="phomin" /></div>...<br /><div>' + json.msg +
            '</div>');
      } else {
        $('#response-preload-left').html('Фото удалено');
      }
    },
    storycom: showTipCom,
    diarycom: showTipCom
  };
  var idtcom = '', typefunc = '', idrel = 0;
  $(document).ready(function () {
    $('.hover-tip-left').hover(function () {
      idrel = this.dataset.rel;
      typefunc = $(this).parent().attr('id');
      idtcom = typefunc + idrel;
      var offset = $(this).offset();
      pending = setTimeout(function () {
        $('#preload-left').css({'top': offset.top - 10 + 'px', 'left': offset.left - 286 + 'px'}).show();
        if (typeof arrUsers[idtcom] == 'undefined') {
          $.ajax({
            type: 'get',
            url: 'pupusers.php',
            data: typefunc + '=' + idrel,
            dataType: 'json',
            beforeSend: function () {
              $('#response-preload-left').html('<img src="/img/loading.gif" width="32" height="32" alt="loading" />');
            },
            success: function (json) {
              arrUsers[idtcom] = json;
              typeFunctions[typefunc](json);
            }
          });
        } else {
          typeFunctions[typefunc](arrUsers[idtcom]);
        }
      }, 300);
    }, function () {
      clearTimeout(pending);
      $('#preload-left').hide();
    });
  });

  /* --- right */



    <?php if($myrow->isUser()) : ?>
    /*  head  */
    var pending = 0,
      idU = 0,
      outputU = '',
      popupWin;

    function openPrivate (link, id) {
      popupWin = window.open(link, 'win' + id,
        'toolbar=0,location=0,directories=0,status=1,menubar=0,scrollbars=yes,resizable=yes,copyhistory=1,width=700,height=600,top=0');
      if (popupWin) {
        popupWin.focus();
      }
      return false;
    }

    function showTipUsers (u, rpu) {
      outputU = u.real_status + u.login + '<br /><img src="' + u.pic1 +
        '" width="70" height="70" alt="avka" /><br />'
        + u.gender + '<br />' + u.fname + '<br />' + u.birthday + '<br />' + u.city;
      rpu.css('background', u.vip_time).html(outputU);
    }

    function spoiler (e) {
      $(e).toggleClass('folder unfolder').next('div').toggle();
    }

    function getLenta (e) {
      $('.lst').hide();
      $(e).next().show();
    }

    function dblClickLenta (id) {
      location.assign('/id' + id);
    }

    $(document).ready(function () {
      var rpu = $('#response-preload-users');
      $('body').on({
        mouseenter: function () {
          idU = this.href.replace('https://swing-kiska.ru/id', '');
          var offset = $(this).offset();
          pending = setTimeout(function () {
            rpu.parent().css({'top': offset.top + 45 + 'px', 'left': offset.left - 57 + 'px'}).show();
            if (typeof arrUsers[idU] === 'undefined') {
              $.ajax({
                type: 'get',
                url: 'popis.php',
                data: {id: idU, g:<?php echo $myrow->real_status; ?>},
                dataType: 'json',
                beforeSend: function () {
                  rpu.html('<img src="/img/loading.gif" width="32" height="32" alt="loadin" />');
                },
                success: function (json) {
                  arrUsers[json.id] = json;
                  showTipUsers(json, rpu);
                }
              });
            } else {
              showTipUsers(arrUsers[idU], rpu);
            }
          }, 300);
        },
        mouseleave: function () {
          clearTimeout(pending);
          rpu.css('background', '#F0F0F0').parent().hide();
        }
      }, 'a.hover-tip');
        <?php if(!$myrow->isMobile()) : ?>
      $('#mordalenta').on({
        mouseenter: function () {
          $(this).next().show();
        },
        mouseleave: function (e) {
          if (e.relatedTarget === null || e.relatedTarget.className !== 'lenta-tip') {
            $('.lst').hide();
          } else {
            $(this).next().bind('mouseleave', function () {
              $('.lst').hide();
            });
          }
        }
      }, '.tiplen');
        <?php endif;?>
    });
    /*   --- head  */


    /* lenta */

    function displayblock (id) {
      var ids = $('#ids_' + id);
      var idp = $('#idp_' + id);
      ids.show();
      idp.mouseout(function () {
        ids.hide();
      });
      ids.mouseover(function () {
        $(this).show();
      });
      ids.mouseout(function () {
        $(this).hide();
      });
    }

    function validate_lenta () {
      if (document.lents.textlenta.value === '') {
        alert('Нельзя отправить пустое сообщение');
        return false;
      }
    }

    $(function () {
      $('a.show_popup').click(function () {
        $('div.' + $(this).attr('rel')).fadeIn(300);
        $('body').append('<div id="overlay"></div>');
        $('#overlay').show().css({'filter': 'alpha(opacity=80)'});
        return false;
      });
      $('a.close').click(function () {
        $(this).parent().fadeOut(100);
        $('#overlay').remove('#overlay');
        return false;
      });
    });

    /* --- lenta */

    /* smile */
    $('#m-smile').on('click', function () {
      var smile = $(this).parent('div').prev('div');
      $.getJSON('/ajax/', {cntr: 'Succour', action: 'getSmiles'}, function (json) {
        smile.html('<strong>' + json['title'] + '</strong>:<br><em>' + json['text'] + '</em>');
      });
    });
    /* --- smile */

    /* private */
  var title = document.title;

  function setTitleCount(c) {
    document.title = (c === 0) ? title : '('+ c + ') ' + title;
  }
  var infoPrivat = $("#info-privat"), count_mess = $('.count-mes');

  function show_privat() {
    $.getJSON('/private').success(function(json) {
      count_mess.text(json['count'] || '');

      if(json['message'] !== '') {
        infoPrivat.html(json['message']).slideDown();
      }

      setTitleCount(json['count']);
      pr = setTimeout(show_privat,30000);
    }).error(function() {
      count_mess.text('');
    });
  }
  var pr = setTimeout(show_privat,30000);

  function closeTipInfo(){infoPrivat.slideUp();}
    /* --- private*/
  /*console.log('%c       ', 'font-size: 100px; background: url(http://cdn.nyanit.com/nyan2.gif) no-repeat;');*/
    <?php endif; ?>
</script>



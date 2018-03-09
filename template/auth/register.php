<?php
/**
* @var \System\View $this
*/

$dbh = db();
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Регистрация<?php $this->stop(); ?>
<?php $this->start('description'); ?>Регистрация<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<link rel="stylesheet" href="/js/kladr/jquery.kladr.min.css">
<style type="text/css">
    .messagebox {
        width: 100px;
        border: 1px solid #c93;
        background: #ffc;
        padding: 3px;
    }

    .messagebox2 {
        width: auto;
        border: 1px solid #c93;
        background: #ffc;
        padding: 3px;
    }

    #validEmail {
        margin-top: 4px;
        margin-left: 9px;
        position: absolute;
        width: 16px;
        height: 16px;
    }

    form[name=contact_form] label {
        font-weight: bold
    }

    td.purposes ul {
        list-style: none;
        margin: 0;
        padding: 0
    }

    td.purposes li {
        margin-bottom: 5px;
    }

    td.purposes label {
        user-select: none;
        font-weight: normal;
        cursor: pointer
    }

    .table-registration {
    }

    .table-registration tr {
        background-color: #ebebf3;
    }

    .table-registration td {
        padding: 10px
    }

    .table-registration td:first-child {
        width: 200px
    }

    .reg-errors{font-size: 16px;font-weight: bold; color: red}

    .loading{position:relative;cursor:default;text-shadow:none !important;color:transparent !important;opacity:1;pointer-events:auto;-webkit-transition:all 0s linear,opacity .1s ease;transition:all 0s linear,opacity .1s ease}
    .loading:before{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;border-radius:500rem;border:.2em solid rgba(0,0,0,.15)}
    .loading:after{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;-webkit-animation:button-spin .6s linear;animation:button-spin .6s linear;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;border-radius:500rem;border:.2em solid transparent;border-top-color:#FFF;box-shadow:0 0 0 1px transparent}
    @-webkit-keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
    @keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<form id="reg-form" name="contact_form" method="post" action="/regsave">
    <table class="table-registration" border=0 width=100%>
        <tr>
            <td colspan=2 align=center bgcolor=#e1eaff style="height: 30px;"><h2>Регистрация</h2></td>
        </tr>

        <tr>
            <td><label for="login">Логин</label>:<br>(Никнейм Вашей анкеты)</td>
            <td>
                <div>
                    Логин должен начинаться с буквы и заканчиваться буквой или цифрой (от 3 до 25 знаков)<br>
                    <input id="login" name="login" type="text" class="text" maxlength="25" required>
                    <span id="msgbox" style="display:none"></span>
                </div>
            </td>
        </tr>

        <tr>
            <td><label for="password">Ваш пароль</label>:<br>(Обязательно)</td>
            <td>
                Введите пароль<br>
                <input id="password" type="password" class="text" style="width: 300px" name="password" required></td>
        </tr>

        <tr>
            <td><label for="email">E-mail</label>:<br>(Обязательно)</td>
            <td>
                Введите реальный e-mail он требуется для подтверждения регистрации<br>
                <input name="email" type="email" class="text" id="email" style="width: 300px" required>
            </td>
        </tr>

        <tr>
            <td><label for="fname">Имя</label>:<br>(Обязательно)</td>
            <td>
                Ваше имя<br>
                <input id="fname" type="text" class="text" style="width: 300px" name="fname" required>
            </td>
        </tr>

        <tr>
            <td><label for="gender">Пол:</label><br>(Обязательно)
            </td>
            <td>
                <select name="gender" id="gender">
                    <?php foreach (\App\Arrays\Genders::$gender as $key => $gender) : ?>
                        <option value="<?php echo $key; ?>"><?php echo $gender; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <td><label for="country">Страна</label>:<br>(Обязательно)</td>
            <td>
                <select name="country" id="country">
                    <option value="127">Россия</option>
                    <?php foreach (\App\Arrays\Country::$array as $key => $value) : ?>
                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>

        <tr>
            <td><label for="kladrs">Город</label>:<br>(Обязательно)</td>
            <td>
                <input id="kladrs" style="width: 200px;" type="text" class="text" name="city" value="" autocomplete="off" onkeydown="if(event.keyCode===13){return false;}">
            </td>
        </tr>

        <tr>
            <td><label>Дата рождения</label>:<br>(Обязательно)</td>
            <td>
                <label>
                    год
                    <select name="birthday[]">
                        <option selected value="0"></option>
                        <?php for ($ii = (int)(new DateTime())->modify('-18 year')->format('Y'), $ll = $ii - 43; $ii > $ll; $ii--) { ?>
                            <option value="<?php echo $ii; ?>"> <?php echo $ii; ?></option>
                        <?php } ?>
                    </select>
                </label>

                <label>
                    месяц
                    <select name="birthday[]">
                        <option selected value="0"></option>
                        <?php foreach (range(1, 12) as $value) : ?>
                            <option value="<?php echo $value; ?>"><?php printf('%02d', $value); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    день
                    <select name="birthday[]">
                        <option selected value="0"></option>
                        <?php foreach (range(1, 31) as $value) : ?>
                            <option value="<?php echo $value; ?>"><?php printf('%02d', $value); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </td>
        </tr>

        <tr>
            <td colspan=2 align=center bgcolor=#e1eaff style="height: 30px;"><h2>Информация об объекте поиска</h2></td>
        </tr>

        <tr>
            <td><b>Цель знакомства</b>:<br>(Обязательно)</td>
            <td class="purposes purposes-js">
                <ul>
                    <?php foreach (\App\Arrays\Purposes::$array as $key => $value) : ?>
                        <li>
                            <label><input type="checkbox" name="purposes[]" value="<?php echo $key; ?>"><?php echo $value; ?>
                            </label></li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>

        <tr>
            <td><b>Ищу</b>:<br>(Обязательно)</td>
            <td class="purposes gender-js">
                <ul>
                    <?php foreach (\App\Arrays\Genders::$sgender as $key => $value) : ?>
                        <li>
                            <label><input type="checkbox" name="sgender[]" value="<?php echo $key; ?>"><?php echo $value; ?>
                            </label></li>
                    <?php endforeach; ?>
                </ul>
            </td>
        </tr>

        <tr>
            <td colspan=2 style="padding-left: 5px; padding-top: 5px; padding-bottom: 5px;">
                <label>
                    <input type="hidden" name="personal" value="0">
                    <input type="checkbox" name="personal" value="1" required>
                    <span style="font-size: 16px">
                Мне больше 18 лет, я ознакомлен(a) c <a href="/viewdiary_30" target="_blank">Правилами сайта</a>
                и я даю согласие на <a href="/personal" target="_blank">обработку персональных данных</a>
            </span>
                </label>
            </td>
        </tr>
        <tr>
            <td colspan=2>
                Сколько будет 3 + 4 ?
                <label for="logtext">Введите ответ</label>:<br>
                <input type="number" id="logtext" name="code" required>
            </td>
        </tr>

        <tr>
            <td colspan=2 bgcolor=#e1eaff height=30 valign=center>
                <button class="btn btn-success" type="submit">Зарегистрироваться</button>
            </td>
        </tr>
    </table>
</form>

<div id="reg-errors" class="reg-errors"></div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script src="/js/kladr/jquery.kladr.https.min.js"></script>
<script>
  $('#kladrs').
    kladr({
      token: '51dfe5d42fb2b43e3300006e',
      key: '86a2c2a06f1b2451a87d05512cc2c3edfdf41969',
      type: $.kladr.type.city
    });
  $('#country').
    change(function () {
      if ($(this).val() != 127) {$('#kladr_autocomplete').hide();} else {
        $('#kladr_autocomplete').show();
      }
    });
</script>
<script>
  function validateObj (o) {
    for (var i = 0, c = o.length; i < c; i++) {
      if (o[i].checked === true) {
        return true;
      }
    }
    return false;
  }

  function validate_form (form) {

    if (form.login.value === '') {
      alert('Пожалуйста, введите Логин.');
      return false;
    }

    if (form.password.value.length < 6) {
      alert('Пожалуйста, введите пароль не менее 6-ти символов.');
      return false;
    }

    if (form.email.value.length < 3) {
      alert('Не правильно введен E-mail.');
      return false;
    }

    if (form.fname.value === '') {
      alert('Пожалуйста, введите Ваше имя.');
      return false;
    }

    if (form.gender.selectedIndex === 0) {
      alert('Пожалуйста, укажите Ваш пол.');
      return false;
    }

    if (form.country.value === '') {
      alert('Пожалуйста, укажите Страну.');
      return false;
    }

    if (form.city.value === '') {
      alert('Пожалуйста, укажите Город.');
      return false;
    }

    if (validateObj(form.querySelectorAll('.purposes-js input[type=checkbox]')) === false) {
      alert('Пожалуйста, выберете "Цель знакомства"');
      return false;
    }

    if (validateObj(form.querySelectorAll('.gender-js input[type=checkbox]')) === false) {
      alert('Пожалуйста, выберете "Я ищу"');
      return false;
    }

    if (form.code.value === '') {
      alert('Пожалуйста, введите ответ.');
      return false;
    }

    return true;
  }

  $(document).ready(function () {

    var errorsBox = $('#reg-errors');

    $('#login').blur(function () {
      $('#msgbox').removeClass().addClass('messagebox').text('Проверка...').fadeIn('slow');

      $.getJSON('user_availability.php', {user_name: $(this).val()}, function (json) {
        $('#msgbox').fadeTo(200, 0.1, function () {
          var color = json.status ? 'green' : 'red';
          $(this).
            html('<span style="color:' + color + ';font-weight:bolder">' + json.html + '</span>').
            removeClass().
            addClass('messagebox2').
            fadeTo(900, 1);
        });
      });
    });

    $('#reg-form').on('submit', function (e) {
      e.preventDefault();
      errorsBox.css('opacity', '0');

      if (!validate_form(this)) {
        return;
      }

      var form = $(this);

      $.ajax({
        url: form.attr('action'),
        type: 'POST',
        dataType: 'json',
        data: form.serialize(),
        beforeSend: function () {
          form.find('button[type=submit]').addClass('loading');
        },
        success: function (data) {
          if (data.status && data.status === 1) {
            document.location.assign('/login');
          }
        },
        error: function (jqXHR) {
          if (jqXHR.status === 422) {
            var errors = jqXHR['responseJSON']['errors'];
            var html = [];

            for (var index in errors) {
              if (errors.hasOwnProperty(index)) {
                html.push('<li>' + errors[index] + '</li>');
              }
            }
            errorsBox.html('<ul>' + html.join('') + '</ul>').css('opacity', '1');
            form.find('button[type=submit]').removeClass('loading');
          }
        }
      });
    });

  });
</script>
<?php $this->stop(); ?>

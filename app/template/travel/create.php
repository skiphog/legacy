<?php
/**
 * @var \Swing\System\Controller $this
 */
require __PATH__ . '/ajax/system/ArrayTravel.php';

// Получаем страны

$country = $this->dbh->query('select id, title from travel_country order by sort, title')->fetchAll();

?>
<link rel="stylesheet" href="/js/jtime/jquery.datetimepicker.min.css">
<style>
    .loading{position:relative;cursor:default;text-shadow:none !important;color:transparent !important;opacity:1;pointer-events:auto;-webkit-transition:all 0s linear,opacity .1s ease;transition:all 0s linear,opacity .1s ease}
    .loading:before{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;border-radius:500rem;border:.2em solid rgba(0,0,0,.15)}
    .loading:after{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;-webkit-animation:button-spin .6s linear;animation:button-spin .6s linear;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;border-radius:500rem;border:.2em solid transparent;border-top-color:#FFF;box-shadow:0 0 0 1px transparent}
    @-webkit-keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
    @keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
    .e-input{font:14px Arial,Tahoma,Verdana,sans-serif;padding:4px;border:1px solid #ccc;border-radius:2px}
    .e-input:focus{border-color:#99baca;background:#f5fbfe}
    .e-input.date{width:100px}
    #travel{padding:10px;}
    .travel-select{margin-bottom:10px;}
    .travel-select>label,.travel-sgender>label{display:inline-block;color:#3c3c3c;user-select:none;}
    .travel-select>label{font-weight:bold;}
    .travel-select>select{padding:3px;}
    select+span{visibility:hidden;}
    select[disabled]+span{display:inline-block;vertical-align:bottom;visibility:visible !important;width:25px;height:25px;background:url("/img/loading.gif") no-repeat center;background-size:25px 25px;}
</style>

<h1>Добавить объявление в путешествиях</h1>
<form id="travel" action="" method="post">
    <div class="travel-select">
        <label>
            Дата начала
            <br>
            <input id="s-date" class="e-input date" type="text" name="date_start" title="Дата начала" placeholder="Выберите" autocomplete="off" value="<?= date('d.m.Y'); ?>">
        </label>
        —
        <label>
            Дата окончания
            <br>
            <input class="e-input date" type="text" name="date_end" title="Дата окончания" placeholder="Выберите" autocomplete="off" value="<?= date('d.m.Y'); ?>">
        </label>
    </div>

    <div class="travel-select">
        <label for="country_id">Страна</label>
        <br>
        <select name="country_id" class="sel" id="country_id">
            <option value="0">Не выбрано</option>
            <?php foreach ($country as $item) {?>
                <option value="<?= $item['id']; ?>"><?= $item['title']; ?></option>
            <?php }?>
        </select>
        <span></span>
    </div>

    <div class="travel-select">
        <label for="city_id">Город</label>
        <br>
        <select name="city_id" class="sel" id="city_id">
            <option value="0" selected>Не выбрано</option>
        </select>
        <span></span>
    </div>

    <div class="travel-select">
        <label><?= ArrayTravel::$case['search'][$this->myrow->gender]; ?></label>
        <div class="travel-sgender">
            <?php foreach (\Swing\Arrays\Genders::$sgender as $key => $value) :?>
                <label><input type="checkbox" name="sgender[]" value="<?= $key; ?>" checked> <?= $value; ?></label><br>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="travel-select">
        <label for="payment">Финансы</label>
        <br>
        <select name="payment" id="payment">
            <?php foreach ((array)ArrayTravel::$payment as $key => $value) {?>
                <option value="<?= $key; ?>"><?= $value; ?></option>
            <?php }?>
        </select>
    </div>

    <div class="travel-select">
        <label for="transport">Транспорт</label>
        <br>
        <select name="transport" id="transport">
            <option value="0" selected>Не выбрано</option>
            <?php foreach ((array)ArrayTravel::$transport as $key => $value) {?>
                <option value="<?= $key; ?>"><?= $value; ?></option>
            <?php }?>
        </select>
    </div>

    <div class="travel-select">
        <div class="travel-sgender">
            <label><input type="checkbox" name="is_children" value="1"> <?= ArrayTravel::$case['child'][$this->myrow->gender]; ?> с детьми</label>
        </div>
    </div>

    <div class="travel-select">
        <label for="description">Описание</label>
        <br>
        <textarea name="description" id="description" cols="50" rows="5"></textarea>
    </div>

    <div id="t-errors" class="red"></div>

    <div class="travel-select">
        <input id="button" type="submit" class="btn btn-primary" value="Добавить">
    </div>
</form>


<script src="/js/jtime/jquery.datetimepicker.full.min.js"></script>
<script>
    var city=$('#city_id'),sel=$('.sel'),but=$('#button'),st=true,tmpl_c='<option value="0" selected>Не выбрано</option>';

    $('.date').datetimepicker({
        dayOfWeekStart:1,
        minDate:0,
        format:'d.m.Y',
        closeOnWithoutClick:false,
        startDate:new Date(),
        timepicker:false,
        <?php if(!empty($_SESSION['is_mobile'])) {echo 'inline:true,';} ?>
    });
    $.datetimepicker.setLocale('ru');

    $('#country_id').on('change', function () {
        var id = parseInt($(this).val()) || 0;

        if(id === 0) {
            return city.html(tmpl_c);
        }

        sel.attr('disabled','disabled');

        $.getJSON('/ajax?cntr=Travel&action=getCity&id=' + id, function (j) {
            if(j.length === 0) {
                return city.html(tmpl_c);
            }
            var c = [];

            for (var a in j) {
                if(j.hasOwnProperty(a)) {
                    c.push('<option value="' + j[a].id + '">'+ j[a].title +'</option>')
                }
            }
            city.html(tmpl_c + c.join(' '));
            sel.removeAttr('disabled');
        });
    });

    $('#travel').on('submit', function (e) {
        e.preventDefault();
        var data=$(this).serialize();
        if(!st) {return;}
        $.ajax({
            url:'/ajax/',
            type:'post',
            data: data + '&cntr=Travels&action=addTravel',
            dataType:'json',
            beforeSend:function () {
                st=false;
                but.addClass('loading')
            },
            success: function (j) {
                if(!j['status']) {
                    var t = [];
                    $.each(j['html'],function (k,v) {
                        t.push('<li>' +  v + '</li>');
                    });
                    $('#t-errors').html('<ul>'+ t.join(' ') +'</ul>');
                    but.removeClass('loading');
                    return;
                }
                window.location.replace('/travel');
            },
            error:function () {
                alert("Forbidden 403");
            },
            complete:function () {
                st=true;
                but.removeClass('loading');
            }
        });

        // вадидация



    });


</script>



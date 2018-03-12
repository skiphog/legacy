<?php
/**
* @var \System\View $this
*/

$dbh = db();

$sql = 'select e.id,e.id_user,e.title,e.img,e.begin_date,e.end_date,e.club,e.v_count,e.city,e.address,e.`status`,
  u.login,u.gender    
  from `events` e
  join users u on u.id = e.id_user
  where e.end_date > now() 
  and e.`status` <> 0 
order by e.`status` desc ,e.begin_date asc';

$sth = $dbh->query($sql);

$events = [];

while ($row = $sth->fetch()) {
    $row['ts_b'] = strtotime($row['begin_date']);
    $row['ts_e'] = strtotime($row['end_date']);
    $events[] = $row;
}

$moderation = [
    1 => 'Активен',
    2 => 'Отклонен',
    3 => 'Ожидает модерацию'
];

?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Модерация анонсов<?php $this->stop(); ?>
<?php $this->start('description'); ?>Модерация анонсов вечеринок<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    #content hr{border-style:groove;margin:20px 0}
    .t-breadcrumbs{padding:5px;font-style:italic;margin-bottom:10px}
    .events,.e-ava{border:1px solid #ccc;border-radius:2px}
    .events{margin-bottom:20px;background:#ebebf3;}
    .e-date,.e-addr,.knops{margin-bottom:10px}
    .e-sity,.events h3,.e-date-head{font-weight:700}
    .events h3{font-size:20px;margin: 5px 0 10px;text-decoration:underline}
    .e-addr{font-size:16px}
    .e-date-head{font-size:18px;color:#5c6583}
    .e-date-time{color:#A99898}
    .e-table{display:table-cell;position:relative;vertical-align:top;padding-right:20px;}
    .e-table-first{text-align:center}
    .e-ava{display:block;width:150px;height:auto;padding:0;vertical-align:middle}
    .e-mod{text-transform:uppercase;border-radius:2px;padding:2px;color:#fff;}
    .e-mod-1{background: #008000}
    .e-mod-2{background: #ff0000}
    .e-mod-3{background: #ffff00;color:#444}
    .e-mod-4{background: #CCC;}
    .e-mod-head{font-size:10px}
    .del-comm{color:#CCC;float:right;cursor:pointer;padding:2px;}
    .del-comm:hover{color:#D21B1B}
    .div-del{background-color: #EBEBF3}
    .div-del > span {cursor: pointer;color: green}
    .del-mask{background: url('/img/loader_button.gif')}
    button[name="01"],button[name="12"]{display:none}
    .loading{position:relative;cursor:default;text-shadow:none !important;color:transparent !important;opacity:1;pointer-events:auto;-webkit-transition:all 0s linear,opacity .1s ease;transition:all 0s linear,opacity .1s ease}
    .loading:before{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;border-radius:500rem;border:.2em solid rgba(0,0,0,.15)}
    .loading:after{position:absolute;content:'';top:40%;left:50%;margin:-.64285714em 0 0 -.64285714em;width:1.28571429em;height:1.28571429em;-webkit-animation:button-spin .6s linear;animation:button-spin .6s linear;-webkit-animation-iteration-count:infinite;animation-iteration-count:infinite;border-radius:500rem;border:.2em solid transparent;border-top-color:#FFF;box-shadow:0 0 0 1px transparent}
    @-webkit-keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
    @keyframes "button-spin"{from{-webkit-transform:rotate(0deg);transform:rotate(0deg);}to{-webkit-transform:rotate(360deg);transform:rotate(360deg);}}
    .btn-group{margin: 10px 0}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<div class="t-breadcrumbs">
    <!--suppress HtmlUnknownTarget -->
    <a href="/parties" target="_blank">Посмотреть анонсы на сайте</a>
</div>
<h1>Модерация свинг вечеринок</h1>
<hr>
<div id="events">
    <?php foreach ($events as $event) {?>
        <div class="events border-box" data-value="<?=$event['id']; ?>">
            <span class="del-comm" title="Удалить встречу">Удалить нафиг</span>
            <div class="e-table e-table-first">
                <img class="e-ava" src="/<?= $event['img']; ?>" alt="eava">
            </div>
            <div class="e-table">
                <div class="e-mod-head">
                    <?php if($_SERVER['REQUEST_TIME'] < $event['ts_e']) {?>
                        <span class="e-mod e-mod-<?=$event['status']; ?>"><?= $moderation[$event['status']]?></span>
                    <?php }else{?>
                        <span class="e-mod e-mod-4">Завершен</span>
                    <?php }?>
                </div>
                <h3><a href="/event_<?= $event['id']; ?>" target="_blank"><?= html($event['title']); ?></a></h3>
                <div class="e-date">
                    <span class="e-date-head"><?= date('d.m.Y',$event['ts_b']); ?> в <?= date('H:i',$event['ts_b']); ?></span>
                    <i class="e-date-time">
                        <?php if($_SERVER['REQUEST_TIME'] < $event['ts_b']) {?>
                            начнется через <?php echo (new \App\Components\SwingDate($event['begin_date']))->getHumansShort(); ?>
                            (продолжительность &mdash; <?php echo (new \App\Components\SwingDate(date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'] + ($event['ts_e'] - $event['ts_b']))))->getHumansShort(); ?>)
                        <?php }elseif($_SERVER['REQUEST_TIME'] > $event['ts_e']) {?>
                            закончилась <?php echo (new \App\Components\SwingDate($event['end_date']))->getHumans(); ?>
                        <?php }else{?>
                            началась
                        <?php }?>
                    </i>
                </div>
                <div class="e-addr">
                    <div>
                        <span class="e-sity"><?= html($event['city']); ?></span>
                        <span>(<?= html($event['club']); ?>)</span>
                    </div>
                    <div><?= html($event['address']); ?></div>
                </div>

                <div>
                    Автор:
                    <a class="hover-tip" href="/id<?= $event['id_user']; ?>" target="_blank">
                        <img src="/img/info_small_<?= $event['gender']; ?>.png" width="15" height="14" alt="gender"> <?= $event['login']; ?>&nbsp;
                    </a>
                </div>
                <hr>

            </div>
            <div class="btn-group">
                <button class="btn btn-success" value="1">Одобрить</button>
                <button class="btn btn-default" value="3">Модерация</button>
                <button class="btn btn-danger" value="2">Отклонить</button>
            </div>

        </div>
    <?php }?>
</div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script>

  /* все переделать!!!*/
  var events = $('#events');

  events.on("click", ".del-comm", function() {
    var e = $(this).parent('div');
    var $append = $("<div/>").addClass("events border-box div-del del-mask").html("&nbsp;");
    e.after($append).hide();
    $.post("/ajax/", {
      cntr: "Party",
      action: "delParty",
      pdel: e.data("value")
    }, function() {
      $append.removeClass("del-mask").html('Встреча удалена. <span data-comm="' + e.data("value") + '">Восстановить?</span>');
    });
  });

  events.on("click", ".div-del > span", function() {
    var comm = $(this);
    var $div = comm.parent("div").prev().show().addClass("del-mask");
    comm.parent("div").remove();
    $.post("/ajax/", {
      cntr: "Party",
      action: "recParty",
      dataType:'json',
      prec: comm.data("comm")
    }, function(json) {
      $div.removeClass("del-mask");
      if(json['status'] !== undefined && json['status'] === 3) {
        $div.find('.e-mod').addClass('e-mod-3').html('На модерации');
      }
    });
  });

  var sende=true;

  events.on('click','.btn',function() {
    if(!sende) {
      return;
    }
    sende=false;
    var b = $(this);
    b.addClass('loading');
    var d = b.parent().parent();
    $.post("/ajax/", {
      cntr: "Moderator",
      action: "setEventsStatus",
      dataType:'json',
      id: d.data("value"),
      status: b.val()
    }, function(json) {
      if(json['status'] != undefined) {
        d.find('.e-mod').attr('class','e-mod e-mod-' + json['status']).html(json['mod']);
        sende=true;
        b.removeClass('loading')
      }
    });

  });
</script>
<?php $this->stop(); ?>
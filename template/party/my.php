<?php
/**
 * @var \System\View $this
 */

$dbh = db();
$myrow = auth();

$sql = 'select id, title, img, `status`,begin_date, end_date, club, v_count,city,address 
  from `events` 
  where id_user = ' . (int)$myrow->id . ' 
  and `status` <> 0 
order by `status` desc,begin_date desc limit 20';
$sth = $dbh->query($sql);

$events = [];

while ($row = $sth->fetch()) {
    $row['ts_b'] = strtotime($row['begin_date']);
    $row['ts_e'] = strtotime($row['end_date']);
    $events[] = $row;
}

$moderation = [
    1 => ['Активен', ''],
    2 => ['Отклонен', '&mdash; пожалуйста, внесите изменения в анонс или свяжитесь с администратором'],
    3 => ['На модерации', '&mdash; пожалуйста, дождитесь модерации анонса']
];?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Мои вечеринки<?php $this->stop(); ?>
<?php $this->start('description'); ?>Вечеринки пользователя<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .events,.e-ava{border:1px solid #ccc;border-radius:2px}
    .events{margin-bottom:20px;padding:5px}
    .events h2{margin: 5px 0 10px}
    .e-date{margin-bottom:5px}
    .e-sity,.events h2,.e-date-head{font-weight:700}
    .events h2{font-size:20px}
    .e-date-head{color:#5c6583}
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
    .div-del > span,.modal h2 {cursor: pointer;color: green}
    .del-mask{background: url('/img/loader_button.gif')}
    .themodal-lock{overflow: hidden;}
    .themodal-overlay{position: fixed;bottom: 0;left: 0;top: 0;right: 0;z-index: 100;overflow: auto;-webkit-overflow-scrolling: touch;}
    .themodal-overlay > *{-webkit-transform: translateZ(0px);}
    .themodal-overlay{background: rgba(0, 0, 0, 0.5);filter: progid:DXImageTransform.Microsoft.gradient(startColorstr = #7F000000, endColorstr = #7F000000);zoom: 1;}
    .modal{background: #fff;width: 800px;margin: 20px auto;padding: 20px;border-radius:3px;}
    .modal a.close{float: right;font-size: 30px;}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
    <h2>Мои встречи</h2>

<?php if(!empty($_SESSION['event_add'])) : ?>
    <?php unset($_SESSION['event_add']); ?>
    <div class="modal" id="e-modal" style="display:none">
        <a href="#" class="close" onclick="return closeModal();">x</a>
        <div style="clear: both"></div>
        <h2>Анонс добавлен на модерацию</h2>
        <h3>Вы можете прорекламировать вашу вечеринку на главной странице или на всех страницах сайта.</h3>
        <label>Просмотры</label>
        <ul>
            <li>Баннеры на главной странице: ~ <strong>17 000</strong> просмотров в сутки</li>
            <li>Сквозной баннер в левом меню: ~ <strong>160 000</strong> просмотров в сутки</li>
        </ul>
        <div>
            <img src="/imgart/201705/6dfbaba7cd873d061bae0186eb3269b0.jpg" width="640" height="522" alt="info">
        </div>
        <h3>Реклама вашей группы</h3>
        <p>
            Вы можеет привлечь внимание пользователей к вашей группе на сайте.
            <br>
            Ее можно закрепить на первых места следующих страниц:
        </p>
        <h4>&mdash; в блоке "Группы"﻿ в разделе <a href="https://swing-kiska.ru/newugthreads_1" target="_blank">Интересные группы</a></h4>
        <label>~ <strong>60 000</strong> просмотров в месяц</label>
        <div>
            <img src="/imgart/201705/f6fbc8052d0240a05bf01de9b178c4ff.jpg" width="640" height="403" alt="groups">
        </div>
        <h4>&mdash; на первом месте в общем списке <a href="https://swing-kiska.ru/ugrouplist_1" target="_blank">всех Групп</a></h4>
        <label>~ <strong>3 000</strong> просмотров в месяц</label>
        <div>
            <img src="/imgart/201705/e5f7dd0b2bac368e00e036b30dd49220.jpg" width="640" height="349" alt="groups">
        </div>
        <p>Стоимость закрепления группы &mdash; <strong>1500</strong> руб/месяц</p>
        <p>По всем вопросам пишите в личку <a href="/id1" target="_blank">swing-kiska</a></p>
    </div>
    <script src="/js/modal.js"></script>
    <script>
      $('#e-modal').modal().open();
      function closeModal() {
        $.modal().close();
        return false;
      }
    </script>
<?php endif; ?>

<?php if(empty($events)) {?>
    <p>Вы не добавили еще ни один анонс.</p>
<?php }else {?>
    <div id="events">
        <?php foreach ($events as $event) {?>
            <div class="events">
                <span class="del-comm" title="Удалить встречу" data-value="<?=$event['id']; ?>">Удалить встречу</span>
                <div class="e-table e-table-first">
                    <img class="e-ava" src="/<?= $event['img']; ?>" alt="eava">
                    <a href="/event/<?=$event['id']; ?>/edit">Редактировать встречу</a>
                </div>
                <div class="e-table">
                    <div class="e-mod-head">
                        <?php if($_SERVER['REQUEST_TIME'] < $event['ts_e']) {?>
                            <span class="e-mod e-mod-<?=$event['status']; ?>"><?= $moderation[$event['status']][0]?></span>
                            <span><?= $moderation[$event['status']][1]?></span>
                        <?php }else{?>
                            <span class="e-mod e-mod-4">Завершен</span>
                        <?php }?>
                    </div>
                    <h2><?= html($event['title']); ?></h2>
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
                    <div>
                        <span class="e-sity"><?= html($event['city']); ?></span>
                        <span>(<?= html($event['club']); ?>)</span>
                    </div>
                    <div><?= html($event['address']); ?></div>
                    <a href="/event_<?=$event['id']; ?>">Посмотреть на сайте</a>
                </div>
            </div>
        <?php }?>
    </div>
<?php }?>
    <a href="/event/create" class="btn btn-default">Добавить анонс встречи</a>
    <br>
    <br>
    <a href="https://swing-kiska.ru/viewdiary_260">Реклама на сайте</a>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script>
  var events = $('#events');

  events.on("click", ".del-comm", function() {
    var comm = $(this);
    var $append = $("<div/>").addClass("events border-box div-del del-mask").html("&nbsp;");
    comm.parent("div").after($append).hide();
    $.post("/ajax/", {
      cntr: "Party",
      action: "delParty",
      pdel: comm.data("value")
    }, function() {
      $append.removeClass("del-mask").html('Встреча удалена. <span data-comm="' + comm.data("value") + '">Восстановить?</span>');
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
</script>
<?php $this->stop(); ?>
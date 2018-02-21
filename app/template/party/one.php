<?php
/**
 * @var \Swing\System\Controller $this
 */

$id_event = $this->request->get('id');

$sql = 'select a.city, a.title, a.`text`, a.img, a.`status`, a.timer, a.maper, 
      a.address, a.begin_date, a.end_date, a.club, a.coords, a.v_count, a.price, a.site, a.email, a.vkontakte, a.tel,
      u.id u_id, u.login u_login,u.pic1 u_ava,u.gender u_gender,u.city u_city,u.fname u_name
      from `events` a
      join users u on u.id = a.id_user
      where a.id = ' . $id_event . ' and a.`status` <> 0 
    limit 1';

$sth = $dbh->query($sql);

if (!$sth->rowCount()) {
    throw new \Swing\Exceptions\NotFoundException('Анонса не существует или удален');
}

$event = $sth->fetch();

/** @noinspection NotOptimalIfConditionsInspection */
if ((int)$event['status'] !== 1 && $myrow->id !== $event['u_id'] && !$myrow->isModerator()) {
    throw new \Swing\Exceptions\ForbiddenException('Анонс еще не прошел модерацию');
}

$dbh->exec('update `events` set v_count = v_count + 1 where id = ' . $id_event);

$event['ts_b'] = strtotime($event['begin_date']);
$event['ts_e'] = strtotime($event['end_date']);

foreach (['coords', 'price', 'site', 'tel'] as $value) {
    $event[$value] = json_decode($event[$value]);
}

$assets = [];

if (!empty($event['timer'])) {
    $assets['style'][] = '/js/countdown/jquery.countdown.css';
    $assets['script'][] = '/js/countdown/jquery.countdown.js';
}

if (!empty($event['vkontakte'])) {
    $assets['script'][] = '//vk.com/js/api/openapi.js?136';
}

if (!empty($event['maper'])) {
    $assets['script'][] = '//api-maps.yandex.ru/2.1/?lang=ru_RU';
}

$e_master = $myrow->id === (int)$event['u_id'];

$moderation = [
    1 => 'Активен',
    2 => 'Отклонен',
    3 => 'На модерации'
];

/// view

if(!empty($assets['style'])) {?>
    <?php foreach ((array)$assets['style'] as $value) {?>
    <link rel="stylesheet" href="<?= $value; ?>">
    <?php }?>
<?php }?>
<?php if(!empty($assets['script'])) {?>
    <?php foreach ((array)$assets['script'] as $value) {?>
        <script src="<?= $value; ?>"></script>
    <?php }?>
<?php }?>

<style>
    #event{margin:10px}
    #event hr{border-style:groove;margin:20px 0}
    .t-breadcrumbs{padding:5px;font-style:italic;margin-bottom:10px}
    .e-table,.e-table-min,.e-table-min-tel{display:table-cell;position:relative}
    .e-table,.e-table-min{vertical-align:top}
    .e-table-min,.e-table-min-tel{padding-right:5px}
    .e-table{padding-right:20px}
    .e-table-min-tel{vertical-align:middle}
    .e-ava{display:block;width:250px;height:auto;padding:0;vertical-align:middle}
    .e-ava{border:1px solid #ccc;border-radius:2px}
    .events h1{margin: 5px 0 20px}
    .e-sity,.events h1,.e-date-head,.e-price caption,.e-caption{font-weight:700}
    .events h1{font-family:Arial,Tahoma,Verdana,sans-serif;font-size: 26px}
    .e-date{padding:5px 0;margin-bottom:10px}
    .p-sity,.e-caption{margin-bottom: 5px}
    .e-date-head{font-size:21px;color:#5c6583}
    .e-date-time{color:#A99898;vertical-align:top}
    .sity-addr,.e-link,.e-price caption,.e-caption{font-size: 16px}
    .e-link{margin-top:10px}
    .e-los{text-decoration: underline}
    .a-map{color: #444}
    .pre-date{display:inline-block;border-top: 1px dashed #444444}
    .e-price{min-width:300px;border-collapse:collapse;border-spacing:0;}
    .e-price td,.eg-div{vertical-align:middle;padding:5px 2px 0 5px;border-bottom:1px solid #E5E5E5;}
    .e-price td:first-child,.e-tel-tab{white-space:nowrap}
    .e-price td:first-child{width: 76px}
    .e-price caption{text-align:left;padding:2px 0}
    .e-tel{margin-top: 10px}
    .e-user-info{min-width:300px;max-width: 500px;overflow:hidden}
    .e-mod{text-transform:uppercase;border-radius:2px;padding:2px;color:#fff;}
    .e-mod-1{background: #008000}
    .e-mod-2{background: #ff0000}
    .e-mod-3{background: #ffff00;color:#444}
    .e-mod-4{background: #CCC;}
</style>

<div class="t-breadcrumbs">
    <!--suppress HtmlUnknownTarget -->
    <?php if($e_master) {?>
    <a href="/my_events">Мои встречи</a> &bull;
    <?php }?>
    <a href="/all_events">Все анонсы</a>
    &bull;
    <?= html($event['title'])?>
</div>

<div id="event">
    <?php if($e_master || $myrow->isAdmin()) {?>
        <?php if($_SERVER['REQUEST_TIME'] < $event['ts_e']) {?>
            <span class="e-mod e-mod-<?=$event['status']; ?>"><?= $moderation[$event['status']]; ?></span>
        <?php }else{?>
            <span class="e-mod e-mod-4">Неактивен</span>
        <?php }?>
        &mdash;
        <a href="/edit_event_<?= $id_event ;?>">Редактировать</a>
        <br>
    <?php }?>
    <div class="e-head">
        <div class="e-table">
            <img class="e-ava" src="/<?= $event['img']; ?>" alt="eava">
        </div>
        <div class="e-table events">
            <?php if($_SERVER['REQUEST_TIME'] > $event['ts_e']) {?>
            <span class="e-mod e-mod-4">Встреча завершена</span>
            <?php }?>
            <h1><?= html($event['title']); ?></h1>
            <div class="e-date">
                <span class="e-date-head"><?= date('d.m.Y',$event['ts_b']); ?> в <?= date('H:i',$event['ts_b']); ?></span>
                <i class="e-date-time">
                    <?php if($_SERVER['REQUEST_TIME'] < $event['ts_b']) {?>
                        начнется через <?php echo (new \Swing\Components\SwingDate($event['begin_date']))->getHumansShort(); ?>
                        (продолжительность &mdash; <?php echo (new \Swing\Components\SwingDate(date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME'] + ($event['ts_e'] - $event['ts_b']))))->getHumansShort(); ?>)
                    <?php }elseif($_SERVER['REQUEST_TIME'] > $event['ts_e']) {?>
                        закончилась <?php echo (new \Swing\Components\SwingDate($event['end_date']))->getHumans(); ?>
                    <?php }else{?>
                        началась
                    <?php }?>
                </i>
            </div>
            <div class="sity-addr">
                <div class="p-sity">
                    <span class="e-sity"><?= html($event['city']); ?></span>
                    <span>(<?= html($event['club']); ?>)</span>
                </div>
                <div>
                    <a href="#map" class="a-map"><?= html($event['address']); ?></a>
                </div>
            </div>
            <div class="e-link">
            <?php if(!empty($event['site'])) {?>
                <!--noindex-->
                <a href="<?= $event['site'][0]?>" class="e-los" target="_blank" rel="nofollow"><?= $event['site'][1] ? html($event['site'][1]) : $event['site'][0]?></a>
                <!--/noindex-->
            <?php }?>
            </div>
        </div>
    </div>
    <hr>

    <div class="e-present">
        <div class="present-text">
            <?= $event['text']?>
        </div>
        <div style="display: none">
            <span class="pre-date">До начала мероприятия осталось всего:</span>
            <div id="timer" data-time="<?= $event['begin_date']?>"></div>
        </div>
    </div>
    <hr>
    <?php if(!empty($event['price'][0]) || !empty($event['price'][1]) || !empty($event['price'][2])) {?>

    <table class="e-price">
        <caption>Дополнительная информация</caption>
        <?php if(!empty($event['price'][0])) {?>
        <tr>
            <td>Для пар МЖ:</td>
            <td><?= html($event['price'][0]); ?></td>
        </tr>
        <?php }?>
        <?php if(!empty($event['price'][1])) {?>
        <tr>
            <td>Для девушек:</td>
            <td><?= html($event['price'][1]); ?></td>
        </tr>
        <?php }?>
        <?php if(!empty($event['price'][2])) {?>
        <tr>
            <td>Для мужчин:</td>
            <td><?= html($event['price'][2]); ?></td>
        </tr>
        <?php }?>
    </table>
    <hr>
    <?php }?>

    <div class="contacts">
        <div class="e-table e-user-info">
            <div class="e-caption">Контакты</div>
            <div class="e-table-min">
                <img src="/avatars/user_thumb/<?= $event['u_ava']?>" width="70" height="70" alt="avatar">
            </div>
            <div class="e-table-min">
                <a class="hover-tip" href="/id<?= $event['u_id']; ?>" target="_blank">
                    <img src="/img/info_small_<?= $event['u_gender']; ?>.png" width="15" height="14" alt="gender"> <?= $event['u_login']; ?>&nbsp;
                </a>
                <br>
                <?= $event['u_name']; ?>
                <br>
                <?= $event['u_city']; ?>
            </div>
            <hr>
            <div>
            <?php if(!empty($event['site'])) {?>
                <div>
                    <!--noindex-->
                    Сайт: <a href="<?= $event['site'][0]?>" class="e-los" target="_blank" rel="nofollow"><?= $event['site'][1] ? html($event['site'][1]) : $event['site'][0]?></a>
                    <!--/noindex-->
                </div>
            <?php }?>
            <?php if(!empty($event['email'])) {?>
                <div>
                    <!--noindex-->
                    Email: <a href="mailto:<?= $event['email'] ;?>" class="e-los" rel="nofollow"><?= $event['email'] ;?></a>
                    <!--/noindex-->
                </div>
            <?php }?>
            <?php if(!empty($event['tel'])) {?>
                <div class="e-tel">
                    <div>Телефоны:</div>
                    <?php foreach ($event['tel'] as $value) {?>
                    <div class="eg-div">
                        <div class="e-table-min-tel e-tel-tab">
                            <!--noindex-->
                            <a href="tel:<?= str_replace(['-','(',')',' '], '', $value[0]); ?>" rel="nofollow"><?= $value[0]; ?></a>
                            <!--/noindex-->
                        </div>
                        <div class="e-table-min-tel"><?php if(!empty($value[1])) {echo '- ', html($value[1]);}?></div>
                    </div>
                    <?php }?>
                </div>
            <?php }?>
            </div>
        </div>
        <!--noindex-->
        <div class="e-table">
            <div id="vksgf"></div>
        </div>
        <!--/noindex-->
    </div>

    <div style="display:none">
        <hr>
        <div id="map" style="max-width:800px;height:340px;margin-top:20px;"></div>
        <hr>
    </div>
</div>


<script>
<?php if($event['timer'] && $_SERVER['REQUEST_TIME'] < $event['ts_b']) {?>
(function () {
    var t = $('#timer');
    var d = t.data('time').split(/[- :]/);
    t.countdown({
        timestamp : new Date(d[0],d[1]-1,d[2],d[3],d[4])
    }).parent().show();
})();
<?php }?>

<?php if(!empty($event['vkontakte'])) {?>
    VK.Widgets.Group("vksgf", {mode: 0, width: "220"}, <?= $event['vkontakte']; ?>);
<?php }?>
<?php if(!empty($event['maper']) && !empty($event['address']) && !empty($event['coords'])) {?>
    ymaps.ready(function () {
        $('#map').parent().show();
        var map = new ymaps.Map('map', {
            center: [<?= implode(',', $event['coords']); ?>],
            zoom: 15
        });

        addPlacemark([<?= implode(',', $event['coords']); ?>],'<?= html($event['club']); ?>','<?= html($event['address']); ?>');

        function addPlacemark(c,i,b) {
            var cl = decodeHtml(i);
            var p = new ymaps.Placemark(c, {
                iconCaption: cl,
                balloonContent: decodeHtml(b),
                balloonContentHeader: cl
            }, {
                preset: 'islands#blueDotIconWithCaption'
            });
            map.geoObjects.add(p);
        }

        function decodeHtml(t) {
            return t
                .replace(/&amp;/g, "&")
                .replace(/&lt;/g, "<")
                .replace(/&gt;/g, ">")
                .replace(/&quot;/g, '"')
                .replace(/&#039;/g, "'");
        }
    });
<?php }?>
</script>



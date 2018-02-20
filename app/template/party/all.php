<?php
/**
 * @var \Swing\System\Controller $this
 */

if (!$events = cache()->get('party_events')) {
    $sql = 'select id, title, img, begin_date, end_date, club, v_count, city, address 
      from `events` 
      where end_date > now() 
      and `status` = 1 
    order by begin_date asc';

    $sth = $this->dbh->query($sql);

    $events = [];

    while ($row = $sth->fetch()) {
        $row['ts_b'] = strtotime($row['begin_date']);
        $row['ts_e'] = strtotime($row['end_date']);
        $events[] = $row;
    }

    cache()->set('party_events', $events, 3600);
}


if (!empty($events) && $this->myrow->isUser()) {

    $u_events = [];

    $u_city = mb_strtolower($this->myrow->city);

    foreach ($events as $key => &$value) {
        if (strcmp($u_city, mb_strtolower($value['city'])) === 0) {
            $value['city'] = '<span style="color: #0000FF">' . html($value['city']) . '</span>';
            $u_events[] = $value;
            unset($events[$key]);
        }
    }

    unset($value, $u_city);
} ?>
<style>
    #content hr {
        border-style: groove;
        margin: 20px 0
    }

    .events, .e-ava {
        border: 1px solid #ccc;
        border-radius: 2px
    }

    .events {
        margin-bottom: 20px;
        background: #ebebf3;
    }

    .e-date {
        margin-bottom: 10px
    }

    .e-sity, .events h3, .e-date-head {
        font-weight: 700
    }

    .events h3 {
        font-size: 20px;
        margin: 5px 0 10px;
        text-decoration: underline
    }

    .e-addr {
        font-size: 16px
    }

    .e-date-head {
        font-size: 18px;
        color: #5c6583
    }

    .e-date-time {
        color: #A99898
    }

    .e-table {
        display: table-cell;
        position: relative;
        vertical-align: top;
        padding-right: 20px;
    }

    .e-table-first {
        text-align: center
    }

    .e-ava {
        display: block;
        width: 150px;
        height: auto;
        padding: 0;
        vertical-align: middle
    }

    .e-mod {
        text-transform: uppercase;
        border-radius: 2px;
        padding: 2px;
        color: #fff;
    }

    .e-mod-1 {
        background: #008000
    }

    .e-mod-2 {
        background: #ff0000
    }

    .e-mod-3 {
        background: #ffff00;
        color: #444
    }

    .e-mod-4 {
        background: #CCC;
    }

    .e-mod-head {
        font-size: 10px
    }
</style>

<h1>Анонсы свинг вечеринок</h1>
<hr>
<?php if (!empty($u_events)) { ?>
    <h2>Вечеринки в вашем городе</h2>
    <?= $this->render('party/events', ['data' => $u_events]) ?>
    <hr>
<?php } ?>
<?php if (!empty($events)) { ?>
    <h2>Все вечеринки</h2>
    <?= $this->render('party/events', ['data' => $events]) ?>
    <hr>
<?php } else { ?>
    <p>Пока нет анонсов</p>
<?php } ?>

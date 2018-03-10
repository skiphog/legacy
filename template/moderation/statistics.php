<?php
/**
* @var \System\View $this
*/

$dbh = db();
$cache = cache();
$myrow = auth();

if (!$series = $cache->get('moderation_active')) {

    $series = [
        'users' => [],
        'month' => []
    ];

    $date = new DateTime('first day of this month -1 year 00:00:00');

    try {
        $interval = new DateInterval('P1M');
    } catch (Exception $e) {}

    $period = new DatePeriod($date, $interval, (new DateTime())->modify('last day of this month 23:59:59'));

    foreach ($period as $value) {
        /** @var DateTime $value */
        $series['month'][] = \App\Components\SwingDate::$month[$value->format('n')];

        $sql = 'select
          u.login, (select count(*)
            from log_moder
            where id_user = u.id
              and id_type in (10, 11)
              and log_date between ' . $dbh->quote($value->format('Y-m-d H:i:s')) . ' 
              and ' . $dbh->quote($value->modify('last day of 23:59:59')->format('Y-m-d H:i:s')) . ') cnt
            from users u
            where u.id in
        (89978, 3, 1, 6, 10022, 75378, 82534, 60146, 69467, 12796, 103745, 80445, 80445, 101453, 75943, 96249)';

        $sth = $dbh->query($sql);
        $i = 0;
        while ($row = $sth->fetch()) {
            if (empty($series['users'][$i])) {
                $series['users'][$i] = new stdClass();
                $series['users'][$i]->name = $row['login'];
                $series['users'][$i]->data[] = (int)$row['cnt'];
            } else {
                $series['users'][$i]->data[] = (int)$row['cnt'];
            }
            $i++;
        }
    }

    $cache->set('moderation_active', $series);
}
if (!$series1 = $cache->get('moderation_ban')) {
    $sql = 'select u.login,count(l.id) cnt
			from log_moder l
			join users u on u.id = l.id_user
		where l.id_type = 2 group by l.id_user order by cnt desc ';
    $series1 = $dbh->query($sql)->fetchAll();
    $cache->set('moderation_ban', $series1, 10800);
}
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Статистика<?php $this->stop(); ?>
<?php $this->start('description'); ?>Статистика<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .chars{margin:10px auto}
    .clear-cache{text-align:center;margin:20px 0}
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<div id="container1" class="chars border-box"></div>
<?php if ($myrow->isAdmin()) : ?>
    <div class="clear-cache">
        <button class="btn btn-primary" type="button">Обновить кэш лога модерации</button>
        <p>
            <small>Обновление кэша длится ~ 5-10 секунд (операция ресурсозатратная)<br>Доступно только админам</small>
        </p>
    </div>
<?php endif; ?>
<hr>
<div id="container2" class="chars"></div>
<hr>
<?php $this->stop(); ?>

<?php $this->start('script'); ?>
<script src="//code.highcharts.com/highcharts.js"></script>
<script>
  var users = <?php echo json_encode($series1); ?>, us2 = [];
  for (var i = 0; i < users.length; i++) {
    us2[i] = [users[i]['login'], users[i]['cnt'] * 1];
  }

  $(function () {

    $('#container1').highcharts({
      chart: {
        type: 'line'
      },
      title: {
        text: 'Модерация анкет'
      },
      yAxis: {
        min: 0,
        title: {
          text: 'Количество модераций'
        }
      },
      xAxis: {
        categories: <?php echo json_encode($series['month']); ?>
      },
      series: <?php echo json_encode($series['users']); ?>
    });

    $('#container2').highcharts({
      chart: {
        plotBackgroundColor: null,
        plotBorderWidth: null,
        plotShadow: false,
        type: 'pie'
      },
      title: {
        text: 'Самые банистые модераторы за всю историю лога'
      },
      plotOptions: {
        pie: {
          allowPointSelect: true,
          cursor: 'pointer',
          size: 350
        }
      },
      xAxis: {
        type: 'category'
      },
      yAxis: {
        min: 0,
        title: {
          text: 'Количество банов'
        }
      },
      legend: {
        enabled: false
      },
      series: [
        {
          name: 'Забанено',
          colorByPoint: true,
          data: us2
        }]
    });



    var sendModer = true;

    $('.clear-cache').on('click', function () {

      if (false === sendModer) {
        return;
      }
      sendModer = !1;

      $.post('/ajax/', {cntr: 'ModerLog', action: 'clearCacheBanned'}).
        done(function () {
          location.assign('/moderator/statistic');
        });
    });

    /*$('#container3').highcharts({
        chart: {
            type: 'line'
        },
        title: {
            text: 'Статистика по возрастам на swing-kiska.ru'
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Количество анкет'
            }
        },
        xAxis: {
            categories: ['18-25','25-30','30-35','35-40','40-45','45-50','50-55','55-60','60+']
        },

        series: [{
            'name' : 'Мужчины',
            'color' : '#4b7bb1',
            'data' : [994,5397,9451,8860,5368,3234,1199,396,41]
        },
            {
                'name' : 'Девушки',
                'color' : '#f09609',
                'data' : [217,666,741,668,353,158,50,16,11]
            },
            {
                'name' : 'Пары',
                'color' : '#f15c80',
                'data' : [574,3626,7849,7997,5101,2687,846,298,25]
            },
            {
                'name' : 'Трансы',
                'color': '#555',
                'data' : [21,39,47,46,36,20,14,19,3]
            }
        ]

    });*/
  });
</script>
<?php $this->stop(); ?>
<?php
/**
 * @var \Swing\System\Controller $this
 */

/**
 * @param $now
 * @param $diff
 *
 * @return string
 */
function dateWis($now, $diff)
{
    $diff = new DateTimeImmutable($diff);
    if ($now === $format = $diff->format('d-m-Y')) {
        return '<strong style="color: blue;font-size: 16px;">Сегодня</strong><br>' . $format . '<br>' . $diff->format('H:i:s');
    }
    if ($now === $diff->modify('+1day')->format('d-m-Y')) {
        return '<strong>Вчера</strong><br>' . $format . '<br>' . $diff->format('H:i:s');
    }

    return $format;
}

$sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
	u.real_status, u.visibility, u.hot_time, u.regdate,
	u.vip_time, u.now_status, u.hot_text,
	u.vipsmile,u.admin, u.moderator, u.city,
	u.login, u.fname, u.gender, u.about,
	ut.last_view, w.wholoock_time,w.looking
	from whoisloock w
	join users u on u.id = w.wholoock_kto
	join users_timestamps ut on ut.id = u.id
	where w.wholoock_kogo=' . $this->myrow->id . '
order by wholoock_time desc limit 50';

$sth = $this->dbh->query($sql);
$date = date('d-m-Y');
$data = [];
while ($row = $sth->fetchObject(\Swing\Models\RowUser::class)) {
    $row->wholoock_time = dateWis($date, $row->wholoock_time);
    $data[] = $row;
}

$this->dbh->exec('update whoisloock set looking = 1 where wholoock_kogo = ' . $this->myrow->id);
?>
<style>
    .container .border-box {
        margin: 10px 0;
    }

    .w-time, .w-body {
        display: table-cell;
        vertical-align: middle;
        width: 100%
    }

    .w-time {
        width: 70px;
        text-align: center;
        white-space: nowrap;
        padding-right: 5px
    }

    .look-0 {
        border: 1px solid #FFC818 !important;
        box-shadow: 0 0 8px #FF0 !important;
        -moz-box-shadow: 0 0 8px #FF0 !important;
        -webkit-border-shadow: 0 0 8px #FF0 !important;
    }
</style>
<?php if (!empty($data)) { ?>
    <h1>Анкету смотрели</h1>
    <div class="container">
        <button class="btn btn-default toggle">Очистить список</button>
        <div class="btn-group toggle-off" style="display: none">
            <button id="action" class="btn btn-success" onclick="clearWis();">Подтверждаю</button>
            <button class="btn btn-danger toggle">Отказываюсь</button>
        </div>

        <?php foreach ($data as $value) { ?>
            <div class="border-box look-<?php echo $value->looking; ?>">
                <div class="w-time"><?php echo $value->wholoock_time; ?></div>
                <div class="w-body"><?php anketa_usr_row($this->myrow, $value); ?></div>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <h1>Анкету еще не смотрели</h1>
<?php } ?>

<script>
  var container = $('.container');

  container.on('click', '.toggle', function () {
    $('.toggle-off').toggle();
  });

  function clearWis () {
    container.html('История посещений очищена');
    $.post('/ajax/', {cntr: 'Option', action: 'clearWis'});
  }

  $(document).ready(function () {
    setTimeout(function () {
      $('.count-guest').fadeOut();
    }, 500);
    $('.container').on('click', '.border-box', function () {
      var look = $(this);
      if (look.hasClass('look-0')) {
        look.removeClass('look-0');
      }
    });
  });
</script>
<?php
/**
 * @var \Swing\System\View $this
 */
$dbh = db();
$myrow = auth();

$page = (int)request()->get('page');

$params = [];
$line_link = [];
$where = '';
$distinct = '';
$now = date('Y-m-d');
$bann_user = $myrow->isModerator() ? ' and u.status <> 2' : '';
$join_count = '';
$join = 'join users_timestamps ut on ut.id = u.id';
$orderby = 'ut.last_view';

if (!empty($_REQUEST['find_nik'])) {
    $find_nik = trim(strip_tags($_REQUEST['find_nik']));
    $where = ' and u.login like :find_nik';
    $params['find_nik'] = sprintf('%%%s%%', $find_nik);
    $line_link['find_nik'] = $find_nik;
} else {

    if (!empty($_REQUEST['gender'])) {
        $gender = abs((int)$_REQUEST['gender']);
        $where .= ' AND u.gender = ' . $gender;
        $line_link['gender'] = $gender;
    }

    if (!empty($_REQUEST['agef']) && !empty($_REQUEST['aget'])) {
        $agef = abs((int)$_REQUEST['agef']);
        $aget = abs((int)$_REQUEST['aget']);

        if ($agef !== 18 || $aget !== 60) {
            $tget = (($aget + 1) > 60) ? 100 : $aget;
            $where .= ' and u.birthday BETWEEN DATE_SUB(now(),INTERVAL ' . $tget . ' YEAR) AND DATE_SUB(now(), INTERVAL ' . $agef . ' YEAR)';
            $line_link['agef'] = $agef;
            $line_link['aget'] = $aget;
        }
    }

    if (!empty($_REQUEST['purposes'])) {
        $purposes = abs((int)$_REQUEST['purposes']);
        $where .= ' and u.purposes like \'%' . $purposes . '%\'';
        $line_link['purposes'] = $purposes;
    }

    if (!empty($_REQUEST['country'])) {
        $country = abs((int)$_REQUEST['country']);
        $where .= ' and u.country = ' . $country;
        $line_link['country'] = $country;
    }

    if (!empty($_REQUEST['find_city'])) {
        $find_city = trim(strip_tags($_REQUEST['find_city']));
        $where .= ' AND u.city like :find_city';
        $params['find_city'] = sprintf('%%%s%%', $find_city);
        $line_link['find_city'] = $find_city;
    }

    if (!empty($_REQUEST['find_real'])) {
        $where .= ' and u.real_status = 1';
        $line_link['find_real'] = 1;
    }

    if (!empty($_REQUEST['find_alb'])) {
        $distinct = 'DISTINCT';
        $join_count = 'JOIN photo_albums pa on pa.us_id_album = u.id';
        $join .= ' JOIN photo_albums pa on pa.us_id_album = u.id';
        $line_link['find_alb'] = 1;
    }

    if (!empty($_REQUEST['find_new'])) {
        $orderby = 'u.regdate';
        $line_link['find_new'] = 1;
    }
}


$sql = 'select count(' . $distinct . ' u.id) from users u ' . $join_count . ' where 1=1 ' . $where . $bann_user;

$sth = $dbh->prepare($sql);
$sth->execute($params);
?>

<?php $this->extend('layout/layout'); ?>

<?php $this->start('title'); ?>Результат поиска<?php $this->stop(); ?>
<?php $this->start('description'); ?>Результат поиска анкет<?php $this->stop(); ?>

<?php $this->start('content'); ?>

<?php if ($count = $sth->fetchColumn()) {
    $pagination = new \Kilte\Pagination\Pagination($count, $page, 30);

    $sql = 'select ' . $distinct . ' u.id, u.birthday, u.pic1, u.photo_visibility,u.real_status,
		u.visibility, u.hot_time, u.regdate,u.vip_time, u.now_status, u.hot_text,u.vipsmile,
		u.admin, u.moderator, u.city,u.login, u.fname, u.gender, u.about,ut.last_view
		from users u ' . $join . '
		where 1=1 ' . $where . ' ' . $bann_user . '
		order by ' . $orderby . '
	desc limit ' . $pagination->offset() . ',30';
    $sth = $dbh->prepare($sql);
    $sth->execute($params);
    $users = $sth->fetchAll(PDO::FETCH_CLASS, \Swing\Models\RowUser::class);
    $paging = $pagination->build();
    $paging_page = 'Одна страница';
    $line_link = http_build_query($line_link);

    if (!empty($paging)) {
        ob_start(); ?>
        <ul class="pagination">
            <?php foreach ($paging as $link => $name) { ?>
                <li class="<?php echo $name; ?>">
                    <a href="/findresult?find=1&page=<?php echo $link . '&'. $line_link; ?>"><?php echo $link; ?></a>
                </li>
            <?php } ?>
        </ul>
        <?php $paging_page = ob_get_clean();
    } ?>

    <table border="0" width="100%">
        <tr>
            <td height="1" colspan="2" bgcolor="#336699"></td>
        </tr>
        <tr>
            <td align="left" style="font-weight:bolder;font-size:16px;">
                <a href="/findlist">Поиск анкет</a>&nbsp;&bull;&nbsp;Результаты поиска (<?php echo $count; ?>)
            </td>
        </tr>
        <tr>
            <td height="1" colspan="2" bgcolor="#336699"></td>
        </tr>
        <tr>
            <td align="left"><?php echo $paging_page; ?></td>
        </tr>
        <tr>
            <td height="1" colspan="2" bgcolor="#336699"></td>
        </tr>
        <tr>
            <td align="center" colspan="2" class="user-row">
                <?php foreach($users as $user) : ?>
                    <?php anketa_usr_row($myrow, $user); ?>
                <?php endforeach; ?>
            </td>
        </tr>
        <tr>
            <td height="1" colspan="2" bgcolor="#336699"></td>
        </tr>
        <tr>
            <td align="left"><?php echo $paging_page; ?></td>
        </tr>
        <tr>
            <td height="1" colspan="2" bgcolor="#336699"></td>
        </tr>
    </table>
    <?php
} else { ?>
    <div style="text-align: center">
        <h2>Нет анкет соответствующих запросу</h2>
        <h3>
            <a href="/findlist">Назад к поиску</a>
        </h3>
    </div>
<?php }?>
<?php $this->stop(); ?>
<?php
/**
 * @var \Swing\System\Controller $this
 */

[$login, $password] = request()->postValuesString(['login', 'password']);

if (empty($login) || empty($password)) {
    exit('Вы ввели не всю информацию, вернитесь назад и заполните все поля!<br><br><a href="/xmail"> проблемы с доступом? Напишите нам. Мы поможем.</a>');
}

$dbh = db();

$sth = $dbh->prepare('select u.id, u.login, u.password,u.vip_time, HEX(uuid) as uuid,o.stels from users u left join `option` o on o.u_id = u.id where u.login = ? and u.password = ?');
$sth->execute([$login, md5($password)]);

if (!$myrow = $sth->fetch()) {
    exit('Извините, логин или пароль неверный. <a href="/repass">Забыли пароль?</a>');
}

$user = array_slice($myrow, 0, 3);

foreach ($user as $key => $value) {
    $_SESSION[$key] = $value;
}

if (!($myrow['stels'] && strtotime($myrow['vip_time']) - $_SERVER['REQUEST_TIME'] >= 0)) {
    $dbh->exec('update users_timestamps set last_view = NOW(), ip = ' . ip2long($_SERVER['REMOTE_ADDR']) . ' where id = ' . (int)$myrow['id']);
}

cache()->delete('online_users');

$domain = config('domain');
$secure = config('secure');

if (isset($_POST['save'])) {
    foreach ($user as $key => $value) {
        setcookie($key, $value, 0x7FFFFFFF, '/', $domain, $secure, true);
    }
}

$id_user = $myrow['id'];
$uuid = $myrow['uuid'];

if (empty($_COOKIE['SSKSESID'])) {
    setcookie('SSKSESID', $uuid, 0x7FFFFFFF, '/', $domain);
    header('location: profile');
    exit;
}

$ses_uuid = $_COOKIE['SSKSESID'];

$sth = $dbh->prepare('select exists(select id from users where uuid = UNHEX(?))');
$sth->execute([$ses_uuid]);

if (!$sth->fetchColumn()) {
    setcookie('SSKSESID', $uuid, 0x7FFFFFFF, '/', $domain);
    header('location: profile');
    exit;
}

if ($ses_uuid !== $uuid) {
    if ($uuid < $ses_uuid) {
        $min_uuid = $uuid;
        $max_uuid = $ses_uuid;
        setcookie('SSKSESID', $min_uuid, 0x7FFFFFFF, '/', $domain);
    } else {
        $min_uuid = $ses_uuid;
        $max_uuid = $uuid;
    }

    $sql = 'update users 
              set uuid = UNHEX(' . $dbh->quote($min_uuid) . ') 
            where uuid = UNHEX(' . $dbh->quote($max_uuid) . ')';
    $dbh->exec($sql);

    $sql = 'insert into log_moder (id_type,id_user,content) values (1, ' . (int)$id_user . ',' . $dbh->quote($min_uuid) . ')';
    $dbh->exec($sql);

    $sql = 'select id from users where moderator <> 0 and id not in (73726,935) order by id';
    $moderators = $dbh->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);

    $dbh->beginTransaction();

    $sql = 'insert into notification (id_user,mesage,ntime) values (?, ?, now())';

    $sth = $dbh->prepare($sql);
    $txt = '<strong>Модераторская</strong><br><br>Зафиксирован новый дубль <a style="font-weight: bolder;color: blue;" href="/id' . $myrow['id'] . '" target="_blank">' . $myrow['login'] . '</a>';

    foreach ($moderators as $moderator_id) {
        $sth->execute([$moderator_id, $txt]);
    }

    $dbh->commit();
}

header('location: profile');
exit;

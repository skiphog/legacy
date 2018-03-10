<?php

header('Content-Type: application/json;charset=utf-8');
header('Cache-Control: no-store;max-age=0');

$validate = [
    'login'    => function ($data) {
        $data = trim($data);
        if (!is_string($data) || ($length = mb_strlen($data) < 3) || $length > 25) {
            throw new \InvalidArgumentException('Некорректный Логин');
        }

        foreach (['"', '\\', '/', '*', '\'', '`', '&quot;', '&apos;', ';', ',', '@', '.'] as $key => $value) {
            if (strpos($data, $value) !== false) {
                throw new \InvalidArgumentException('Логин содержит недопустимые символы');
            }
        }

        if (!preg_match('~^[a-zа-яё][a-zа-яё0-9-_ ]+[a-zа-яё0-9]$~iu', $data)) {
            throw new \InvalidArgumentException('Логин должен начинаться с буквы и заканчиваться буквой или цифрой');
        }

        $sth = db()->prepare('select exists(select id from users where login = ?)');
        $sth->execute([$data]);

        if ($sth->fetchColumn()) {
            throw new \InvalidArgumentException('Логин занят');
        }

        return $data;
    },
    'password' => function ($data) {
        if (empty($data) || mb_strlen($data) < 6) {
            throw new \InvalidArgumentException('Пароль должен иметь не меньше 6-ти символов');
        }

        return md5($data);
    },
    'email'    => function ($data) {
        $data = trim($data);

        if (filter_var($data, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException('Неккоректный email адрес');
        }

        $sth = db()->prepare('select exists(select id from users where email = ?)');
        $sth->execute([$data]);

        if ($sth->fetchColumn()) {
            throw new \InvalidArgumentException('Недопустимый email / Либо уже зарегистрирован.');
        }

        return $data;
    },
    'fname'    => function ($data) {
        $data = trim($data);

        if (empty($data)) {
            throw new \InvalidArgumentException('Имя не может быть пустым');
        }

        return $data;
    },
    'gender'   => function ($data) {
        $data = abs((int)$data);

        if (!array_key_exists($data, \App\Arrays\Genders::$gender)) {
            throw new \InvalidArgumentException('Некооректный выбор');
        }

        return $data;
    },
    'country'  => function ($data) {
        $data = abs((int)$data);

        if (!array_key_exists($data, \App\Arrays\Country::$array)) {
            throw new \InvalidArgumentException('Некооректный выбор гор');
        }

        return $data;
    },
    'city'     => function ($data) {
        $data = trim($data);

        if (empty($data)) {
            throw new \InvalidArgumentException('Выберите город');
        }

        return mb_convert_case($data, MB_CASE_TITLE);
    },
    'birthday' => function ($array) use (&$data) {
        $date = (array)$array;
        if (count($date) !== 3 || false === checkdate($date[1], $date[2], $date[0])) {
            throw new \InvalidArgumentException('Некорректная дата рождения');
        }

        $date = new DateTimeImmutable(implode('/', $date));
        $check = new DateTimeImmutable();

        if ($date->modify('+18 year') > $check) {
            throw new \InvalidArgumentException('Ресурс предназначен для людей старше 18-ти лет');
        }

        if ($date < $check->modify('-60 year')) {
            throw new \InvalidArgumentException('Ах ты старый извращенец! Песок уже сыплется, а ты всё туда...');
        }

        $data['birthday_hash'] = (int)$date->format('m') * 31 + (int)$date->format('d');
        $data['regdate'] = $check->format('Y-m-d H:i:s');

        return $date->format('Y-m-d');
    },
    'purposes' => function ($data) {
        $data = array_filter((array)$data, function ($item) {
            return array_key_exists((int)$item, \App\Arrays\Purposes::$array);
        });

        if (empty($data)) {
            throw new \InvalidArgumentException('Некорректный выбор');
        }

        return implode(',', $data);
    },
    'sgender'  => function ($data) {
        $data = array_filter((array)$data, function ($item) {
            return array_key_exists((int)$item, \App\Arrays\Genders::$sgender);
        });

        if (empty($data)) {
            throw new \InvalidArgumentException('Некорректный выбор');
        }

        return implode(',', $data);
    },
    'personal' => function ($data) {
        if (empty($data)) {
            throw new \InvalidArgumentException('Подтвердите условие регистрации');
        }

        return null;
    }
];


$errors = [];
$data = [];

foreach ($validate as $key => $value) {
    if (!array_key_exists($key, $_POST)) {
        $errors[$key] = 'Обязательное поле';
        continue;
    }
    try {
        $data[$key] = $value($_POST[$key]);
    } catch (\Exception $e) {
        $errors[$key] = $e->getMessage();
    }
}


if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(['errors' => $errors], JSON_UNESCAPED_UNICODE);
    die;
}

unset($data['personal']);
$data['moder_text'] = $data['moder_zametka'] = '';

$sql = sprintf(/** @lang text */
    'insert into users (%s) values (:%s)',
    implode(',', array_keys($data)),
    implode(',:', array_keys($data))
);

try {
    $sth = db()->prepare($sql);
    $result = $sth->execute($data);
} catch (\Exception $e) {
    die('XTRA');
}

$_SESSION['registration'] = $data['login'];

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
    header('Location: /login');
    die;
}

echo json_encode(['status' => 1], JSON_UNESCAPED_UNICODE);
die;
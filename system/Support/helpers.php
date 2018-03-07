<?php

/**
 * Получить объект из контейнера
 *
 * @param string $name
 *
 * @return mixed
 */
function app($name)
{
    return \System\Container::get($name);
}

/**
 * Получить параметр из конфига
 *
 * @param string $key
 *
 * @return mixed
 */
function config($key)
{
    return app(\System\Setting::class)->get($key);
}

/**
 * @return \System\Request
 */
function request()
{
    return app(\System\Request::class);
}

/**
 * @return \PDO
 */
function db()
{
    return app(\System\DB::class)->dbh();
}

/**
 * @return \System\Cache\Cache
 */
function cache()
{
    return app('cache');
}

/**
 * @return \App\Models\Myrow
 */
function auth()
{
    return \App\Components\Auth::user();
}

/** @noinspection PhpDocMissingThrowsInspection */
/**
 * @param string $name
 * @param array  $params
 *
 * @return string
 */
function render($name, array $params = [])
{
    /** @noinspection PhpUnhandledExceptionInspection */
    return (new \System\View())->render($name, $params);
}

/**
 * @param string $name
 * @param array  $params
 *
 * @return \System\Response
 */
function view($name, array $params = [])
{
    return (new \System\Response())->setData(render($name, $params));
}

/**
 * @param string $url
 *
 * @return \System\Response
 */
function redirect($url = '/')
{
    return (new \System\Response())->redirect($url);
}

/**
 * @param mixed $data
 * @param int   $code
 *
 * @return \System\Response
 */
function json($data, $code = 200)
{
    return (new \System\Response())->json($data, $code);
}

/**
 * @param int  $code
 * @param null $data
 */
function abort($code = 404, $data = null)
{
    (new \System\Response())->abort($code, $data);
}

/**
 * Экранирует теги
 *
 * @param $string string
 *
 * @return string string
 */
function html($string)
{
    return htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE);
}

/**
 * @param string $text
 * @param int    $sub
 * @param string $end
 *
 * @return string
 */
function subText($text, $sub, $end = '')
{
    if (isset($text[$sub * 2 + 2])) {
        $text = mb_substr($text, 0, (int)$sub);
        $text = mb_substr($text, 0, mb_strrpos($text, ' '));
        $text .= $end;
    }

    return $text;
}

/**
 * @param int    $number
 * @param string $words [анкета|анкеты|анкет]
 *
 * @return string
 */
function plural($number, $words)
{
    $tmp = explode('|', $words);

    if (count($tmp) < 3) {
        return '';
    }

    return $tmp[(($number % 10 === 1) && ($number % 100 !== 11)) ? 0 : ((($number % 10 >= 2) && ($number % 10 <= 4) && (($number % 100 < 10) || ($number % 100 >= 20))) ? 1 : 2)];
}

/**
 * Сравнение двух строк
 *
 * @param string $str1
 * @param string $str2
 *
 * @return int
 */
function strCompare($str1, $str2)
{
    return (int)(mb_strtoupper($str1) === mb_strtoupper($str2));
}

/**
 * Очистить строку от тегов
 *
 * @param $string
 *
 * @return string
 */
function clearString($string)
{
    return trim(strip_tags($string));
}

/**
 * @param $string
 *
 * @return string
 */
function compress($string)
{
    return preg_replace(['/>[^\S ]+/s', '/[^\S ]+</s', '/(\s)+/s'], ['>', '<', '\\1'], $string);
}

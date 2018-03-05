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
    return \Swing\System\App::get($name);
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
    return app(\Swing\System\Setting::class)->get($key);
}

/**
 * @return \Swing\System\Request
 */
function request()
{
    return app(\Swing\System\Request::class);
}

/**
 * @return \PDO
 */
function db()
{
    return app(\Swing\System\DB::class)->dbh();
}

/**
 * @return \Swing\System\Cache
 */
function cache()
{
    return app(\Swing\System\Cache::class);
}


/**
 * @return \Swing\Models\Myrow
 */
function auth()
{
    return \Swing\Components\Auth::user();
}

/** @noinspection PhpDocMissingThrowsInspection */
/**
 * @param string $name
 * @param array  $params
 *
 * @return \Swing\System\Response
 */
function view($name, array $params = [])
{
    /** @noinspection PhpUnhandledExceptionInspection */
    return (new \Swing\System\Response())->html($name, $params);
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
    return (new \Swing\System\View())->render($name, $params);
}

/**
 * @param string|null $url
 *
 * @return \Swing\System\Response
 */
function redirect($url = '/')
{
    return (new \Swing\System\Response())->redirect($url);
}

/**
 * @param mixed $data
 * @param int   $code
 *
 * @return \Swing\System\Response
 */
function json($data, $code = 200)
{
    return (new \Swing\System\Response())->json($data, $code);
}

/**
 * @param int  $code
 * @param null $data
 */
function abort($code = 404, $data = null)
{
    \Swing\System\Response::Abort($code, $data);
}

/**
 * Доступ к аватарке пользователя
 *
 * @param \Swing\Models\Myrow $myrow
 * @param string              $pic
 * @param int                 $uVis
 *
 * @return string
 */
function avatar(\Swing\Models\Myrow $myrow, string $pic, int $uVis): string
{
    if (0 === $uVis || (2 === $uVis && $myrow->isUser()) || (3 === $uVis && $myrow->isReal())) {
        return 'https://swing-kiska.ru/avatars/user_thumb/' . $pic;
    }

    if (2 === $uVis) {
        return 'https://swing-kiska.ru/img/avatars/user.jpg';
    }

    return 'https://swing-kiska.ru/img/avatars/real.jpg';
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
 * @param $text
 *
 * @return string
 */
function hyperlink($text)
{
    /** @noinspection NotOptimalRegularExpressionsInspection */
    return preg_replace(
        '/(?i)\b((?:https?:\/\/|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'\".,<>?«»“”‘’]))/u',
        '<a href="$0" target="_blank" rel="noopener noreferrer">Ссылка</a>',
        $text
    );
}

/**
 * @param $text
 *
 * @return string
 */
function smile($text)
{
    static $smiles = [
        ':)'     => '<img src="/img/smile/smile/s38.gif">',
        ';)'     => '<img src="/img/smile/smile/s08.gif">',
        ':s01:'  => '<img src="/img/smile/sex/s01.gif">',
        ':s02:'  => '<img src="/img/smile/sex/s02.gif">',
        ':s03:'  => '<img src="/img/smile/sex/s03.gif">',
        ':s04:'  => '<img src="/img/smile/sex/s04.gif">',
        ':s05:'  => '<img src="/img/smile/sex/s05.gif">',
        ':s06:'  => '<img src="/img/smile/sex/s06.gif">',
        ':s07:'  => '<img src="/img/smile/sex/s07.gif">',
        ':s08:'  => '<img src="/img/smile/sex/s08.gif">',
        ':s09:'  => '<img src="/img/smile/sex/s09.gif">',
        ':s10:'  => '<img src="/img/smile/sex/s10.gif">',
        ':s11:'  => '<img src="/img/smile/sex/s11.gif">',
        ':s12:'  => '<img src="/img/smile/sex/s12.gif">',
        ':s13:'  => '<img src="/img/smile/sex/s13.gif">',
        ':s14:'  => '<img src="/img/smile/sex/s14.gif">',
        ':s15:'  => '<img src="/img/smile/sex/s15.gif">',
        ':s16:'  => '<img src="/img/smile/sex/s16.gif">',
        ':s17:'  => '<img src="/img/smile/sex/s17.gif">',
        ':s18:'  => '<img src="/img/smile/sex/s18.gif">',
        ':s19:'  => '<img src="/img/smile/sex/s19.gif">',
        ':s20:'  => '<img src="/img/smile/sex/s20.gif">',
        ':s21:'  => '<img src="/img/smile/sex/s21.gif">',
        ':s22:'  => '<img src="/img/smile/sex/s22.gif">',
        ':s23:'  => '<img src="/img/smile/sex/s23.gif">',
        ':s24:'  => '<img src="/img/smile/sex/s24.gif">',
        ':s25:'  => '<img src="/img/smile/sex/s25.gif">',
        ':s001:' => '<img src="/img/smile/smile/s01.gif">',
        ':s002:' => '<img src="/img/smile/smile/s02.gif">',
        ':s003:' => '<img src="/img/smile/smile/s03.gif">',
        ':s004:' => '<img src="/img/smile/smile/s04.gif">',
        ':s005:' => '<img src="/img/smile/smile/s05.gif">',
        ':s006:' => '<img src="/img/smile/smile/s06.gif">',
        ':s007:' => '<img src="/img/smile/smile/s07.gif">',
        ':s008:' => '<img src="/img/smile/smile/s08.gif">',
        ':s009:' => '<img src="/img/smile/smile/s09.gif">',
        ':s010:' => '<img src="/img/smile/smile/s10.gif">',
        ':s011:' => '<img src="/img/smile/smile/s11.gif">',
        ':s012:' => '<img src="/img/smile/smile/s12.gif">',
        ':s013:' => '<img src="/img/smile/smile/s13.gif">',
        ':s014:' => '<img src="/img/smile/smile/s14.gif">',
        ':s015:' => '<img src="/img/smile/smile/s15.gif">',
        ':s016:' => '<img src="/img/smile/smile/s16.gif">',
        ':s017:' => '<img src="/img/smile/smile/s17.gif">',
        ':s018:' => '<img src="/img/smile/smile/s18.gif">',
        ':s019:' => '<img src="/img/smile/smile/s19.gif">',
        ':s020:' => '<img src="/img/smile/smile/s20.gif">',
        ':s021:' => '<img src="/img/smile/smile/s21.gif">',
        ':s022:' => '<img src="/img/smile/smile/s22.gif">',
        ':s023:' => '<img src="/img/smile/smile/s23.gif">',
        ':s024:' => '<img src="/img/smile/smile/s24.gif">',
        ':s025:' => '<img src="/img/smile/smile/s25.gif">',
        ':s026:' => '<img src="/img/smile/smile/s26.gif">',
        ':s027:' => '<img src="/img/smile/smile/s27.gif">',
        ':s028:' => '<img src="/img/smile/smile/s28.gif">',
        ':s029:' => '<img src="/img/smile/smile/s29.gif">',
        ':s030:' => '<img src="/img/smile/smile/s30.gif">',
        ':s031:' => '<img src="/img/smile/smile/s31.gif">',
        ':s032:' => '<img src="/img/smile/smile/s32.gif">',
        ':s033:' => '<img src="/img/smile/smile/s33.gif">',
        ':s034:' => '<img src="/img/smile/smile/s34.gif">',
        ':s035:' => '<img src="/img/smile/smile/s35.gif">',
        ':s036:' => '<img src="/img/smile/smile/s36.gif">',
        ':s037:' => '<img src="/img/smile/smile/s37.gif">',
        ':s038:' => '<img src="/img/smile/smile/s38.gif">',
        ':s039:' => '<img src="/img/smile/smile/s39.gif">',
        ':s040:' => '<img src="/img/smile/smile/s40.gif">',
        ':s041:' => '<img src="/img/smile/smile/s41.gif">',
        ':s042:' => '<img src="/img/smile/smile/s42.gif">',
        ':s100:' => '<img src="/img/smile/smile/s100.gif">',
        ':s101:' => '<img src="/img/smile/smile/s101.gif">',
        ':s102:' => '<img src="/img/smile/smile/s102.gif">',
        ':s103:' => '<img src="/img/smile/smile/s103.gif">',
        ':s104:' => '<img src="/img/smile/smile/s104.gif">',
        ':s105:' => '<img src="/img/smile/smile/s105.gif">',
        ':s106:' => '<img src="/img/smile/smile/s106.gif">',
        ':s107:' => '<img src="/img/smile/smile/s107.gif">',
        ':s108:' => '<img src="/img/smile/smile/s108.gif">',
        ':s109:' => '<img src="/img/smile/smile/s109.gif">',
        ':s110:' => '<img src="/img/smile/smile/s110.gif">',
        ':s111:' => '<img src="/img/smile/smile/s111.gif">',
        ':s112:' => '<img src="/img/smile/smile/s112.gif">',
        ':s113:' => '<img src="/img/smile/smile/s113.gif">',
        ':s114:' => '<img src="/img/smile/smile/s114.gif">',
        ':s115:' => '<img src="/img/smile/smile/s115.gif">',
        ':s116:' => '<img src="/img/smile/smile/s116.gif">',
        ':s117:' => '<img src="/img/smile/smile/s117.gif">',
        ':s118:' => '<img src="/img/smile/smile/s118.gif">',
        ':s119:' => '<img src="/img/smile/smile/s119.gif">',
        ':s120:' => '<img src="/img/smile/smile/s120.gif">',
        ':s121:' => '<img src="/img/smile/smile/s121.gif">',
        ':s122:' => '<img src="/img/smile/smile/s122.gif">',
        ':s123:' => '<img src="/img/smile/smile/s123.gif">',
        ':s124:' => '<img src="/img/smile/smile/s124.gif">',
        ':s125:' => '<img src="/img/smile/smile/s125.gif">',
        ':s126:' => '<img src="/img/smile/smile/s126.gif">',
        ':s127:' => '<img src="/img/smile/smile/s127.gif">',
        ':s128:' => '<img src="/img/smile/smile/s128.gif">',
        ':s129:' => '<img src="/img/smile/smile/s129.gif">',
        ':s130:' => '<img src="/img/smile/smile/s130.gif">',
        ':s131:' => '<img src="/img/smile/smile/s131.gif">',
        ':s132:' => '<img src="/img/smile/smile/s132.gif">',
        ':s133:' => '<img src="/img/smile/smile/s133.gif">',
        ':s134:' => '<img src="/img/smile/smile/s134.gif">',
        ':s135:' => '<img src="/img/smile/smile/s135.gif">',
        ':s136:' => '<img src="/img/smile/smile/s136.gif">',
        ':s137:' => '<img src="/img/smile/smile/s137.gif">',
        ':s138:' => '<img src="/img/smile/smile/s138.gif">',
        ':s139:' => '<img src="/img/smile/smile/s139.gif">',
        ':s140:' => '<img src="/img/smile/smile/s140.gif">',
        ':s141:' => '<img src="/img/smile/smile/s141.gif">',
        ':s142:' => '<img src="/img/smile/smile/s142.gif">',
        ':s143:' => '<img src="/img/smile/smile/s143.gif">',
        ':s144:' => '<img src="/img/smile/smile/s144.gif">',
        ':s145:' => '<img src="/img/smile/smile/s145.gif">',
        ':s146:' => '<img src="/img/smile/smile/s146.png">'
    ];

    return strtr($text, $smiles);
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
 * @param string $text
 *
 * @return string
 */
function imgart($text)
{
    return preg_replace('#{{(.+?)}}#', '<img src="/imgart/$1">', $text);
}

/**
 * @param $text
 *
 * @return null|string|string[]
 */
function imgart_no_reg($text)
{
    return preg_replace(
        '#{{(.+?)}}#',
        '<img src="/img/imgss.jpg" width="74" height="20" alt="img"> ',
        $text
    );
}

/**
 * @param $text
 *
 * @return null|string
 */
function nickartGlobal($text)
{
    return preg_replace('#\|\|(.+?)\|\|#', '<b style="color:#747474">$1</b>', $text);
}

/**
 * @param $text
 *
 * @return mixed|null|string
 */
function nickart($text)
{
    return preg_replace_callback('#\|\|(.+?)\|\|#', function ($value) {
        $color = (!empty($_SESSION['login']) && $_SESSION['login'] === $value[1]) ? '#F00' : '#747474';

        return '<b style="color:' . $color . '">' . html($value[1]) . '</b>';
    }, $text);
}

/**
 * @param string $myCity
 * @param string $city
 *
 * @return int
 */
function getCityCompare($myCity, $city)
{
    return strcmp(mb_strtolower($myCity), mb_strtolower($city));
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

<?php

namespace App\Components\Parse;

/**
 * Class All
 */
class All extends NoSession
{
    /**
     * @param $txt
     *
     * @return mixed
     */
    protected function parseUrl($txt)
    {
        if (preg_match_all('#\[url=([^]]*)]#i', $txt, $match)) {
            foreach ((array)$match[1] as $val) {
                $txt = str_replace('[url=' . $val . ']',
                    '<a href="' . htmlspecialchars($val, ENT_QUOTES) . '" target="_blank">', $txt);
            }
        }

        return $txt;
    }

    /**
     * @param $txt
     *
     * @return mixed|string
     */
    protected function parseImg($txt)
    {
        if (preg_match_all('#\[(img)=?(.*?)\](.+?)\[/img]#', $txt, $match)) {
            foreach ((array)$match[3] as $key => $val) {
                $param = '';
                $wh = preg_split('#[=]#', $match[2][$key]);
                if (isset($wh[1], $wh[0]) && (int)$wh[0] < 800 && (int)$wh[1] < 800) {
                    $param = 'width:' . (int)$wh[0] . 'px;height:' . (int)$wh[1] . 'px';
                }
                $txt = str_replace($match[0][$key],
                    '<img class="new-imgart" style="' . $param . '" src="' . htmlspecialchars($val, ENT_QUOTES) . '">',
                    $txt);
            }
        }

        return $txt;
    }

    /**
     * @param $txt
     *
     * @return mixed|string
     */
    protected function parseVideo($txt)
    {
        return preg_replace_callback('#\[video]([^[]*)\[/video]#i', function ($item) {
            return '<br><iframe width="420" height="315" src="https://www.youtube.com/embed/' . $item[1] . '?iv_load_policy=3;showinfo=0;rel=0" frameborder="0" allowfullscreen></iframe>';
        }, $txt);
    }
}

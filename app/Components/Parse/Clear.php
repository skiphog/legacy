<?php

namespace App\Components\Parse;

/**
 * Class Clear
 */
class Clear extends GParse
{

    protected $replace = [
        '[b]'      => '',
        '[/b]'     => '',
        '[u]'      => '',
        '[/u]'     => '',
        '[i]'      => '',
        '[/i]'     => '',
        '[s]'      => '',
        '[/s]'     => '',
        '[*]'      => '',
        '[/*]'     => '',
        '[list]'   => '',
        '[/list]'  => '',
        '[listo]'  => '',
        '[/listo]' => '',
        '[/color]' => '',
        '[/size]'  => '',
        '[/font]'  => '',
        '[quote]'  => '',
        '[/quote]' => '',
        '[/url]'   => '',
        '[table]'  => '',
        '[/table]' => ''
    ];

    /**
     * @param $txt
     *
     * @return mixed
     */
    protected function parseBb($txt)
    {

        if (preg_match_all('#\[quote=([^]]*)]#i', $txt, $match)) {
            foreach ((array)$match[1] as $val) {
                $txt = str_replace('[quote=' . $val . ']', "[$val]", $txt);
            }
        }
        if (preg_match_all('#\[size=([^\]]*)]#i', $txt, $match)) {
            foreach ((array)$match[1] as $val) {
                $txt = str_replace('[size=' . $val . ']', '', $txt);
            }
        }

        if (preg_match_all('#\[color=([^\]]*)]#i', $txt, $match)) {
            foreach ((array)$match[1] as $val) {
                $txt = str_replace('[color=' . $val . ']', '', $txt);
            }
        }
        $txt = preg_replace_callback('#\[spoiler=%(.*?)%](.*?)\[/spoiler]#is', function ($val) {
            return $val[2];
        }, $txt);

        return $txt;
    }
}

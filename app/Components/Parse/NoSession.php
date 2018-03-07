<?php

namespace App\Components\Parse;

/**
 * Class NoSession
 */
class NoSession extends GParse
{
    protected $replace = [
        '[b]'      => '<strong>',
        '[/b]'     => '</strong>',
        '[u]'      => '<span style="text-decoration: underline;">',
        '[/u]'     => '</span>',
        '[i]'      => '<i>',
        '[/i]'     => '</i>',
        '[s]'      => '<span style="text-decoration: line-through;">',
        '[/s]'     => '</span>',
        '[*]'      => '<li>',
        '[/*]'     => '</li>',
        '[list]'   => '<ul>',
        '[/list]'  => '</ul>',
        '[listo]'  => '<ol>',
        '[/listo]' => '</ol>',
        '[/color]' => '</span>',
        '[/size]'  => '</span>',
        '[/font]'  => '</span>',
        '[quote]'  => '<blockquote class="wwblock"><div class="wwquote">',
        '[/quote]' => '</div></blockquote>',
        '[/url]'   => '</a>',
        '[table]'  => '',
        '[/table]' => '',
        '[tr]'     => '',
        '[/tr]'    => '',
        '[td]'     => '',
        '[/td]'    => ''
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
                $txt = str_replace('[quote=' . $val . ']', '<blockquote class="wwblock"><cite>' . htmlspecialchars($val,
                        ENT_QUOTES) . ' писал(а)</cite><div class="wwquote">', $txt);
            }
        }

        if (preg_match_all('#\[size=([^\]]*)]#i', $txt, $match)) {
            foreach ((array)$match[1] as $val) {
                $txt = str_replace('[size=' . $val . ']', '<span style="font-size:' . (int)$val . '%">', $txt);
            }
        }

        if (preg_match_all('#\[color=([^\]]*)]#i', $txt, $match)) {
            foreach ((array)$match[1] as $val) {
                $txt = str_replace('[color=' . $val . ']',
                    '<span style ="color:' . htmlspecialchars($val, ENT_QUOTES) . '">', $txt);
            }
        }

        $txt = preg_replace_callback('#\[spoiler=%(.*?)%](.*?)\[/spoiler]#is', function ($val) {
            $title = !empty($val[1]) ? $val[1] : 'SPOILER';

            return '<div class="spoler-wrap"><div class="spoiler-head folder" onclick="spoiler(this);">' . $title . '</div><div class="spoler-body" style="display: none">' . $val[2] . '</div></div>';
        }, $txt);

        return $txt;
    }
}

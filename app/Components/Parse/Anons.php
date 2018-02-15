<?php

namespace Swing\Components\Parse;

/**
 * Class Anons
 */
class Anons extends All
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
                $txt = str_replace('[url=' . $val . ']', "[[$val || ", $txt);
            }
            $txt = str_replace('[/url]', ']]', $txt);
        }

        return $txt;
    }
}

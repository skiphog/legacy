<?php

namespace Swing\Components\Parse;

/**
 * Class GParse
 */
abstract class GParse
{

    protected $replace = [];

    /**
     * @param $txt
     *
     * @return string
     */
    public function parse($txt): string
    {
        $txt = str_replace('http://swing-kiska.ru/', 'https://swing-kiska.ru/', $txt);
        $txt = $this->parseBb($txt);
        $txt = $this->parseUrl($txt);
        $txt = $this->parseImg($txt);
        $txt = $this->parseVideo($txt);

        return strtr($txt, $this->replace);
    }

    /**
     * @param $txt
     *
     * @return mixed
     */
    abstract protected function parseBb($txt);

    /**
     * @param $txt
     *
     * @return mixed
     */
    protected function parseUrl($txt)
    {
        $txt = preg_replace('#\[url=([^]]*)]#i', '', $txt);

        return str_replace('[/url]', '', $txt);
    }

    /**
     * @param $txt
     *
     * @return mixed|string
     */
    protected function parseImg($txt)
    {
        if (preg_match_all('#\[(img)=?(.*?)\](.+?)\[/img]#', $txt, $match)) {
            $txt = '<img src="/img/imgss.jpg" width="74" height="20" alt="img"><br>' . $txt;

            foreach ((array)$match[3] as $key => $val) {
                $txt = str_replace($match[0][$key], '', $txt);
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
        if (preg_match_all('#\[video]([^[]*)\[/video]#i', $txt, $match)) {
            $txt = '<img src="/img/youtube.jpg" width="39" height="20" alt="video">' . $txt;

            foreach ((array)$match[1] as $val) {
                $txt = str_replace('[video]' . $val . '[/video]', '', $txt);
            }
        }

        return $txt;
    }
}

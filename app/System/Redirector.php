<?php

namespace Swing\System;

class Redirector
{
    /**
     * @param string $url
     *
     * @return string
     */
    public function redirect($url = '/'): ?string
    {
        header('Location: ' . $url);

        die;
    }

    /**
     * @param $url
     * @param $name
     * @param $value
     *
     * @return string
     */
    public function redirectWith($url, $name, $value = null): ?string
    {
        $data = \is_array($name) ? $name : [$name => $value];

        foreach ($data as $key => $item) {
            $_SESSION[$key] = $item;
        }

        return $this->redirect($url);
    }
}

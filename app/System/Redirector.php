<?php

namespace Swing\System;

/**
 * Class Redirector
 *
 * @package Swing\System
 */
class Redirector
{
    /**
     * @param string $url
     *
     * @return Redirector
     */
    public function redirect($url = '/'): Redirector
    {
        header('Location: ' . $url);

        return $this;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return Redirector
     */
    public function with($name, $value = null): Redirector
    {
        $data = \is_array($name) ? $name : [$name => $value];

        foreach ($data as $key => $item) {
            $_SESSION[$key] = $item;
        }

        return $this;
    }
}

<?php

namespace Swing\System;

/**
 * Class Request
 *
 * @package Swing\System
 */
class Request
{

    /**
     * @param null $param
     *
     * @return array|mixed|null
     */
    public function get($param = null)
    {
        unset($_GET['c'], $_GET['a']);

        return $this->getRequest($_GET, $param);
    }

    /**
     * @param null $param
     *
     * @return array|mixed|null
     */
    public function post($param = null)
    {
        return $this->getRequest($_POST, $param);
    }

    /**
     * @param array      $data
     * @param array|null $params
     *
     * @return array|mixed|null
     */
    protected function getRequest(array &$data, $params)
    {
        if (null === $params) {
            return $data;
        }

        $params = (array)$params;

        if (\count($params) === 1) {
            return $data[array_shift($params)] ?? null;
        }

        return array_intersect_key($data, array_flip($params));
    }

    /**
     * @return string
     */
    public static function uri(): string
    {
        return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    /**
     * @return string
     */
    public static function type(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

}

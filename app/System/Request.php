<?php

namespace Swing\System;

/**
 * Class Request
 *
 * @package App\System
 */
class Request
{
    public const INT = 'sanitizeInt';

    public const STRING = 'sanitizeString';

    public function __construct()
    {
        unset($_GET['c'], $_GET['a']);
    }

    /**
     * @param mixed $params
     * @param mixed $options
     *
     * @return mixed
     */
    public function get($params = null, $options = null)
    {
        return $this->getRequest($_GET, $params, $options);
    }

    /**
     * @param mixed $params
     * @param mixed $options
     *
     * @return mixed
     */
    public function post($params = null, $options = null)
    {
        return $this->getRequest($_POST, $params, $options);
    }

    /**
     * @param mixed $params
     *
     * @return mixed
     */
    public function getInteger($params = null)
    {
        return $this->get($params, self::INT);
    }

    /**
     * @param mixed $params
     *
     * @return mixed
     */
    public function postInteger($params = null)
    {
        return $this->post($params, self::INT);
    }

    /**
     * @param mixed $params
     *
     * @return mixed
     */
    public function getString($params = null)
    {
        return $this->get($params, self::STRING);
    }

    /**
     * @param mixed $params
     *
     * @return mixed
     */
    public function postString($params = null)
    {
        return $this->post($params, self::STRING);
    }

    /**
     * @param mixed $params
     * @param mixed $options
     *
     * @return array
     */
    public function getValues($params = null, $options = null): array
    {
        return array_values((array)$this->get($params, $options));
    }

    /**
     * @param mixed $params
     * @param mixed $options
     *
     * @return array
     */
    public function postValues($params = null, $options = null): array
    {
        return array_values((array)$this->post($params, $options));
    }

    /**
     * @param mixed $params
     *
     * @return array
     */
    public function getValuesInteger($params = null): array
    {
        return $this->getValues($params, self::INT);
    }

    /**
     * @param mixed $params
     *
     * @return array
     */
    public function getValuesString($params = null): array
    {
        return $this->getValues($params, self::STRING);
    }

    /**
     * @param mixed $params
     *
     * @return array
     */
    public function postValuesInteger($params = null): array
    {
        return $this->postValues($params, self::INT);
    }

    /**
     * @param mixed $params
     *
     * @return array
     */
    public function postValuesString($params = null): array
    {
        return $this->postValues($params, self::STRING);
    }


    /**
     * @param array $data
     * @param mixed $params
     * @param mixed $options
     *
     * @return mixed
     */
    protected function getRequest(array &$data, $params, $options)
    {
        $result = $this->getAllRequest($data, $params);

        if (null !== $options) {
            $result = $this->$options($result);
        }

        return $result;
    }

    /**
     * @param array $data
     * @param mixed $params
     *
     * @return mixed
     */
    protected function getAllRequest(array &$data, $params)
    {
        if (null === $params) {
            return $data;
        }

        $result = [];

        foreach ((array)$params as $param) {
            $result[$param] = $data[$param] ?? null;
        }

        return \count($result) === 1 ? array_shift($result) : $result;
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    protected function sanitizeInt($data)
    {
        return $this->sanitize($data, function ($value) {
            return abs((int)$value);
        });
    }

    /**
     * @param mixed $data
     *
     * @return mixed
     */
    protected function sanitizeString($data)
    {
        return $this->sanitize($data, function ($value) {
            return null !== $value ? trim(strip_tags($value)) : null;
        });
    }


    /**
     * @param mixed    $data
     * @param callable $callback
     *
     * @return mixed
     */
    protected function sanitize($data, callable $callback)
    {
        if (true === \is_array($data)) {
            return array_map(function ($value) use ($callback) {
                return $this->sanitize($value, $callback);
            }, $data);
        }

        return $callback($data);
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

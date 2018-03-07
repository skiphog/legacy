<?php

namespace System;

/**
 * Class Setting
 *
 * @package System
 */
class Setting
{
    /**
     * @var array $config
     */
    protected $config;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config.php';
    }

    /**
     * Получить значение из конфига
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        return self::stackArray($key, $this->config);
    }

    /**
     * Установить значение в конфиг
     *
     * @param  mixed     $key
     * @param null|mixed $value
     *
     * @return Setting
     */
    public function set($key, $value = null): Setting
    {
        $data = \is_array($key) ? $key : [$key => $value];

        foreach ($data as $index => $item) {
            $this->config[$index] = $item;
        }

        return $this;
    }

    /**
     * Получить значение из конфига через точку [key.key]
     *
     * @param string $key
     * @param array  $array
     *
     * @return array|mixed
     */
    public static function stackArray($key, array $array)
    {
        foreach (explode('.', $key) as $value) {
            if (!array_key_exists($value, $array)) {
                throw new \InvalidArgumentException('Параметр { ' . $key . ' } в конфиге не найден.');
            }

            $array = $array[$value];
        }

        return $array;
    }
}

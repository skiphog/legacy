<?php

namespace Swing\System;

/**
 * Class App
 *
 * @package Swing\System
 */
class App
{
    /**
     * Хранилище
     *
     * @var array $registry
     */
    protected static $registry = [];

    /**
     * Получить экземпляр зарегистрированного класса для использования как Singleton
     *
     * @param string $name
     *
     * @return mixed
     */
    public static function get($name)
    {
        if (array_key_exists($name, self::$registry)) {
            return self::$registry[$name];
        }

        throw new \InvalidArgumentException('Not exists <b>' . $name . '</b> in App container');
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public static function set($name, $value): void
    {
        self::$registry[$name] = $value;
    }
}

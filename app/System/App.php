<?php

namespace App\System;

/**
 * Class App
 *
 * @package App\System
 */
class App
{
    /**
     * Определения
     *
     * @var array $definitions
     */
    protected static $definitions = [];

    /**
     * Хранилище
     *
     * @var array $registry
     */
    protected static $registry = [];

    /**
     * Получить объект из контейнера
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

        if (array_key_exists($name, self::$definitions)) {
            $item = self::$definitions[$name];

            return self::$registry[$name] = $item instanceof \Closure ? $item() : $item;
        }

        if (class_exists($name)) {
            return self::$registry[$name] = new $name();
        }

        throw new \InvalidArgumentException('Не известный сервис [ ' . $name . ' ]');
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public static function set($name, $value): void
    {
        if (array_key_exists($name, self::$registry)) {
            unset(self::$registry[$name]);
        }

        self::$definitions[$name] = $value;
    }
}

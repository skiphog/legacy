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
     * Хранилище классов
     *
     * @var array $registry
     */
    protected static $registry = [];

    /**
     * Получить экземпляр зарегистрированного класса для использования как Singleton
     *
     * @param string $className
     *
     * @return mixed
     */
    public static function get($className)
    {
        if (array_key_exists($className, self::$registry)) {
            return self::$registry[$className];
        }

        // todo:: try cache
        return self::$registry[$className] = new $className;
    }
}

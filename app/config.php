<?php

return [

    /**
     * Имя приложения
     */
    'domain'        => 'swing',

    /**
     * Использовать HTTPS
     */
    'secure'        => false,

    /**
     * Настройка соединения с базой данных
     */
    'db'            => [
        'host'     => 'localhost',
        'dbname'   => 'skip',
        'username' => 'root',
        'password' => '',
        'options'  => [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            //\PDO::ATTR_EMULATE_PREPARES  => false,
            //\PDO::ATTR_STRINGIFY_FETCHES => false
        ]
    ],

    /**
     * Драйвер для кеширования
     */
    'cache_driver'  => \App\System\Cache::class,

    /**
     * Время онлайна
     */
    'activity_time' => 600
];

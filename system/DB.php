<?php

namespace System;

/**
 * Class DB
 *
 * @package System
 */
class DB
{
    protected $dbh;

    public function __construct()
    {
        try {
            $config = config('db');
            $this->dbh = new \PDO(
                'mysql:dbname=' . $config['dbname'] . ';host=' . $config['host'],
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (\Exception $e) {
            //todo:: Пробросить исключение дальше
            die($e->getMessage());
        }
    }

    /**
     * @return \PDO
     */
    public function dbh(): \PDO
    {
        return $this->dbh;
    }
}

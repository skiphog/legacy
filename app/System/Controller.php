<?php

namespace Swing\System;


use Swing\Models\Myrow;
use Swing\Exceptions\ForbiddenException;

/**
 * Class Controller
 *
 * @package Swing\System
 */
abstract class Controller
{
    /**
     * @var Request $request
     */
    public $request;

    /**
     * @var \PDO
     */
    public $dbh;

    /**
     * @var Cache $cache
     */
    public $cache;

    /**
     * Myrow - так повелось на старом сайте
     *
     * @var Myrow $myrow
     */
    public $myrow;

    public function __construct()
    {
        $this->request = request();
        $this->dbh = db();
        $this->cache = cache();
        $this->myrow = user();
    }

    /**
     * @param string $action
     *
     * @return mixed
     * @throws ForbiddenException
     */
    public function action($action)
    {
        $this->before();

        if (false === $this->access()) {
            throw new ForbiddenException('Доступ запрещен');
        }

        if (($result = $this->$action()) instanceof Response) {
            return $result();
        }

        $this->after();

        return $this->page($result);
    }

    /**
     * Middleware
     *
     * @return bool
     */
    protected function access(): bool
    {
        return true;
    }

    /**
     * Инициализация
     */
    protected function before(): void
    {

    }

    /**
     * Post middleware
     */
    protected function after(): void
    {
        $this->myrow->setTimeStamp();
    }

    /**
     * Выыводит результат
     *
     * @param $result
     *
     * @return mixed
     */
    protected function page($result)
    {
        echo $result;

        return true;
    }
}

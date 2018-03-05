<?php

namespace Swing\System;

use Swing\Exceptions\ForbiddenException;

/**
 * Class Controller
 *
 * @package Swing\System
 */
abstract class Controller
{
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
        auth()->setTimeStamp();
    }

    /**
     * @throws ForbiddenException
     */
    protected function accessAuthUser(): void
    {
        if (auth()->isGuest()) {
            throw new ForbiddenException('Только для зарегистрированных пользователей');
        }
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

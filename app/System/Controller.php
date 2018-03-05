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
     * @throws ForbiddenException
     */
    public function action($action): void
    {
        if (!$this->access()) {
            throw new ForbiddenException('Доступ запрещен');
        }

        $this->before();

        echo $this->$action();
    }

    /**
     * Access
     *
     * @return bool
     */
    protected function access(): bool
    {
        return true;
    }

    protected function before(): void
    {

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
}

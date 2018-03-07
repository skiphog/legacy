<?php

namespace App\System;

use App\Exceptions\ForbiddenException;

/**
 * Class Controller
 *
 * @package App\System
 */
abstract class Controller
{
    /**
     * @param string  $action
     *
     * @param Request $request
     *
     * @return Response
     * @throws ForbiddenException
     */
    public function callAction($action, Request $request): Response
    {
        if (!method_exists($this, $action)) {
            throw new \BadMethodCallException('Метод ' . $action . ' в контроллере ' . static::class . ' не найден');
        }

        if (!$this->access()) {
            throw new ForbiddenException('Доступ запрещен');
        }

        $this->before();

        return $this->$action($request);
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

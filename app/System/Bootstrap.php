<?php

namespace Swing\System;

use Swing\Exceptions\ForbiddenException;
use Swing\Exceptions\NotFoundException;

/**
 * Class Bootstrap
 *
 * @package Swing\System
 */
class Bootstrap
{
    /**
     * @return mixed
     */
    public static function run()
    {
        $controller = 'Swing\\Controllers\\' . ucfirst($_GET['c'] ?? 'Index') . 'Controller';
        $action = Request::type() . ucfirst($_GET['a'] ?? 'Index');

        try {
            if (!class_exists($controller)) {
                throw new \BadMethodCallException('Контроллера ' . $controller . ' не существует');
            }

            if (!method_exists($controller, $action)) {
                throw new \BadMethodCallException('Метод ' . $action . ' в контроллере ' . $controller . ' не найден');
            }
            /** @var Controller $controller */
            $controller = new $controller();

            return $controller->action($action);
        } catch (NotFoundException | ForbiddenException | \BadMethodCallException | \InvalidArgumentException$e) {
            http_response_code(404);
            var_dump(
                $e->getMessage(),
                $e->getCode(),
                $e->getTrace(),
                $e->getFile(),
                $e->getLine()
            );
        }
    }
}

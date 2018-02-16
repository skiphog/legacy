<?php

namespace Swing\System;

use Swing\Exceptions\ForbiddenException;

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
        $controller = 'Swing\\Controllers\\' . ucfirst($_REQUEST['c'] ?? 'Index') . 'Controller';
        $action = Request::type() . ucfirst($_REQUEST['a'] ?? 'Index');

        try {
            if (!class_exists($controller)) {
                throw new \BadMethodCallException('Контроллера ' . $controller . ' не существует');
            }

            if (!method_exists($controller, $action)) {
                throw new \BadMethodCallException('Метод ' . $action . ' в контроллере ' . $controller . ' не найден');
            }
            /** @var Controller $controller */
            $controller = new $controller();

            return $controller->action($action, new Request());
        } catch (ForbiddenException | \BadMethodCallException | \InvalidArgumentException$e) {
            http_response_code(403);
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

<?php

namespace Swing\System;

use Swing\Exceptions\ForbiddenException;

/**
 * Class BootStrap
 *
 * @package Swing\System
 */
class BootStrap
{
    /**
     * @return mixed
     */
    public static function run()
    {
        $controller = 'Swing\\Controllers\\' . ucfirst($_REQUEST['c'] ?? 'Index') . 'Controller';
        $action = 'action' . ucfirst($_REQUEST['a'] ?? 'Default');

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
        } catch (ForbiddenException | \BadMethodCallException $e) {
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

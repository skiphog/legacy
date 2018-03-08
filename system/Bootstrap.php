<?php

namespace System;

use System\Cache\Cache;

/**
 * Class Bootstrap
 *
 * @package System
 */
class Bootstrap
{
    public function run(): void
    {
        try {
            $request = request();
            [$controller, $action, $attributes] = Router::load()->match($request);
            $request->setAttributes($attributes);

            $controller = $this->getController($controller);

            $this->setRegistry();

            $response = $controller->callAction($action, $request);

            echo $response;
        } catch (\Exception $e) {
            http_response_code(404);
            var_dump(
                $e
            );
        }
    }

    /**
     * @param $controller
     *
     * @return Controller
     */
    protected function getController($controller): Controller
    {
        $controller = 'App\\Controllers\\' . $controller;

        if (!class_exists($controller)) {
            throw new \BadMethodCallException('Контроллера ' . $controller . ' не существует');
        }

        return new $controller;
    }

    protected function setRegistry(): void
    {
        Container::set('cache', function () {
            $class = config('cache_driver');

            return new Cache(new $class);
        });
    }
}

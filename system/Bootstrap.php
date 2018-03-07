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
            /** @var Request $request */
            $request = request();
            $result = Router::load()->match($request);

            $request->setAttributes($result[1]);
            [$controller, $action] = explode('@', $result[0]);

            $controller = 'App\\Controllers\\' . $controller;

            if (!class_exists($controller)) {
                throw new \BadMethodCallException('Контроллера ' . $controller . ' не существует');
            }

            /** @var \System\Controller $controller */
            $controller = new $controller;

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

    protected function setRegistry(): void
    {
        Container::set('cache', function () {
            $class = config('cache_driver');
            return new Cache(new $class);
        });
    }
}

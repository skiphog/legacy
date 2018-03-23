<?php

namespace System;

/**
 * Class Router
 *
 * @package System
 */
class Router
{
    public $routes = [
        'GET'  => [],
        'POST' => []
    ];

    /**
     * @return Router
     */
    public static function load(): Router
    {
        $route = new static();
        require __DIR__ . '/../app/route.php';

        return $route;
    }

    /**
     * @param string $pattern
     * @param string $handler
     */
    public function get($pattern, $handler): void
    {
        $this->setRoute('GET', $pattern, $handler);
    }

    /**
     * @param string $pattern
     * @param string $handler
     */
    public function post($pattern, $handler): void
    {
        $this->setRoute('POST', $pattern, $handler);
    }

    /**
     * @param Request $request
     *
     * @return array
     * @throws \InvalidArgumentException
     */
    public function match(Request $request): array
    {
        $uri = $request->uri();

        foreach ((array)$this->routes[$request->type()] as $pattern => $handler) {
            if (preg_match('#^' . $this->getPattern($pattern) . '$#', $uri, $matches)) {
                return array_merge(explode('@', $handler), [$matches]);
            }
        }

        throw new \InvalidArgumentException('Роут ' . $request->type() . ' [' . $uri . '] не зарегистрирован');
    }

    /**
     * @param string $method
     * @param string $pattern
     * @param string $handler
     */
    protected function setRoute($method, $pattern, $handler): void
    {
        $this->routes[$method][trim($pattern, '/')] = $handler;
    }

    /**
     * @param string $pattern
     *
     * @return string
     */
    protected function getPattern($pattern): string
    {
        return preg_replace_callback('#{([^\}:]+):?([^\}]*?)\}#', function ($matches) {
            return '(?P<' . $matches[1] . '>' . ($matches[2] ?: '.+') . ')';
        }, $pattern);
    }
}

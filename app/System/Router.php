<?php

namespace App\System;

/**
 * Class Router
 *
 * @package App\System
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
        require __DIR__ . '/../route.php';

        return $route;
    }

    /**
     * @param string $pattern
     * @param string $handler
     * @param array  $tokens
     */
    public function get($pattern, $handler, array $tokens = []): void
    {
        $this->setRoute('GET', $pattern, $handler, $tokens);
    }

    /**
     * @param string $pattern
     * @param string $handler
     * @param array  $tokens
     */
    public function post($pattern, $handler, array $tokens = []): void
    {
        $this->setRoute('POST', $pattern, $handler, $tokens);
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
            $pattern = $this->getPattern($pattern, current($handler));

            if (preg_match('#^' . $pattern . '$#', $uri, $matches)) {
                return [
                    key($handler),
                    $matches
                ];
            }
        }

        throw new \InvalidArgumentException('Роут ' . $request->type() . ' [' . $uri . '] не зарегистрирован');
    }

    /** @noinspection MoreThanThreeArgumentsInspection */
    /**
     * @param string $method
     * @param string $pattern
     * @param string $handler
     * @param array  $tokens
     */
    protected function setRoute($method, $pattern, $handler, $tokens): void
    {
        $this->routes[$method][trim($pattern, '/')] = [$handler => $tokens];
    }

    /**
     * @param string $pattern
     * @param array  $handler
     *
     * @return mixed
     */
    protected function getPattern($pattern, array $handler)
    {
        return preg_replace_callback('#{([^\}]+)\}#', function ($matches) use ($handler) {
            return '(?P<' . $matches[1] . '>' . ($handler[$matches[1]] ?? '[^}]+') . ')';
        }, $pattern);
    }
}

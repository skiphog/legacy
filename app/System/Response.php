<?php

namespace Swing\System;

/**
 * Class Redirector
 *
 * @package Swing\System
 */
class Response
{

    protected $data;

    /**
     * @param string $url
     *
     * @return Response
     */
    public function redirect(string $url): Response
    {
        $this->setHeader('Location: ' . $url, 302);

        return $this;
    }

    /**
     * @param mixed $data
     * @param int   $code
     *
     * @return Response
     */
    public function json($data, $code): Response
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        return $this->setJson($json, $code);
    }

    /**
     * Записывает данные в сессию
     *
     * @param $name
     * @param $value
     *
     * @return Response
     */
    public function with($name, $value = null): Response
    {
        $data = \is_array($name) ? $name : [$name => $value];

        foreach ($data as $key => $item) {
            $_SESSION[$key] = $item;
        }

        return $this;
    }

    /**
     * Устанвливает Куки
     *
     * @param string|array $name
     * @param string|null  $value
     *
     * @return Response
     */
    public function withCookie($name, $value = null): Response
    {
        $data = \is_array($name) ? $name : [$name => $value];

        foreach ($data as $key => $item) {
            setcookie($key, $value, 0x7FFFFFFF, '/', config('domain'), false, true);
        }

        return $this;
    }

    /**
     * Устанавливает заголовок
     *
     * @param string $header
     * @param int    $code
     * @param bool   $replace
     */
    protected function setHeader($header, $code, $replace = true): void
    {
        header($header, $replace, $code);
    }

    /**
     * @param string $json
     * @param int    $code
     *
     * @return $this
     */
    protected function setJson($json, $code): Response
    {
        $this->setHeader('Content-Type: application/json;charset=utf-8', $code);
        $this->data = $json;

        return $this;
    }

    /**
     * @return bool
     */
    public function __invoke()
    {
        if (null !== $this->data) {
            echo $this->data;
        }

        return true;
    }

    /**
     * Устанавливает заголовок и прекращает работу приложения
     *
     * @param int         $code
     * @param string|null $data
     */
    public static function Abort(int $code, $data = null): void
    {
        http_response_code($code);

        if (null !== $data) {
            echo $data;
        }

        die;
    }
}

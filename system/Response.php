<?php

namespace System;

/**
 * Class Redirector
 *
 * @package System
 */
class Response
{
    protected $data;

    /**
     * @param mixed $data
     *
     * @return Response
     */
    public function setData($data): Response
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param string $url
     * @param int    $code
     *
     * @return Response
     */
    public function redirect($url, $code = 302): Response
    {
        $this->setHeader('Location: ' . $url, $code);

        return $this;
    }

    /**
     * @param mixed $data
     * @param int   $code
     *
     * @return Response
     */
    public function json($data, $code = 200): Response
    {
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException(json_last_error_msg());
        }

        $this->setHeader('Content-Type: application/json;charset=utf-8', $code);

        return $this->setData($json);
    }

    /**
     * Записывает данные в сессию
     *
     * @param $name
     * @param $value
     *
     * @return Response
     */
    public function withSession($name, $value = null): Response
    {
        $data = \is_array($name) ? $name : [$name => $value];

        foreach ($data as $key => $item) {
            $_SESSION[$key] = $item;
        }

        return $this;
    }

    /**
     * Записывает данные в куки
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
     * @param array $header
     * @param int   $code
     * @param bool  $replace
     *
     * @return Response
     */
    public function withHeaders(array $header, $code = 200, $replace = true): Response
    {
        foreach ($header as $key => $value) {
            $this->setHeader($key . ': ' . $value, $code, $replace);
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
     * Устанавливает заголовок и прекращает работу приложения
     *
     * @param int   $code
     * @param mixed $data
     */
    public function abort(int $code, $data = null): void
    {
        http_response_code($code);
        echo $data;

        die;
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->data;
    }
}
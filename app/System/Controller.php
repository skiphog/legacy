<?php

namespace Swing\System;


use Swing\Models\Myrow;
use Swing\Exceptions\ForbiddenException;

/**
 * Class Controller
 *
 * @package Swing\System
 */
abstract class Controller
{
    /**
     * @var Myrow $myrow
     */
    public $myrow;

    /**
     * @var Cache $cache
     */
    public $cache;

    /**
     * @var \PDO
     */
    public $dbh;

    /**
     * @var array $meta
     */
    public $meta;

    /**
     * @var mixed $response
     */
    public $content;

    /**
     * @param string  $action
     * @param Request $request
     *
     * @return mixed
     * @throws ForbiddenException
     */
    public function action($action, Request $request)
    {
        $this->init();

        if (false === $this->assess()) {
            throw new ForbiddenException('Доступ запрещен');
        }

        if (($response = $this->$action($request)) instanceof Response) {
            return $response();
        }

        $this->after();

        return $this->page();
    }

    /**
     * Middleware
     *
     * @return bool
     */
    protected function assess(): bool
    {
        return true;
    }

    /**
     * Инициализирует классы
     *
     * //todo:: всё убрать и сделать инициализацию при старте приложения // после изменений шаблонов
     */
    protected function init(): void
    {
        $this->myrow = user();
        $this->cache = cache();
        $this->dbh = db();
    }

    /**
     * Post middleware
     */
    protected function after(): void
    {

    }

    /**
     * Если сформирован шаблон страницы, то подключает Layout
     */
    protected function page()
    {
        $this->myrow->setTimeStamp();

        return require __PATH__ . '/app/template/layout/layout.php';
    }

    /**
     * @param string $path
     * @param array  $params
     *
     * @return string
     */
    protected function render(string $path, array $params = []): string
    {
        extract($params, EXTR_SKIP);
        ob_start();
        /** @noinspection PhpIncludeInspection */
        require __PATH__ . '/app/template/' . $path . '.php';

        return ob_get_clean();
    }

    /**
     * @param string $path
     * @param array  $params
     *
     * @return mixed
     */
    protected function view(string $path, array $params = [])
    {
        $this->content = $this->render($path, $params);

        return true;
    }
}

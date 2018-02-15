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
     * @var array $content
     */
    public $content;

    /**
     * @param $action
     *
     * @return mixed
     * @throws ForbiddenException
     */
    public function action($action)
    {
        if (!$this->assess()) {
            throw new ForbiddenException('Доступ запрещен');
        }

        $this->init();
        $this->$action();
        $this->after();

        return $this->page();
    }

    /**
     * @return bool
     */
    protected function assess(): bool
    {
        return true;
    }

    /**
     * Инициализирует классы
     */
    protected function init(): void
    {
        $this->myrow = user();
        $this->cache = cache();
        $this->dbh = db();
    }

    protected function after()
    {
        $this->myrow->setTimeStamp();
    }

    /**
     * @return mixed
     */
    protected function page()
    {
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
     * @return bool
     */
    protected function view(string $path, array $params = []): bool
    {
        $this->content = $this->render($path, $params);

        return true;
    }
}

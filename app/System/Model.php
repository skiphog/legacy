<?php

namespace App\System;

/**
 * Class Model
 *
 * @package App\System
 */
abstract class Model
{

    /**
     * @param $name
     * @param $value
     *
     * @return int
     */
    public function __set($name, $value)
    {
        $method = $this->generateMethod('set', $name);

        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        return is_numeric($value) ? $this->{$name} = (int)$value : $this->{$name} = $value;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($this->{$name});
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $method = $this->generateMethod('get', $name);

        return method_exists($this, $method) ? $this->$method($name) : null;
    }

    /**
     * Генерирует метод
     *
     * @param string $particle
     * @param string $data
     *
     * @return string
     */
    protected function generateMethod($particle, $data): string
    {
        $method = array_map(function ($v) {
            return ucfirst($v);
        }, explode('_', $data));

        return $particle . implode('', $method);
    }
}

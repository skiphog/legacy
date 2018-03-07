<?php

namespace System\Cache;

/**
 * Interface CacheDriverInterface
 *
 * @package System\Cache
 */
interface CacheDriverInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed  $value
     * @param int    $time
     *
     * @return bool
     */
    public function set($key, $value, $time): bool;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key): bool;
}

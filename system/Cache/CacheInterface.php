<?php

namespace System\Cache;

/**
 * Interface CacheInterface
 *
 * @package System\Cache
 */
interface CacheInterface
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

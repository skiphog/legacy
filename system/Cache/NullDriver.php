<?php

namespace System\Cache;

/**
 * Class NullDriver
 *
 * @package System\Cache
 */
class NullDriver implements CacheInterface
{
    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return false;
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param int    $time
     *
     * @return bool
     */
    public function set($key, $value, $time): bool
    {
        return true;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key): bool
    {
        return true;
    }
}

<?php

namespace System\Cache;

/**
 * Class MemcachedDriver
 *
 * @package System\Cache
 */
class MemcachedDriver implements CacheDriverInterface
{
    protected $memcached;

    public function __construct()
    {
        $this->memcached = new \Memcached();
        $this->memcached->addServer('localhost', 11211);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->memcached->get($key);
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
        return $this->memcached->set($key, $value, $time);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key): bool
    {
        return $this->memcached->delete($key);
    }
}

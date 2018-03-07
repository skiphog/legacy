<?php

namespace App\System;

/**
 * Class Cache
 *
 * @package App\System
 */
class Cache
{
    private $cache;

    public function __construct()
    {
        //$this->cache = new \Memcached();
        //$this->cache->addServer('localhost', 11211);
    }

    public function get($key)
    {
        //return $this->cache->get($key);
        return false;
    }

    public function set($key, $value, $time = 0)
    {
        //return $this->cache->set($key, $value, $time);
        return true;
    }

    public function delete($key)
    {
        //return $this->cache->delete($key);
        return true;
    }

    public function getDelayed($keys, $with_cas = false)
    {
        //return $this->cache->getDelayed($keys, $with_cas);
        return true;
    }

    public function fetch()
    {
        //return $this->cache->fetch();
        return false;
    }
}

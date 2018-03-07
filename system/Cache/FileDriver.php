<?php

namespace System\Cache;

/**
 * Class FileDriver
 *
 * @package System\Cache
 */
class FileDriver implements CacheDriverInterface
{
    protected $path;

    public function __construct()
    {
        $this->path = __DIR__ . '/../../cache';
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $file = $this->path . '/' . $key;

        if (file_exists($file)) {
            $data = unserialize(file_get_contents($file), []);

            if ($data['time'] === 0 || $data['time'] > time()) {
                return $data['data'];
            }
            unlink($file);
        }

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
        $data = [
            'time' => $time ? time() + $time : 0,
            'data' => $value
        ];

        return file_put_contents($this->path . '/' . $key, serialize($data));
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key): bool
    {
        $file = $this->path . '/' . $key;

        return file_exists($file) ? @unlink($file) : true;
    }
}

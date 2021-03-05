<?php


namespace SynerBay\Helper;


use Exception;
use Memcached;

class MemcacheHelper
{
    private static bool $active = true;

    /** @var Memcached */
    private static Memcached $mc;

    public static bool $inited = false;

    public static function init()
    {
        try {
            self::$mc = new Memcached();

            if(!self::$mc->addServer('127.0.0.1', 11211)) {
                throw new Exception('Could not connect');
            }

            //        $version = self::$mc->getVersion();
//        echo "Server's version: ".implode(', ', $version)."<br/>\n";

        } catch (Exception $e) {
            self::$active = false;
        }

        self::$inited = true;
    }

    public static function get(string $key)
    {
        if (!self::$active) return false;

        return self::$mc->get($key);
    }

    public static function set(string $key, $data, $expire = null)
    {
        if (!self::$active) return false;

        $expire = is_null($expire) ? (15 * 60) : $expire;

        return self::$mc->set($key, $data, $expire);
    }

    public static function delete(string $key)
    {
        if (!self::$active) return false;

        return self::$mc->delete($key);
    }
}
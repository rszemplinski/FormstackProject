<?php

namespace JustTheBasicz;

use Noodlehaus\Config as NoodleHausConfig;

class Config extends NoodleHausConfig
{
    protected function getDefaults()
    {
        return [
            "app"   => [
                "id"          => "my-app",
                "name"        => "My Application",
                "environment" => "development",
                "debug"       => true
            ],
            "mysql" => [
                "host"      => "localhost",
                "database"  => "test",
                "username"  => "mysql_user",
                "password"  => "mysql_pass",
                "port"      => "3306",
                "charset"   => "utf8",
                "collation" => "utf8_unicode_ci"
            ]
        ];
    }

    private static $_instance = null;

    public static function instance()
    {
        if (self::$_instance == null) {
            if (!defined('APP_ROOT')) {
                $path = __DIR__ . '/../app/config.json';
            } else {
                $path = APP_ROOT . 'config.json';
            }
            if (!file_exists($path)) {
                $config = json_encode(self::getDefaults(), JSON_PRETTY_PRINT);
                file_put_contents($path, $config);
            }
            self::$_instance = new static($path);
        }
        return self::$_instance;
    }

    public static function read($key = '', $default = null)
    {
        if (empty($key)) {
            return self::instance()->all();
        }
        if (!self::exists($key)) {
            return $default;
        }
        return self::instance()->get($key);
    }

    public static function exists($key = '')
    {
        return self::instance()->has($key);
    }
}

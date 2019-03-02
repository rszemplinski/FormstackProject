<?php

namespace JustTheBasicz;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

class Database
{
    private static $_instance;

    public static function instance()
    {
        if (null === self::$_instance) {
            $capsule = new Capsule;
            $capsule->addConnection(self::getConfig());
            $capsule->setEventDispatcher(new Dispatcher(new Container));
            $capsule->setAsGlobal();
            self::$_instance = $capsule;
        }
        return self::$_instance;
    }

    public static function getConfig()
    {
        return array_merge(
            [
                'driver'    => 'mysql',
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ],
            Config::read('mysql', $default = [])
        );
    }

    public static function boot()
    {
        self::instance()->bootEloquent();
    }
}

<?php

namespace JustTheBasicz;

use InvalidArgumentException;
use \Philo\Blade\Blade;

class View
{
    private static $_instance = null;

    public static function instance()
    {
        if (null === self::$_instance) {
            self::$_instance = new Blade(
                APP_ROOT . 'views',
                PROJECT_ROOT . 'tmp' . DS . 'cache'
            );
        }
        return self::$_instance;
    }

    public static function render($template, $data = [])
    {
        $factory = self::instance()->view();
        $view = $factory->make($template, $data);
        return $view->render();
    }

    public static function renderJSON($data = [], $flags = null)
    {
        if (is_null($flags)) {
            $flags = JSON_HEX_TAG | JSON_HEX_APOS
                | JSON_HEX_AMP | JSON_HEX_QUOT
                | JSON_UNESCAPED_SLASHES;
        }
        json_encode(null); // clear json_last_error()
        $result = json_encode($data, $flags);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new InvalidArgumentException(
                sprintf(
                    'Unable to encode data to JSON in %s: %s',
                    __CLASS__,
                    json_last_error_msg()
                )
            );
        }
        return $result;
    }
}

<?php

namespace JustTheBasicz;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher\GroupCountBased as Dispatcher;

class Router
{
    const ERR_NOT_FOUND = 'route-not-found';
    const ERR_BAD_METHOD = 'method-not-allowed';
    const ERR_MISSING_CONTROLLER = 'missing-controller';
    const ERR_MISSING_ACTION = 'missing-action';

    private static $_instance;

    public static function instance()
    {
        if (null === self::$_instance) {
            $routeCollector = new RouteCollector(
                new \FastRoute\RouteParser\Std,
                new \FastRoute\DataGenerator\GroupCountBased
            );
            self::$_instance = $routeCollector;
        }
        return self::$_instance;
    }

    public static function dispatch()
    {
        $vars = [];
        $request = Request::createFromGlobals();
        $dispatcher = new Dispatcher(static::instance()->getData());
        $routeInfo = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );
        $controller = "\\JustTheBasicz\\Controller";
        $action = "index";

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $action = 'routerError';
                $vars['error'] = static::ERR_NOT_FOUND;
                $vars['subject'] = $request->getUri()->getPath();
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $action = 'routerError';
                $vars['error'] = static::ERR_BAD_METHOD;
                $vars['subject'] = $routeInfo[1];
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                if (!class_exists($handler[0])) {
                    $action = 'routerError';
                    $vars['error'] = static::ERR_MISSING_CONTROLLER;
                    $vars['subject'] = $handler[0];
                } elseif (!method_exists($handler[0], $handler[1])) {
                    $action = 'routerError';
                    $vars['error'] = static::ERR_MISSING_ACTION;
                    $vars['subject'] = $handler[0] . '::' . $handler[1];
                } else {
                    $controller = $handler[0];
                    $action = $handler[1];
                }
                break;
        }

        $response = new Response();
        $request->setAction($action);
        $instance = new $controller($request, $response);
        return call_user_func([$instance, $action], $vars);
    }

    protected static function callback($callback)
    {
        if (is_string($callback)) {
            if (strpos($callback, ':')) {
                if ($callback {
                    0} !== '\\') {
                    $callback = "\\App\\Controller\\{$callback}";
                }
                $callback = explode(':', $callback);
            } else {
                $callback = [$callback, 'index'];
            }
        }
        if (!is_array($callback)) {
            throw new \LogicException(
                "A route callback could not be understood."
                    . "Couldn't resolve to [class,action] array."
            );
        }
        return $callback;
    }

    public static function get($path, $callback)
    {
        self::instance()->addRoute('GET', $path, self::callback($callback));
    }

    public static function post($path, $callback)
    {
        self::instance()->addRoute('POST', $path, self::callback($callback));
    }

    public static function put($path, $callback)
    {
        self::instance()->addRoute('PUT', $path, self::callback($callback));
    }

    public static function delete($path, $callback)
    {
        self::instance()->addRoute('DELETE', $path, self::callback($callback));
    }

    public static function patch($path, $callback)
    {
        self::instance()->addRoute('PATCH', $path, self::callback($callback));
    }

    public static function options($path, $callback)
    {
        self::instance()->addRoute('OPTIONS', $path, self::callback($callback));
    }

    public static function map($methods, $path, $callback)
    {
        foreach ($methods as $httpMethod) {
            self::instance()->addRoute(
                $httpMethod,
                $path,
                self::callback($callback)
            );
        }
    }
}

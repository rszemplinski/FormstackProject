<?php

use JustTheBasicz\Database;
use JustTheBasicz\Router;
use JustTheBasicz\Response;


define('DS', DIRECTORY_SEPARATOR);
if (!defined('PROJECT_ROOT')) {
    define('PROJECT_ROOT', __DIR__ . DS);
}
define('APP_ROOT', PROJECT_ROOT . 'app' . DS);
define('CORE_ROOT', PROJECT_ROOT . 'core' . DS);

require_once(PROJECT_ROOT . 'vendor' . DS . 'autoload.php');

Database::boot();

require_once(APP_ROOT . 'routes.php');

$response = Router::dispatch();

Response::send($response);

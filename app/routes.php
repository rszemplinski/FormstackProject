<?php

use JustTheBasicz\Router;

Router::get('/', 'UserController:index');
Router::get('/user', 'UserController:test');

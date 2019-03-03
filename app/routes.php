<?php

use JustTheBasicz\Router;

Router::get('/user/{userId}', 'UserController:get');
Router::get('/user', 'UserController:getAll');
Router::post('/user', 'UserController:create');
Router::post('/user/upload_avatar/{userId}', 'UserController:uploadAvatar');
Router::put('/user/{userId}', 'UserController:update');
Router::delete('/user/{userId}', 'UserController:delete');

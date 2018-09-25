<?php

$router->post('register', 'UserController@register');
$router->post('login', 'AuthController@login');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->get('users', 'UserController@index');
});
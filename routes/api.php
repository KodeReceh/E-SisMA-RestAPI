<?php

$router->post('login', 'AuthController@login');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->post('users/register', 'UserController@register');
    $router->get('users', 'UserController@index');
});
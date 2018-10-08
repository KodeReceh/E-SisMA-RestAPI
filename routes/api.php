<?php

$router->post('login', 'AuthController@login');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->post('users/register', 'UserController@register');
    $router->get('users', 'UserController@index');
    $router->get('users/{user}', 'UserController@getUser');
    $router->post('users/update/{user}', 'UserController@update');
});
<?php

$router->post('login', 'AuthController@login');

$router->group(['middleware' => 'auth'], function () use ($router) {
    //user
    $router->post('users/register', 'UserController@register');
    $router->get('users', 'UserController@index');
    $router->get('users/{user}', 'UserController@getUser');
    $router->post('users/update/{user}', 'UserController@update');
    $router->delete('users/delete/{user}', 'UserController@delete');
    $router->post('users/change_status', 'UserController@changeStatus');

    //role
    $router->get('roles', 'RoleController@index');

    //letter
    $router->post('letters/incoming-letter', 'IncomingLetterController@store');
    $router->get('letters/incoming-letter', 'IncomingLetterController@index');
    $router->post('letters/outcoming-letter', 'OutcomingLetterController@store');
    $router->get('letters/outcoming-letter', 'OutcomingLetterController@index');
});
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

    //incoming-letter
    $router->post('letters/incoming-letter', 'IncomingLetterController@store');
    $router->get('letters/incoming-letter', 'IncomingLetterController@getList');
    $router->get('letters/incoming-letter/{id}', 'IncomingLetterController@get');
    $router->put('letters/incoming-letter/{id}', 'IncomingLetterController@update');
    $router->delete('letters/incoming-letter/{id}', 'IncomingLetterController@delete');
    // $router->get('letters/incoming-letter/get-list', 'IncomingLetterController@getList');
    
    //outcoming-letter
    $router->post('letters/outcoming-letter', 'OutcomingLetterController@store');
    $router->get('letters/outcoming-letter', 'OutcomingLetterController@getList');

    //sub-letter-code
    $router->get('letter-codes/{id}/sub-letter-codes', 'SubLetterCodeController@getByLetterCodeId');
    $router->get('letter-codes/{code}/sub-letter-codes/{subCode}', 'SubLetterCodeController@get');

    //letter-code
    $router->get('letter-codes', 'LetterCodeController@getList');
    $router->get('letter-codes/{id}', 'LetterCodeController@get');
});
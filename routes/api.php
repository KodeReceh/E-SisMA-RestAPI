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
    $router->get('letters/incoming-letter/{id}/disposition', 'DispositionController@get');    
    $router->post('letters/incoming-letter/{id}/disposition', 'DispositionController@storeDisposition');
    $router->put('letters/incoming-letter/{id}/disposition', 'DispositionController@updateDisposition');
    $router->put('letters/incoming-letter/{id}', 'IncomingLetterController@update');
    $router->delete('letters/incoming-letter/{id}', 'IncomingLetterController@delete');
    // $router->get('letters/incoming-letter/get-list', 'IncomingLetterController@getList');
    
    //outcoming-letter
    $router->post('letters/outcoming-letter', 'OutcomingLetterController@store');
    $router->get('letters/outcoming-letter', 'OutcomingLetterController@getList');
    $router->get('letters/outcoming-letter/{id}', 'OutcomingLetterController@get');
    $router->put('letters/outcoming-letter/{id}', 'OutcomingLetterController@update');
    $router->delete('letters/outcoming-letter/{id}', 'OutcomingLetterController@delete');
    
    //sub-letter-code
    $router->get('letter-codes/{id}/sub-letter-codes', 'SubLetterCodeController@getByLetterCodeId');
    $router->get('letter-codes/{code}/sub-letter-codes/{subCode}', 'SubLetterCodeController@get');

    //letter-code
    $router->get('letter-codes', 'LetterCodeController@getList');
    $router->get('letter-codes/{id}', 'LetterCodeController@get');

    //document
    $router->get('documents', 'DocumentController@index');
    $router->post('documents', 'DocumentController@store');
    $router->put('documents/{id}', 'DocumentController@update');
    $router->delete('documents/{id}', 'DocumentController@delete');
    $router->get('documents/{id}', 'DocumentController@get');

    // download file
    $router->get('get-file/{path}', 'DocumentController@responseFile');

    //archive type
    $router->group(['prefix' => 'archives'], function () use ($router) {
        $router->get('types','ArchiveTypeController@getList');
        $router->get('types/{id}', 'ArchiveTypeController@get');
        $router->post('types', 'ArchiveTypeController@store');
        $router->put('types/{id}', 'ArchiveTypeController@update');
        $router->delete('types/{id}', 'ArchiveTypeController@delete');
    });

    // archive
    $router->get('archives', 'ArchiveController@getList');
    $router->get('archives/{id}', 'ArchiveController@get');
    $router->post('archives', 'ArchiveController@store');
    $router->put('archives/{id}', 'ArchiveController@update');
    $router->delete('archives/{id}', 'ArchiveController@delete');

    //templates
    $router->get('templates', 'TemplateController@list');
    $router->post('templates', 'TemplateController@create');
    $router->get('templates/{id}', 'TemplateController@get');
    $router->post('templates/{id}/fields', 'TemplateController@addField');
    $router->get('templates/{id}/fields', 'TemplateController@fields');
    $router->get('villager_columns', 'TemplateController@villagerColumns');

    // ltter_templates
    $router->post('templates/{id}/letter_templates', 'LetterTemplateController@saveFieldData');
    $router->get('templates/{template}/letter_templates/{id}/generate', 'LetterTemplateController@generateDoc');

});
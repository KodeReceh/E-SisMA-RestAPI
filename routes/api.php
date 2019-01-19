<?php

$router->post('login', 'AuthController@login');
$router->post('verify-letter', 'VerifyLetterController@check');

$router->group(['middleware' => 'auth'], function () use ($router) {
    // logout
    $router->post('logout', 'AuthController@logout');
    
    //user
    $router->get('users/notifications', 'UserController@notifications');
    $router->get('users/get-profile', 'UserController@getCurrentUser');
    $router->get('users/get-uniques', 'UserController@getUniques');
    $router->post('users', 'UserController@store');
    $router->get('users', 'UserController@index');
    $router->get('users/{user}', 'UserController@getUser');
    $router->put('users/{user}', 'UserController@update');
    $router->delete('users/{user}', 'UserController@delete');
    $router->post('users/change_status', 'UserController@changeStatus');

    //role
    $router->get('roles', 'RoleController@index');
    $router->get('roles/permissions', 'RoleController@getPermissions');
    $router->post('roles', 'RoleController@store');
    $router->put('roles/{id}', 'RoleController@update');
    $router->get('roles/{id}', 'RoleController@get');
    $router->delete('roles/{id}', 'RoleController@delete');

    //incoming-letter
    $router->post('letters/incoming-letter', 'IncomingLetterController@store');
    $router->get('letters/incoming-letter', 'IncomingLetterController@getList');
    $router->get('letters/incoming-letter/{id}', 'IncomingLetterController@get');
    $router->get('letters/incoming-letter/{id}/disposition/{user_id}', 'DispositionController@getByUser');
    $router->get('letters/incoming-letter/{id}/disposition', 'DispositionController@get');
    $router->post('letters/incoming-letter/{id}/disposition', 'DispositionController@storeDisposition');
    $router->put('letters/incoming-letter/{id}/disposition', 'DispositionController@updateDisposition');
    $router->put('letters/incoming-letter/{id}', 'IncomingLetterController@update');
    $router->delete('letters/incoming-letter/{id}', 'IncomingLetterController@delete');

    //outcoming-letter
    $router->post('letters/outcoming-letter', 'OutcomingLetterController@store');
    $router->get('letters/outcoming-letter', 'OutcomingLetterController@getList');
    $router->get('letters/outcoming-letter/get-ordinal', 'OutcomingLetterController@getOrdinal');
    $router->get('letters/outcoming-letter/{id}', 'OutcomingLetterController@get');
    $router->put('letters/outcoming-letter/{id}', 'OutcomingLetterController@update');
    $router->delete('letters/outcoming-letter/{id}', 'OutcomingLetterController@delete');

    //letter
    $router->get('letters/get-numbers', 'LetterController@getNumbers');
    $router->get('letters/{id}', 'LetterController@get');
    
    //sub-letter-code
    $router->get('letter-codes/{id}/sub-letter-codes', 'LetterCodeController@getSubLetterCodeList');

    //letter-code
    $router->get('letter-codes', 'LetterCodeController@getLetterCodeList');

    //get letter code or sub letter code
    $router->get('letter-codes/{id}', 'LetterCodeController@get');
    $router->get('letter-codes/{id}/get-name', 'LetterCodeController@getLetterCodeName');

    //document
    $router->get('documents', 'DocumentController@getUserDocuments');
    $router->post('documents', 'DocumentController@store');
    $router->get('documents/archives/{archiveId}', 'DocumentController@getByArchive');
    $router->put('documents/{id}', 'DocumentController@update');
    $router->delete('documents/{id}', 'DocumentController@delete');
    $router->get('documents/{id}', 'DocumentController@get');

    // download file
    $router->get('get-file/{path}', 'DocumentController@responseFile');

    //archive type
    $router->group(['prefix' => 'archives'], function () use ($router) {
        $router->get('types', 'ArchiveTypeController@getList');
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
    $router->get('templates/get-field-resources', 'TemplateController@getResources');
    $router->get('templates/{id}', 'TemplateController@get');
    $router->put('templates/{id}', 'TemplateController@update');
    $router->delete('templates/{id}', 'TemplateController@delete');
    $router->delete('templates/{templateId}/fields/{id}', 'TemplateController@removeField');
    $router->post('templates/{id}/fields', 'TemplateController@addField');
    $router->post('templates/{id}/field-form', 'LetterTemplateController@storeFieldData');
    $router->get('templates/{id}/field-form', 'LetterTemplateController@getFields');
    $router->get('templates/{id}/fields', 'TemplateController@fields');

    // letter_templates
    $router->get('outcoming-letter-drafts/download/{id}', 'LetterTemplateController@download');
    $router->delete('outcoming-letter-drafts/delete/{id}', 'LetterTemplateController@delete');
    $router->get('outcoming-letter-drafts/sign/{id}', 'LetterTemplateController@sign');
    $router->get('outcoming-letter-drafts/unsign/{id}', 'LetterTemplateController@unsign');
    $router->get('outcoming-letter-drafts/{id}', 'LetterTemplateController@get');
    $router->delete('outcoming-letter-drafts/generated-file/{id}', 'LetterTemplateController@deleteGeneratedFile');
    $router->get('outcoming-letter-drafts', 'LetterTemplateController@getList');
    $router->post('templates/{id}/letter_templates', 'LetterTemplateController@saveFieldData');
    $router->get('templates/{template}/letter_templates/{id}/generate', 'LetterTemplateController@generateDoc');

    // recipients
    $router->get('recipients/{letter_id}', 'RecipientController@getRecipients');
    $router->get('recipients/{letter_id}/user/available', 'RecipientController@availableRecipients');
    $router->get('recipients/{letter_id}/user/{user_id}', 'RecipientController@get');
    $router->post('recipients/{letter_id}', 'RecipientController@store');
    $router->delete('recipients/{letter_id}/user/{user_id}', 'RecipientController@delete');
    $router->get('recipients/user/all', 'RecipientController@allUsers');

    // villagers
    $router->get('villagers', 'VillagerController@all');
    $router->get('villagers/fields', 'VillagerController@getFields');
    $router->get('villagers/get-niks', 'VillagerController@getNIKs');
    $router->get('villagers/{id}', 'VillagerController@get');
    $router->post('villagers', 'VillagerController@store');
    $router->put('villagers/{id}', 'VillagerController@update');
    $router->delete('villagers/{id}', 'VillagerController@delete');
    $router->get('villagers/get-pic/{filename}', 'VillagerController@getPic');

    // profile
    $router->get('profile', 'ProfileController@get');
    $router->put('profile', 'ProfileController@update');
    $router->get('profile/get-sign/{filename}', 'ProfileController@getSign');
});
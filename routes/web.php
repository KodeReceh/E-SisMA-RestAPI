<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('pdf', 'LetterTemplateController@generateFromTemplate');
$router->get('testQR', 'LetterTemplateController@testQRCode');

$router->post('webhook', function(Request $request) {
    $cmd = 'cd .. && git pull origin master';
    if($request->input('force')){
        $cmd .= ' --force';
    }

    $output = shell_exec($cmd);
    return '<pre>'. $output .'</pre>';
});

$router->post('testwebhook', function (Request $request){
    return $request->payload;
});


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
    $output = '';

    $payload = jsno_decode($request->payload);

    if($commits = $payload->commits){
        $output .= 'commits:<br><br>';
        foreach ($commits as $commit) {
            $output .= '- '.$commit->message.'<br>';
            if(strpos($commit->message, 'do fresh migrate') !== false){
                $cmd .= ' && php artisan migrate:fresh';
            }elseif (strpos($commit->message, 'do refresh migrate') !== false) {
                $cmd .= ' && php artisan migrate:refresh';                
            }elseif (strpos($commit->message, 'do migrate') !== false) {
                $cmd .= ' && php artisan migrate';
            }

            if(strpos($commit->message, 'do db:seed') !== false){
                $cmd .= ' && php artisan db:seed';
            }

            if(strpos($commit->message, 'do composer update') !== false){
                $cmd .= ' && composer update';
            }
            
        }
        $output .= '<br><br>';
    }

    $output = 'output command: <br>'.shell_exec($cmd);
    return '<pre>'. $output .'</pre>';
});


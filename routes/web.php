<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('tokens', 'TokenController');

Route::middleware(['auth'])->group(function () {
    Route::resources([
        'upload' => 'UploadController',
        'documents' => 'DocumentController',
        'books' => 'BookController',
        'users' => 'UserController',
        'mathmarks' => 'MathMarkController',
        'computers' => 'ComputerController',
        'criticals' => 'CriticalController',
    ]);
});

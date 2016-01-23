<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    $allImages = DigitalOcean::image()
      ->getAll(['type' => 'snapshot', 'private' => true]);
    $allDroplets = DigitalOcean::droplet()->getAll();

    return view('servman', [
        'allImages' => $allImages,
        'allDroplets' => $allDroplets
    ]);
});

Route::post('/server/init', function() {
    // create new droplet
});

Route::get('/server/{id}/reboot', function($id) {
    // reboot droplet
});

Route::delete('/server/{id}', function($id) {
    // destroy droplet
});


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {

    // Authentication routes...
    Route::get('auth/login', 'Auth\AuthController@getLogin');
    Route::post('auth/login', 'Auth\AuthController@postLogin');
    Route::get('auth/logout', 'Auth\AuthController@getLogout');

    // Registration routes...
    Route::get('auth/register', 'Auth\AuthController@getRegister');
    Route::post('auth/register', 'Auth\AuthController@postRegister');

});

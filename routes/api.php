<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login',[
    'uses'=>'Api\loginController@login',
]);

Route::group(['middleware' => 'jwt.auth'], function () {

    //for User
    Route::post('/users',[
        'uses'=>'Api\UserController@store'
    ]);

});
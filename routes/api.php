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
        //for Image
Route::post('/image',[
    'uses'=>'Api\LocationController@image'
]);

Route::group(['middleware' => 'jwt.auth'], function () {

        //for Location

        Route::get('/location',[
            'uses'=>'Api\LocationController@show'
        ]);

        Route::post('/location', [
            'uses' => 'Api\LocationController@create'
        ]);

        Route::delete('/location/{id}', [
            'uses' => 'Api\LocationController@delete'
        ]);
        Route::put('/location/{id}/', [
            'uses' => 'Api\LocationController@update',
        ]);
        Route::get('/location/{id}',[
            'uses'=>'Api\LocationController@showById'
        ]);


        //for Position
        Route::get('/position',[
        'uses'=>'Api\PositionController@show'
         ]);

         Route::post('/position',[
        'uses'=>'Api\PositionController@create'
        ]);

         Route::delete('/position/{id}',[
             'uses'=>'Api\PositionController@delete'
        ]);

         Route::put('/position/{id}',[
             'uses'=>'Api\PositionController@update'
         ]);
    Route::get('/position/{id}',[
        'uses'=>'Api\PositionController@showById'
    ]);



});
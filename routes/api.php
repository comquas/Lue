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


Route::group(['middleware' => 'jwt.auth'], function () {

         //for logout
        Route::get('/logout',[
          'uses'=>'Api\loginController@logout',
        ]);
        Route::post('/recover',[
            'uses'=>'Api\loginController@recover',
        ]);

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

    //for User
    Route::post('/user',[
        'uses'=>'Api\UserController@create',
    ]);
    Route::get('/user/profile/{id}',[
        'uses'=>'Api\UserController@showUserProfileById'
    ]);
    Route::get('/user',[
        'uses'=>'Api\UserController@show'
    ]);
    Route::put('/user/{id}',[
        'uses'=>'Api\UserController@update'
    ]);
    Route::delete('/user/{id}',[
      'uses'=>'Api\UserController@delete'
    ]);



    //time-off list
    Route::get('/time-off',[
        'uses'=>'Api\LeaveController@show'
    ]);
    Route::put('/time-off/{id}',[
        'uses'=>'Api\LeaveController@update'
    ]);
    Route::get('/admin/time_off_list',[
      'uses'=>'Api\LeaveController@time_off_list',
    ]);
    Route::post('time-off/reject',[
        'uses'=>'Api\LeaveController@reject'
    ]);
    Route::get('/time-off/approve/{id}/',[
        'uses'=>'Api\LeaveController@approve',
    ]);



    //apply time-off
    Route::post('/apply-time-off',[
       'uses'=>'Api\LeaveController@create'
    ]);

    //fore Search

    Route::get('/search/{name}',[
        'uses'=>'Api\SearchController@search'
    ]);

});
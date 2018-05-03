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

    Route::middleware('auth:api')->get('/user', function (Request $request) {
        return $request->user();
    });
Route::post('/apiLogin', [
    'uses' => 'loginController@apiLogin',

]);
Route::get('/supervisor',[
    'uses'=>'loginController@getSup'
]);
Route::group(['middleware' => 'jwt.auth'], function () {

   Route::get('/User',[
       'uses'=>'Api\UserController@getUser'
   ]);
Route::get('/Position',[
    'uses'=>'Api\PositionController@getPosition'
]);

Route::get('/Location',[
    'uses'=>'Api\LocationController@getLocation',
]);
Route::get('/Leaves',[
    'uses'=>'Api\LeaveController@getLeave',
]);
});
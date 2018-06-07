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

          // for login

        Route::post('/login',[
        'uses'=>'Api\loginController@login'
        ]);

        


Route::group(['middleware' => 'jwt.auth'], function () {

         //for logout
        Route::get('/logout',[
          'uses'=>'Api\loginController@logout',
        ]);
        Route::post('/reset',[
            'uses'=>'Api\loginController@reset',
        ]);

//for Location

        //list location

        Route::get('/locations',[
            'uses'=>'Api\LocationController@index'
        ]);

        //store location

        Route::post('/location', [
            'uses' => 'Api\LocationController@store'
        ]);

        //delete location

        Route::delete('/location/{id}', [
            'uses' => 'Api\LocationController@delete'
        ]);

        //update location

        Route::post('/location/{id}/', [
            'uses' => 'Api\LocationController@update',
        ]);
        Route::post('/location/edit/{id}',[
            'uses'=>'Api\LocationController@edit'
        ]);

        //show single location

        Route::get('/location/{id}',[
            'uses'=>'Api\LocationController@show'
        ]);
//end Location

//for Position 

        //list Position

        Route::get('/positions',[
        'uses'=>'Api\PositionController@index'
         ]);

        //store Position

         Route::post('/position',[
        'uses'=>'Api\PositionController@store'
        ]);

         //delete Position

       Route::delete('/position/{id}',[
         'uses'=>'Api\PositionController@delete'
       ]);
       
       //update Position

         Route::post('/position/{id}',[
             'uses'=>'Api\PositionController@update'
         ]);

         //list single position

    Route::get('/position/{id}',[
        'uses'=>'Api\PositionController@show'
    ]);

 //end  Position  

    
//for User

    //store User

    Route::post('/user',[
        'uses'=>'Api\UserController@store',
    ]);

    Route::get('admin/user/profile/{id}',[
    'uses'=>'Api\UserController@profile'
    ]);
 
    //Admin update User ById

    Route::post('/admin/user/{id}',[
        'uses'=>'Api\UserController@update'
    ]);

    //delete User

    Route::delete('/user/{id}',[
      'uses'=>'Api\UserController@delete'
    ]);

    //for Search
       Route::get('/search', 'Api\UserController@search');

       //admin user list

       Route::get('/admin/user/list',[
      'uses'=>'Api\UserController@showList',
    ]);

       //Reset Leave
       Route::get('/admin/reset-leave/{id}',[
       'uses'=>'Api\UserController@resetLeave'
    ]);
       //user Profile update
       Route::post('/update',[
        'uses'=>'Api\UserController@userUpdate',
       ]);



       //update Profile
       Route::post('/user/profile/update',[
        'uses'=>'Api\LocationController@updateProfile',
       ]);




    //time-off list
    Route::get('/time-off',[
        'uses'=>'Api\LeaveController@show'
    ]);
    Route::post('/admin/time-off/update/{id}',[
        'uses'=>'Api\LeaveController@update'
    ]);
    
    Route::post('admin/time-off/reject',[
        'uses'=>'Api\LeaveController@reject'
    ]);
    Route::get('/admin/time-off/approve/{id}/',[
        'uses'=>'Api\LeaveController@approve',
    ]);
    

   Route::get('/admin/time-off/list',[
    'uses'=>'Api\LeaveController@adminTimeOffList',
    ]);
    Route::get('/admin/decided/time-off/list',[
        'uses'=>'Api\LeaveController@adminDecidedTimeOffList'
    ]);

    //Admin Time Off Apply
    Route::post('/admin/time-off/apply',[
       'uses'=>'Api\LeaveController@store'
    ]);

});
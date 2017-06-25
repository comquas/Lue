<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('welcome');
});

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
	
	//position
	Route::get('admin/position/list','Position\JobPositionController@index')->name('position_list');
	Route::get('admin/position/add','Position\JobPositionController@add')->name('position_add');
	Route::post('admin/position/add','Position\JobPositionController@store')->name('position_store');

	Route::get('admin/position/edit/{id}','Position\JobPositionController@edit')->name('position_edit');
	Route::post('admin/position/edit/{id}','Position\JobPositionController@update')->name('position_update');

	Route::post('admin/position/delete/{id}','Position\JobPositionController@delete')->name('position_delete');

	//location
	Route::get('admin/location/list','Location\JobLocationController@index')->name('location_list');
	Route::get('admin/location/add','Location\JobLocationController@add')->name('location_add');
	Route::post('admin/location/add','Location\JobLocationController@store')->name('location_store');

	Route::get('admin/location/edit/{id}','Location\JobLocationController@edit')->name('location_edit');
	Route::post('admin/location/edit/{id}','Location\JobLocationController@update')->name('location_update');

	Route::post('admin/location/delete/{id}','Location\JobLocationController@delete')->name('location_delete');


	//user
	Route::get('admin/user/profile','User\UserController@edit_profile')->name('profile');
	Route::post('admin/user/profile','User\UserController@update_profile')->name('profile_update');


});

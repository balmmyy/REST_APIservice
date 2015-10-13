<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
//member controller
Route::get('/', 'MemberController@getAll');

Route::get('/member', 'MemberController@getAllMember');

Route::get('/member/{key}', 'MemberController@getMember');

Route::post('/member', 'MemberController@addMember');

Route::put('/member/{key}', 'MemberController@editMember');

Route::delete('/member/{key}','MemberController@deleteMember');

//order controller
Route::get('/member/{member}/order', 'OrderController@getAllOrder');

Route::get('/member/{member}/order/{id}', 'OrderController@getOrder');

Route::post('/member/{member}/order', 'OrderController@addOrder');

Route::put('/member/{member}/order/{id}', 'OrderController@editOrder');

Route::delete('/member/{member}/order/{id}', 'OrderController@deleteOrder');

/*Route::get('/', function(){
	return View::make('hello');
});*/
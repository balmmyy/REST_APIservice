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
//home controller
Route::get('/home', 'HomeController@getAll');

Route::delete('/home', 'HomeController@deleteAll');

//member controller
Route::get('/member', 'MemberController@getAllMember');

Route::get('/member/{key}', 'MemberController@getMember');

Route::post('/member', 'MemberController@addMember');

Route::put('/member/{key}', 'MemberController@editMember');

Route::delete('/member/{key}','MemberController@deleteMember');

//order controller
Route::get('/member/{member}/order', 'OrderController@getAllOrderInMember');

Route::get('/member/{member}/order/{id}', 'OrderController@getOrderInMember');

Route::post('/member/{member}/order', 'OrderController@addOrderInMember');

Route::put('/member/{member}/order/{id}', 'OrderController@editOrderInMember');

Route::delete('/member/{member}/order/{id}', 'OrderController@deleteOrderInMember');

Route::get('/order', 'OrderController@getAllOrder');

Route::get('/order/{id}', 'OrderController@getOrder');

Route::post('/order/{member}', 'OrderController@addOrderInMember');

Route::put('/order/{id}', 'OrderController@editOrder');

Route::delete('/order/{id}', 'OrderController@deleteOrder');

/*Route::get('/', function(){
	return View::make('hello');
});*/
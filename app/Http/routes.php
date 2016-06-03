<?php

Route::group(['middleware' => 'auth'], function() {
	Route::get('/', function () {
	    return view('welcome');
	});
});

Route::get('login', function() {
	return view('auth.login');
});

Route::auth();

Route::get('/home', 'HomeController@index');

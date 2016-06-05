<?php

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('characters', 'CharactersController@index');
    Route::delete('characters/{character}', 'CharactersController@delete');
    Route::patch('characters/{character}', 'CharactersController@update');
    Route::patch('users', 'UsersController@update');
    Route::get('notifiers', 'NotifiersController@index');
    Route::post('notifiers', 'NotifiersController@store');
    Route::delete('notifiers', 'NotifiersController@destroy');
    Route::get('callback', 'CallbackController@handle');
});

Route::auth();

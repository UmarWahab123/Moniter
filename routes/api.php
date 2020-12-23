<?php


Route::post('login', 'Api\AuthController@login');
 
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'Api\AuthController@logout');
    Route::get('get-monitors', 'Api\ApiController@getMonitor');
});
<?php


Route::post('login', 'Api\AuthController@login');
 
Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('get-monitors', 'Api\ApiController@getMonitor');
    Route::get('get-featured', 'Api\ApiController@getFeaturedMonitor');


    
    Route::get('logout', 'Api\AuthController@logout');

});
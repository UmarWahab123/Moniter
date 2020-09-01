<?php
use App\User;
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
    User::where('status',0)->orWhere('status',null)->update(['status'=>1]);
    return redirect('login');
});

Auth::routes();
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => 'admin'], function (){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/websites', 'WebSiteController@index')->name('websites');
    Route::post('/add-website', 'WebSiteController@store')->name('add-website');
    Route::get('/users', 'UserController@index')->name('users');
    Route::post('/add-user', 'UserController@store')->name('add-user');
    Route::post('/edit-user', 'UserController@update')->name('edit-user');
    Route::get('/user-status', 'UserController@userStatus')->name('user-status');
});

Route::group(['namespace' => 'User', 'prefix' => 'user', 'middleware' => 'user'], function (){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/websites', 'WebSiteController@index')->name('websites');
    Route::post('/add-website', 'WebSiteController@store')->name('add-website');
});

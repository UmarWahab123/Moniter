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
    return redirect('login');
});

Auth::routes();
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth','admin']], function (){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/websites', 'WebsiteController@index')->name('websites');
    Route::post('/add-website', 'WebsiteController@store')->name('add-website');
    Route::get('/delete-website', 'WebsiteController@destroy')->name('delete-website');
    Route::get('/users', 'UserController@index')->name('users');
    Route::post('/add-user', 'UserController@store')->name('add-user');
    Route::post('/edit-user', 'UserController@update')->name('edit-user');
    Route::get('/user-status', 'UserController@userStatus')->name('user-status');

    Route::get('/settings', 'SettingController@index')->name('settings');
});

Route::group(['namespace' => 'User', 'prefix' => 'user', 'middleware' => ['auth','user']], function (){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/websites', 'WebsiteController@index')->name('websites');
    Route::post('/add-website', 'WebsiteController@store')->name('add-website');
    Route::get('/delete-website', 'WebsiteController@destroy')->name('delete-website');

});

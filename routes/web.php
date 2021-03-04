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
    Route::post('/edit-website', 'WebsiteController@update')->name('edit-website');
    Route::get('/edit-website', 'WebsiteController@edit')->name('edit-website');
    Route::get('/delete-website', 'WebsiteController@destroy')->name('delete-website');
    Route::get('/website-logs/{id}', 'WebsiteController@websiteLogs')->name('website-logs');
    Route::get('feature', 'WebsiteController@featureWebsite')->name('feature');
    Route::get('/users', 'UserController@index')->name('users');
    Route::post('/add-user', 'UserController@store')->name('add-user');
    Route::post('/edit-user', 'UserController@update')->name('edit-user');
    Route::get('/user-status', 'UserController@userStatus')->name('user-status');

    Route::get('/settings', 'SettingController@index')->name('settings');
    
    Route::post('/add-settings', 'SettingController@store')->name('add-settings');


    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::post('/profile', 'ProfileController@update')->name('profile');

    Route::get('/devices', 'DeviceManagementController@index')->name('devices');
    Route::post('/device-logout', 'DeviceManagementController@deviceLogout')->name('device-logout');


    
});

Route::group(['namespace' => 'User', 'prefix' => 'user', 'middleware' => ['auth','user']], function (){
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/websites', 'WebsiteController@index')->name('websites');
    Route::post('/add-website', 'WebsiteController@store')->name('add-website');
    Route::get('/delete-website', 'WebsiteController@destroy')->name('delete-website');
    Route::post('/edit-website', 'WebsiteController@update')->name('edit-website');
    Route::get('/edit-website', 'WebsiteController@edit')->name('edit-website');

    Route::get('feature', 'WebsiteController@featureWebsite')->name('feature');
    Route::get('/website-logs/{id}', 'WebsiteController@websiteLogs')->name('website-logs');

    Route::get('/settings', 'SettingController@index')->name('settings');
    Route::post('/add-settings', 'SettingController@store')->name('add-settings');

    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::post('/profile', 'ProfileController@update')->name('profile');

    Route::get('/devices', 'DeviceManagementController@index')->name('devices');
    Route::post('/device-logout', 'DeviceManagementController@deviceLogout')->name('device-logout');

});

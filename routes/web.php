<?php

use App\Http\Controllers\Admin\PackageController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
    Route::get('/get-down-reason', 'WebsiteController@getDownReason')->name('get-down-reason');
    Route::get('/get-down-reason-image', 'WebsiteController@getDownReasonImage')->name('get-down-reason-image');
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

    //admin package

    Route::get('/package', [PackageController::class,'index']);
    Route::post('/add-package', [PackageController::class,'store']);

    Route::post('/update-package', [PackageController::class,'update']);

    Route::get('package/edit',  [PackageController::class,'edit']);
    Route::get('package/update-status',  [PackageController::class,'updateStatus']);
   // Route::post('add-server', 'ServerController@addServer')->name('add-server');


    
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

    Route::get('/get-down-reason', 'WebsiteController@getDownReason')->name('get-down-reason');
    Route::get('/get-down-reason-image', 'WebsiteController@getDownReasonImage')->name('get-down-reason-image');

});

/*here we will use same routes for both admin and users*/
Route::group(['middleware' => ['auth']], function (){

    /*Server routes*/
    Route::get('servers/dashboard', 'ServerController@dashboard')->name('servers.dashboard');
    Route::get('servers', 'ServerController@index')->name('servers');
    Route::get('get-servers', 'ServerController@getServers')->name('get-servers');
    Route::post('add-server', 'ServerController@addServer')->name('add-server');
    Route::get('server-logs/{id}', 'ServerController@serverLogs')->name('server-logs');
    Route::get('server-logs-in-details', 'ServerController@serverLogsInDetails')->name('server-logs-in-details');
    Route::get('get-latest-server-logs', 'ServerController@getLatestServerLogs')->name('check-new-entry');
    Route::post('servers/delete', 'ServerController@destroy')->name('servers.destroy');
    Route::get('servers/edit', 'ServerController@edit')->name('servers.edit');
    Route::post('servers/update', 'ServerController@update')->name('servers.update');
});

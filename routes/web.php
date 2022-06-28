<?php


use App\Http\Controllers\Admin\PackageController;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\Subscription\SubscriptionController;
use Carbon\Carbon;



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
Route::get('/home', function () {
    $user = User::find(Auth::user()->id);
    $user->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
    $user->save();
    if (Auth::user()->role_id == 1 && Auth::user()->status == 1) {
        return redirect('/admin/home');
    } elseif (Auth::user()->role_id == 2 && Auth::user()->status == 1) {
        return redirect('/user/home');
    } else {
        Auth::logout();
        return redirect('/login');
    }
});

Auth::routes(['verify' => true]);
Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    Route::get('/home', 'HomeController@index')->name('admin.home');
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
    Route::get('/{id}/user-permissions', 'UserController@userPermissions')->name('users.permissions');
    Route::post('save-permissions', 'UserController@saveUserPermissions')->name('users.save-permissions');

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


    // Email Template Routes
    Route::get('/email-templates', 'EmailTemplateController@index')->name('templates.index');
    Route::get('/get-email-templates-data', 'EmailTemplateController@getTemplatesData')->name('templates.getTemplates');
    Route::get('/email-templates/create', 'EmailTemplateController@create')->name('templates.create');
    Route::post('/email-templates/store', 'EmailTemplateController@store')->name('templates.store');
    Route::post('/email-templates/update', 'EmailTemplateController@update')->name('templates.update');
    Route::post('/email-templates/delete/{id}', 'EmailTemplateController@delete')->name('templates.delete');
    Route::get('/email-templates/edit/{id}', 'EmailTemplateController@edit')->name('templates.edit');
    Route::post('/email-templates/storeKeyword', 'EmailTemplateController@storeKeyword')->name('templates.storeKeyword');
});

Route::group(['namespace' => 'User', 'prefix' => 'user', 'middleware' => ['auth', 'user']], function () {
    Route::get('/home', 'HomeController@index')->name('user.home');
    Route::get('/websites', 'WebsiteController@index')->name('websites');
    Route::get('subscription/',  [SubscriptionController::class,'index']);
    Route::post('createSubscription',  [SubscriptionController::class,'create']);
    Route::post('sucessSubscription',  [SubscriptionController::class,'sucessSubscription']);
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

    Route::get('/users', 'UserController@index')->name('users.users');
    Route::post('/add-user', 'UserController@store')->name('users.add-user');
    Route::post('/edit-user', 'UserController@update')->name('users.edit-user');
    Route::get('/user-status', 'UserController@userStatus')->name('users.user-status');
    Route::get('/{id}/user-permissions', 'UserController@userPermissions')->name('users.users-permissions');
    Route::post('save-permissions', 'UserController@saveUserPermissions')->name('users.users-save-permissions');

    // Email Template Routes
    Route::get('/email-templates', 'EmailTemplateController@index')->name('users.templates.index');
    Route::get('/get-email-templates-data', 'EmailTemplateController@getTemplatesData')->name('users.templates.getTemplates');
    Route::get('/email-templates/create', 'EmailTemplateController@create')->name('users.templates.create');
    Route::post('/email-templates/store', 'EmailTemplateController@store')->name('users.templates.store');
    Route::post('/email-templates/update', 'EmailTemplateController@update')->name('users.templates.update');
    Route::post('/email-templates/delete/{id}', 'EmailTemplateController@delete')->name('users.templates.delete');
    Route::get('/email-templates/edit/{id}', 'EmailTemplateController@edit')->name('users.templates.edit');
});

/*here we will use same routes for both admin and users*/
Route::group(['middleware' => ['auth']], function () {

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

Route::get('emails/resend', [UserController::class, 'resendEmail'])->name('emails.resendEmail');
Route::get('verify-user-email-address', function () {
    $user = User::find(Auth::user()->id);
    $user->email_verified_at = Carbon::now();
    $user->save();
    return redirect('/servers/dashboard');
});
Route::post('emails/send-verification_code', [UserController::class, 'sendVerificationCodeEmail'])->name('emails.send-verification_code');
Route::post('/do-login', [AuthController::class, "doLogin"])->name('custom_login');
Route::get('/login/verify-login', [AuthController::class, "createVerfication"])->name('login.custom-verify');

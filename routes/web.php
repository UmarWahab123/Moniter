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
    Route::get('website/edit-website', 'WebsiteController@edit')->name('edit-website');
    Route::get('/delete-website', 'WebsiteController@destroy')->name('delete-website');
    Route::post('/assign-website-to-user', 'WebsiteController@assignWebsiteToSubUser')->name('assign-websites-to-user');
    Route::get('/show_assigned_users', 'WebsiteController@showAssignedUser')->name('show_assigned_users');
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
    Route::post('/change-password', 'ProfileController@changePassword')->name('changePassword');


    Route::get('/devices', 'DeviceManagementController@index')->name('devices');
    Route::post('/device-logout', 'DeviceManagementController@deviceLogout')->name('device-logout');
    Route::get('subscription/',  [SubscriptionController::class,'index']);
    Route::get('createSubscription',  [SubscriptionController::class,'create']);
    Route::get('sucessSubscription',  [SubscriptionController::class,'sucessSubscription']);
    Route::get('cancelSubscription',  [SubscriptionController::class,'cancelSubscription']);

    Route::get('/permission','PermissionController@permission');
    Route::post('/add-permission', 'PermissionController@addPermission');
    Route::get('permission/edit',  'PermissionController@editPermission');
    Route::post('permission/delete','PermissionController@permissionDelete');
    Route::get('permission/permission-setting','PermissionController@permissionSettings');
    Route::post('/store-assign-permission','PermissionController@storeAssignPermissions');
    Route::post('userpermission/delete','PermissionController@userPermissionDelete');
    Route::get('permission/get-permissions','PermissionController@getPermissions');
    //server webisites

    
});

Route::group(['namespace' => 'User', 'prefix' => 'user', 'middleware' => ['auth', 'user']], function () {
    Route::get('/home', 'HomeController@index')->name('user.home');
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
    Route::post('/change-password', 'ProfileController@changePassword')->name('changePassword');

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

});

/*here we will use same routes for both admin and users*/
Route::group(['middleware' => ['auth']], function () {

    /*Server routes*/
    Route::get('servers/dashboard', 'ServerController@dashboard')->name('servers.dashboard');
    Route::get('servers', 'ServerController@index')->name('servers');
    Route::get('get-operating-system','ServerController@getOperatingSystem');
    Route::get('get-servers', 'ServerController@getServers')->name('get-servers');
    Route::post('add-server', 'ServerController@addServer')->name('add-server');
    Route::get('server-logs/{id}', 'ServerController@serverLogs')->name('server-logs');
    Route::get('server-logs-in-details', 'ServerController@serverLogsInDetails')->name('server-logs-in-details');
    Route::get('get-latest-server-logs', 'ServerController@getLatestServerLogs')->name('check-new-entry');
    Route::post('servers/delete', 'ServerController@destroy')->name('servers.destroy');
    Route::get('servers/edit', 'ServerController@edit')->name('servers.edit');
    Route::post('servers/update', 'ServerController@update')->name('servers.update');
    Route::post('servers/binded-websites', 'ServerController@bindedWebsites')->name('servers.binded-websites');
    Route::get('servers/get-biinded-websites', 'ServerController@getBindedWebsites')->name('get-binded-websites');
    Route::post('servers/save-biinded-websites', 'ServerController@saveBindedWebsites')->name('save-binded-websites');
    Route::get('/user-added-websites','ServerController@userAddedWebsite');
    Route::post('server-website/delete','ServerController@serverWebsiteDelete');
    Route::post('website/assign-status-change','ServerController@websiteAssignStatusChange');

});

Route::group(['namespace' => 'SuperAdmin', 'prefix' => 'superAdmin', 'middleware' => ['auth', 'superAdmin']], function () {
    Route::get('dashboard', 'DashboardController@index')->name('superAdmin.dashboard');
    Route::get('dashboard/user-records', 'DashboardController@getUsersTotalRecords')->name('superAdmin.get-users-total-records');
    Route::get('/user', 'UserController@index')->name('superAdmin.users');
    Route::get('/user/get-data', 'UserController@getData')->name('superAdmin.get-data');
    Route::get('/user-status', 'UserController@userStatus')->name('superAdmin.user-status');
    Route::post('/user-delete', 'UserController@delete')->name('superAdmin.user-delete');
    Route::post('/add-user', 'UserController@store')->name('add-user');
    // Email Template Routes
    Route::get('/email-templates', 'EmailTemplateController@index')->name('templates.index');
    Route::get('/get-email-templates-data', 'EmailTemplateController@getTemplatesData')->name('templates.getTemplates');
    Route::get('/email-templates/create', 'EmailTemplateController@create')->name('templates.create');
    Route::post('/email-templates/store', 'EmailTemplateController@store')->name('templates.store');
    Route::post('/email-templates/update', 'EmailTemplateController@update')->name('templates.update');
    Route::post('/email-templates/delete/{id}', 'EmailTemplateController@delete')->name('templates.delete');
    Route::get('/email-templates/edit/{id}', 'EmailTemplateController@edit')->name('templates.edit');
    Route::post('/email-templates/storeKeyword', 'EmailTemplateController@storeKeyword')->name('templates.storeKeyword');
   //SuperAdmin package
    Route::get('/package', [PackageController::class,'index']);
    Route::post('/add-package', [PackageController::class,'store']);
    // Route::post('/update-package', [PackageController::class,'update']);
    Route::get('package/edit',  [PackageController::class,'edit']);
    Route::get('package/update-status',  [PackageController::class,'updateStatus']);
    Route::get('package/assign-feature',[PackageController::class,'assignFeature']);
    Route::post('/store-assign-feature', [PackageController::class,'storeAssignFeature']);

   //SuperAdmin system features
    Route::get('/system-features',  [PackageController::class,'systemFeatures']);
    Route::post('/add-system-feature', [PackageController::class,'addSystemFeature']);
    Route::get('system-feature/edit',  [PackageController::class,'systemFeatureEdit']);
    Route::post('system-feature/delete',[PackageController::class,'systemFeatureDelete']);
    Route::post('package-feature/delete',[PackageController::class,'assignFeatureDelete']);
   // Operating System
   Route::get('/operating-system',  'OperatingSystemController@operatingSystem');
   Route::post('/add-operating-system', 'OperatingSystemController@addOperatingSystem');
   Route::get('operating-system/edit', 'OperatingSystemController@EditOperatingSystem');
   Route::post('operating-system/delete', 'OperatingSystemController@deleteOperatingSystem');

});

Route::get('emails/resend', [UserController::class, 'resendEmail'])->name('emails.resendEmail');
Route::get('verify-user-email-address', function () {
    $user = User::find(Auth::user()->id);
    $user->email_verified_at = Carbon::now();
    $user->save();
    return redirect('/home');
});
Route::post('emails/send-verification_code', [UserController::class, 'sendVerificationCodeEmail'])->name('emails.send-verification_code');
Route::post('/do-login', [AuthController::class, "doLogin"])->name('custom_login');
Route::get('/login/verify-login', [AuthController::class, "createVerfication"])->name('login.custom-verify');


//scripts
Route::get('/super-admin-remember-token', function()
{
    $user = User::where('email', 'support@pkteam.com')->first();
    if ($user) {
        $user->remember_token = 'ovwgRav1OlHK774pP2tuv3j0cBSQ4wklNrm8Sa0P6p9KuoZHd';
        $user->role_id = 3;
        $user->save();
        return 'suucess';
    }
});
Route::get('/set-admin-role-id-to-all-users', function()
{
    $users = User::whereNull('role_id')->get();
    foreach($users as $user)
    {
        $user->role_id = 1;
        $user->save();
    }
    return 'suucess';
});
Route::get('/curl_call_check', function()
{
    $url  = 'http://wstats.websiteuptimerobot.com/add_server_new';
    $data['ip_address']    = '192.1923.23';
    $data['user_id']       = 'usama';
    $data['server_id']     = 'new';
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response);
});

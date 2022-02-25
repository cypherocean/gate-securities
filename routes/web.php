<?php

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
Route::get('command:clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return "config, cache, and view cleared successfully";
});

Route::get('command:config', function() {
    Artisan::call('config:cache');
    return "config cache successfully";
});

Route::get('command:key', function() {
    Artisan::call('key:generate');
    return "Key generate successfully";
});

Route::get('command:migrate', function() {
    Artisan::call('migrate:refresh');
    return "Database migration generated";
});

Route::get('command:seed', function() {
    Artisan::call('db:seed');
    return "Database seeding generated";
});

Route::group(['middleware' => ['prevent-back-history', 'mail-service']], function(){
    Route::group(['middleware' => ['guest']], function () {
        Route::get('/', 'AuthController@login')->name('login');
        Route::post('signin', 'AuthController@signin')->name('signin');

        Route::get('forgot-password', 'AuthController@forgot_password')->name('forgot.password');
        Route::post('password-forgot', 'AuthController@password_forgot')->name('password.forgot');
        Route::get('reset-password/{string}', 'AuthController@reset_password')->name('reset.password');
        Route::post('recover-password', 'AuthController@recover_password')->name('recover.password');
    });

    Route::group(['middleware' => ['auth']], function () {
        Route::get('logout', 'AuthController@logout')->name('logout');

        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

        /** access control */
            /** role */
                Route::any('role', 'RoleController@index')->name('role');
                Route::get('role/create', 'RoleController@create')->name('role.create');
                Route::post('role/insert', 'RoleController@insert')->name('role.insert');
                Route::get('role/edit', 'RoleController@edit')->name('role.edit');
                Route::patch('role/update/{id?}', 'RoleController@update')->name('role.update');
                Route::get('role/view', 'RoleController@view')->name('role.view');
                Route::post('role/delete', 'RoleController@delete')->name('role.delete');
            /** role */

            /** permission */
                Route::any('permission', 'PermissionController@index')->name('permission');
                Route::get('permission/create', 'PermissionController@create')->name('permission.create');
                Route::post('permission/insert', 'PermissionController@insert')->name('permission.insert');
                Route::get('permission/edit', 'PermissionController@edit')->name('permission.edit');
                Route::patch('permission/update/{id?}', 'PermissionController@update')->name('permission.update');
                Route::get('permission/view', 'PermissionController@view')->name('permission.view');
                Route::post('permission/delete', 'PermissionController@delete')->name('permission.delete');
            /** permission */

            /** access */
                Route::any('access', 'AccessController@index')->name('access');
                Route::get('access/edit', 'AccessController@edit')->name('access.edit');
                Route::patch('access/update/{id?}', 'AccessController@update')->name('access.update');
                Route::get('access/view', 'AccessController@view')->name('access.view');
            /** access */
        /** access control */

        /** users */
            Route::any('user', 'UserController@index')->name('user');
            Route::get('user/create', 'UserController@create')->name('user.create');
            Route::post('user/insert', 'UserController@insert')->name('user.insert');
            Route::get('user/view/{id?}', 'UserController@view')->name('user.view');
            Route::get('user/edit/{id?}', 'UserController@edit')->name('user.edit');
            Route::patch('user/update', 'UserController@update')->name('user.update');
            Route::post('user/change-status', 'UserController@change_status')->name('user.change.status');
            Route::post('user/profile-remove', 'UserController@profile_remove')->name('user.profile.remove');
        /** users */

        /** settings */
            Route::get('settings', 'SettingsController@index')->name('settings');
            Route::post('settings/update', 'SettingsController@update')->name('settings.update');
            Route::post('settings/update/logo', 'SettingsController@logo_update')->name('settings.update.logo');
        /** settings */

        /** profile */
            Route::get('profile', 'DashboardController@profile')->name('profile');
            Route::post('profile-update', 'DashboardController@profile_update')->name('profile.update');
        /** profile */
    });

    Route::get("{path}", function(){ return view('404'); })->where('path', '.+');
});
<?php

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
    return view('welcome');
});

Auth::routes();
Route::post('/login/social', 'Auth\LoginController@loginSocial');
Route::get('/login/callback', 'Auth\LoginController@loginCallback');

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
// Route::prefix('admin')->group(function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');
    // Password Reset Routes...
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

    Route::group(['middleware' => 'can:admin'], function () {
        Route::get('/home', 'HomeController@index')->name('home');
    });
    // Socialate bellow
    Route::post('/login/social', 'Auth\LoginController@loginSocial');
    Route::get('/login/callback', 'Auth\LoginController@loginCallback');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/test', function () {
        echo 'Ola Mundo...';
    });
});

// Outra abordagem abaixo
// Route::get('/test', function () {
//     echo 'Ola Mundo...';
// })->middleware('auth');

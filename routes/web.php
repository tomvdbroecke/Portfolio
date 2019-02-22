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

// Regular routes
Route::get('/', 'PageController@home');

// Dashboard routes
Route::get('/dashboard', 'UserController@dashboard');
Route::get('/dashboard/projects', 'UserController@projects');
Route::get('/dashboard/projects/{projectName}', ['uses' => 'UserController@viewProject', 'as' => 'projectName']);
Route::get('/dashboard/account', 'UserController@account');
Route::post('/dashboard/account', 'UserController@editAccount');

// Embed route
Route::get('/dashboard/projects/embed/{projectName}', ['uses' => 'UserController@embedProject', 'as' => 'projectName']);

// User routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

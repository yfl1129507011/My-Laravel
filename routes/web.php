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

Route::get('/', 'IndexController@home')->name('home');
Route::get('/help', 'IndexController@help')->name('help');
Route::get('/about', 'IndexController@about')->name('about');

//注册路由定义
Route::get('signup', 'UserController@signup')->name('signup');
//定义用户资源路由（满足RESTful规则）
Route::resource('user', 'UserController');

//显示登录页面
Route::get('signin', 'SessionsController@create')->name('signin');
//创建登录回话
Route::post('signin', 'SessionsController@store')->name('signin');
//销毁回话（退出登录）
Route::delete('signout', 'SessionsController@destroy')->name('signout');

// 邮箱确认
Route::get('signup/confirm/{token}', 'UserController@confirmEmail')->name('confirm_email');

// 密码重置功能
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

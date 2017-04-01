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

Auth::routes();

//Esim parametreista middlewarelle
//Route::get('/home', 'testi@index')->middleware('App\Http\Middleware\CheckRole:admin');

Route::get('/', 'HomeController@index');

Route::get('/admin', 'AdminController@index')->name('admin');

Route::get('/account', 'AccountController@index')->name('account');
Route::get('/account/{id}', 'AccountController@show')->name('show');
Route::post('/account/{id}/update', 'AccountController@save')->name('account.id.update');
Route::post('/account/{id}/addrole', 'AccountController@addRole')->name('add.role');
Route::post('/account/{id}/removerole', 'AccountController@removeRole')->name('remove.role');

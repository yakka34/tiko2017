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

Route::get('/', 'HomeController@index')->name('home');

Route::get('/admin', 'AdminController@index')->name('admin');

Route::get('/task','TaskController@createTask')->name('create.task');
Route::get('/task/{id}','TaskController@show')->name('show.task');
Route::post('/task/{id}','TaskController@update')->name('update.task');
Route::post('/task','TaskController@save')->name('save.task');

// "API routes" for user tasks and task lists
Route::get('/user/tasks', 'UserController@tasks')->name('user.tasks');
Route::get('/user/tasklists', 'UserController@tasklists')->name('user.tasklists');

Route::get('/missioncontrol', 'TaskAndTasklistController@index')->name('missioncontrol');

Route::get('/account', 'AccountController@index')->name('account');
Route::get('/account/{id}', 'AccountController@show')->name('show');
Route::post('/account/{id}/update', 'AccountController@save')->name('account.id.update');
Route::post('/admin/{id}/addrole', 'AdminController@addRole')->name('add.role');
Route::post('/admin/{id}/removerole', 'AdminController@removeRole')->name('remove.role');

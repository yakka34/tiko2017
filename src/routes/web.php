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

Route::get('/stats', 'UserStatsController@index')->name('stats');

Route::get('/admin', 'AdminController@index')->name('admin');

Route::get('/task','TaskController@createTask')->name('create.task');
Route::get('/task/{id}/edit','TaskController@edit')->name('edit.task');
Route::post('/task/{id}/edit','TaskController@update')->name('update.task');
Route::post('/task','TaskController@save')->name('save.task');

Route::get('/tasklist/{id}','TaskAndTasklistController@show')->name('show.tasklist');
Route::get('/tasklist/{tasklistId}/task/{id}', 'TaskController@show')->name('show.task');

// <Session routes>
Route::get("/session/{tasklist_id}/start","SessionController@start")->name('session.start');
Route::get("/session/{session_id}/tasklist","SessionTasklistController@index")->name('session.show.tasklist');
Route::get("/session/{session_id}/tasklist/{tasklist_id}/task/{task_id}","SessionTasklistController@show")->name('session.show.task');
Route::post("/session/{session_id}/tasklist/{tasklist_id}/task/{task_id}","SessionTasklistController@answer")->name('session.answer.task');
Route::get("/session/{session_id}/tasklist/{tasklist_id}/task/{task_id}/answer", "SessionTasklistController@showAnswer")->name('session.show.answer');
Route::get("/session/{session_id}/stop","SessionController@stop")->name('session.stop');
// </session routes>

// "API routes" for user tasks and task lists
Route::group(['middleware' => 'auth'], function() {
    Route::get('/user/tasks', 'UserController@tasks')->name('user.tasks');
    Route::get('/user/tasklists', 'UserController@tasklists')->name('user.tasklists');
});

Route::get('/missioncontrol', 'TaskAndTasklistController@index')->name('missioncontrol');
Route::post('/missioncontrol/tasklist/create', 'TaskAndTasklistController@saveTasklist');

Route::get('/account', 'AccountController@index')->name('account');
Route::get('/account/{id}', 'AccountController@show')->name('show');
Route::post('/account/{id}/update', 'AccountController@save')->name('account.id.update');
Route::post('/admin/{id}/addrole', 'AdminController@addRole')->name('add.role');
Route::post('/admin/{id}/removerole', 'AdminController@removeRole')->name('remove.role');

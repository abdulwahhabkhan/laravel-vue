<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('login', 'API\Auth\UserController@login');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix'=>'/v1', 'middleware'=>['auth:api'] ], function (){
    Route::get('tickets', ['uses'=>'TicketController@index', 'as'=>'ticket.list']);
    Route::get('users/list', ['uses'=>'UserController@list', 'as'=>'users.list']);

    Route::get('ticket/{id}/comments', ['uses'=>'TicketController@comments', 'as'=>'ticket.comments']);
    Route::resource('ticket', 'TicketController',['except'=>['index', 'create', 'edit']]);
    Route::put('ticket/{id}/dates', ['uses'=>'TicketController@updateDates', 'as'=>'ticket.update.dates']);

    Route::get('comments', ['uses'=>'CommentController@index', 'as'=>'comment.list']);
    Route::resource('comment', 'CommentController',['except'=>['index', 'create', 'edit']]);
    Route::get('projects', ['uses'=>'ProjectController@index', 'as'=>'project.list']);
    Route::get('project/tickets/{id}', ['uses'=>'ProjectController@tickets', 'as'=>'project.ticket']);
    Route::put('project/{id}/complete', ['uses'=>'ProjectController@completed', 'as'=>'project.completed']);
    Route::resource('project', 'ProjectController',['except'=>['index', 'create', 'edit']]);
});

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
//public routes
Route::group(['prefix'=>'/v1', 'middleware'=>['guest:api'] ], function (){
    Route::post('login', 'API\Auth\UserController@login');

});

//private routes
Route::group(['prefix'=>'/v1', 'middleware'=>['auth:api'] ], function (){
    Route::post('logout', 'API\Auth\UserController@logout');
    Route::get('user', function (Request $request) {
        $user = $request->user();
        return ['name'=> $user->name, 'email'=> $user->email, 'photo'=>url($user->image)];
    });
    Route::get('tickets', ['uses'=>'TicketController@index', 'as'=>'ticket.list']);
    Route::get('users/list', ['uses'=>'UserController@list', 'as'=>'users.list']);

    Route::get('ticket/{id}/comments', ['uses'=>'TicketController@comments', 'as'=>'ticket.comments']);
    Route::resource('ticket', 'TicketController',['except'=>['index', 'create', 'edit']]);
    Route::put('ticket/{id}/dates', ['uses'=>'TicketController@updateDates', 'as'=>'ticket.update.dates']);
    Route::get('ticket/{id}/time-entries', ['uses'=>'TicketController@timeEntries', 'as'=>'ticket.time-entries']);
    Route::get('ticket/{id}/files', ['uses'=>'TicketController@files', 'as'=>'ticket.files']);

    Route::get('comments', ['uses'=>'CommentController@index', 'as'=>'comment.list']);
    Route::resource('comment', 'CommentController',['except'=>['index', 'create', 'edit']]);
    Route::get('projects', ['uses'=>'ProjectController@index', 'as'=>'project.list']);
    Route::get('project/tickets/{id}', ['uses'=>'ProjectController@tickets', 'as'=>'project.ticket']);
    Route::get('project/{id}/time', ['uses'=>'ProjectController@timeLog', 'as'=>'project.time']);
    Route::get('project/{id}/summary', ['uses'=>'ProjectController@summary', 'as'=>'project.summary']);
    Route::get('project/{id}/files', ['uses'=>'ProjectController@files', 'as'=>'project.files']);
    Route::get('project/{id}/time-summary', ['uses'=>'ProjectController@timeSummary', 'as'=>'project.time.summary']);
    Route::put('project/{id}/complete', ['uses'=>'ProjectController@completed', 'as'=>'project.completed']);
    Route::resource('project', 'ProjectController',['except'=>['index', 'create', 'edit']]);

    Route::get('time-log', ['uses'=>'TimeLogController@index', 'as'=>'time-log.list']);
    Route::resource('time-log', 'TimeLogController',['except'=>['index', 'create', 'edit']]);

    Route::get('files', ['uses'=>'FileController@index', 'as'=>'file.list']);
    Route::put('file/{id}/details', ['uses'=>'FileController@detail', 'as'=>'file.detail']);
    Route::resource('files', 'FileController',['except'=>['index', 'create', 'edit']]);

});

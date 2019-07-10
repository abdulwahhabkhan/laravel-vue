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
    Route::get('ticket', ['uses'=>'TicketController@index', 'as'=>'ticket.list']);
    Route::resource('ticket', 'TicketController',['except'=>['index', 'create', 'edit']]);
    Route::get('project', ['uses'=>'ProjectController@index', 'as'=>'project.list']);
    Route::resource('project', 'ProjectController',['except'=>['index', 'create', 'edit']]);
});
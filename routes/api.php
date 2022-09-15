<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    
    Route::get('userList','AuthController@getUserList')->middleware(['auth:api']);
    Route::post('logout','AuthController@logout')->middleware(['auth:api']);
});

Route::group(['prefix' => 'messages', 'middleware' => 'auth:api' ], function () {
    Route::post('/', 'MessageController@send');
    Route::get('/', 'MessageController@index');
    // Route::get('/{sender_id}', 'MessageController@show');

    Route::get('/{conversation_id}', 'MessageController@showConversation');
});

Route::group(['prefix' => 'customers', 'middleware' => 'auth:api' ], function () {
    Route::get('/', 'CustomerController@index');
    Route::delete('/{customer_id}', 'CustomerController@delete');
});

Route::get('test', 'TestController@index');
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// api resource
Route::apiResources(['blogs' => 'API\BlogsController']);

// get request 
Route::get('get_deleted_blogs', 'API\BlogsController@get_deleted_blogs');
Route::get('search_deleted_blogs/{id}', 'API\BlogsController@search_deleted_blogs');

// delete request
Route::delete('restore_blogs/{id}', 'API\BlogsController@restore_blogs');
Route::delete('delete_blogs_permanent/{id}', 'API\BlogsController@delete_blogs_permanent');

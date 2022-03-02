<?php

use App\Http\Controllers\Consumer;
use App\Http\Controllers\Administrator;
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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/user/create/list', [Consumer::class, 'user_create_list']);
    Route::get('/user/grab/list', [Consumer::class, 'user_grab_list']);
    Route::post('/user/grab/tasks', [Consumer::class, 'user_grab_tasks']);
    Route::post('/user/add/task', [Consumer::class, 'user_add_task']);
});
Route::post('/user/validate', [Consumer::class, 'user_Login']);
Route::post('/user/register', [Consumer::class, 'user_register']);



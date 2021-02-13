<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenCheck;
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

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::get("/tasks",[TaskController::class,'index']);
    Route::get("/tasks/{id}",[TaskController::class,'show']);
    Route::post("/tasks",[TaskController::class,'store']);
    Route::put("/tasks/{id}",[TaskController::class,'update']);
    Route::delete("/tasks/{id}",[TaskController::class,'destroy']);
});

Route::post("/register",[UserController::class,'register']);
Route::post("/login",[UserController::class,'login']);

// Route::get('(.*)', function () {
//     return response([
//         'message' => ['These credentials do not match our records.']
//     ], 404);
// });


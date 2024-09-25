<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Buildings\BuildingController;
use App\Http\Controllers\Cameras\CameraController;
use App\Http\Controllers\Departments\DepartmentController;
use App\Http\Controllers\Groups\GroupController;
use App\Http\Controllers\Schedules\ScheduleListController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [LoginController::class, 'login']);
Route::post('/update/login/{user}', [LoginController::class, 'updateLoginPassword']);
Route::get('/buildings',[BuildingController::class,'index']);
Route::post('/room/set/camera',[BuildingController::class,'setRoomCamera']);
Route::get('/groups',[GroupController::class,'getAllGroup']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum','role:admin']], function () {
    Route::post('/camera/import', [CameraController::class, 'importExel']);
    Route::get('users', [UserController::class, 'paginate']);
    Route::get('departments', [DepartmentController::class, 'getAll']);
    Route::apiResource('cameras', CameraController::class);
});

Route::group(['prefix' => 'employee', 'middleware' => ['auth:sanctum', 'role:teacher']], function () {

});

Route::get('/schedule/list',[ScheduleListController::class,'getScheduleListHemis']);

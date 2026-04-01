<?php

use App\Http\Controllers\Api\MyTaskApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Các API cho Module Công việc
Route::prefix('tasks')->group(function () {
    Route::get('/', [TaskController::class, 'index']);          // Lấy danh sách
    Route::post('/', [TaskController::class, 'store']);         // Thêm mới
    Route::get('/{id}', [TaskController::class, 'show']);       // Xem chi tiết
    Route::put('/{id}', [TaskController::class, 'update']);     // Cập nhật
    Route::delete('/{id}', [TaskController::class, 'destroy']); // Xóa
    Route::post('/{id}/complete', [TaskController::class, 'complete']); // Hoàn thành
});

// API lấy danh sách nhân viên
Route::get('/users-list', [TaskController::class, 'getUsers']);
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/my-tasks', [MyTaskApiController::class, 'index']);
    Route::get('/my-tasks/{id}', [MyTaskApiController::class, 'show']);
    Route::post('/my-tasks/{id}/complete', [MyTaskApiController::class, 'markAsComplete']);
});


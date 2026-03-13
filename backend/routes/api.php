<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\DashBoardController;
use App\Http\Middleware\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashBoardContoller;
use App\Http\Controllers\LendController;
use App\Http\Controllers\UserController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function (){
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/admin-dashboard', function(){
        return response()->json(['message'=>'เข้าสู่โซน Admin สำเร็จ']);
    });

    Route::get('/dashboard/stats', [DashBoardContoller::class, 'allStats']);

    Route::get('/category', [CategoryController::class, 'index']);
    Route::post('/category', [CategoryController::class, 'store']);
    Route::delete('/category/{id}', [CategoryController::class, 'destroy']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);


    Route::get('/books', [BookController::class, 'index']);
    Route::post('/books', [BookController::class, 'store']);
    Route::put('/books/{id}', [BookController::class, 'update']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);


    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::put('/users/{id}', [UserController::class, 'update']); 
    Route::get('/users', [UserController::class, 'index']); 


    Route::get('/lend', [LendController::class, 'index']);
    Route::post('/lend', [LendController::class, 'store']);
    Route::delete('/lend/soft/{id}', [LendController::class, 'returnBook']);
    Route::delete('/lend/{id}', [LendController::class, 'destroy']);
});

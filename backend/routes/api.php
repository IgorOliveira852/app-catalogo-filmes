<?php


use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;


Route::post('/register', RegisterController::class);
Route::post('/login', AuthController::class);

Route::middleware('auth:sanctum')->group(fn() => [
    Route::get('/user', fn(\Illuminate\Http\Request $request) => response()->json($request->user())),
]);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\NoteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
| 7|kR2p1BxgmIvvgStxaBsPPzO1L0aEG7vGBGoI0eg8
*/

Route::middleware('guest')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/login/google', [AuthController::class, 'google']);
    Route::get('/login/google/callback', [AuthController::class, 'googleCallback']);
});



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return ['user'=> $request->user() ];
    });

    Route::apiResource('notes', NoteController::class);
    
});

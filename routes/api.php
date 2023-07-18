<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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


Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required',
        'password' => 'required',
        'device_name' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if ( !$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    $user->tokens()->where('name', $request->device_name)->delete(); // delete old tokens of current device
    return $user->createToken($request->device_name)->plainTextToken;
});

Route::middleware('auth:sanctum')->post('/logout', function(){
    return false;
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

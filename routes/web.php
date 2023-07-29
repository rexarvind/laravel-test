<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

use Laravel\Socialite\Facades\Socialite;

Route::get('/', function () {
    return view('layouts.front');
});

Route::get('/welcome', function () {
    return view('welcome');
});

Route::resource('projects', ProjectController::class);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('guest')->group(function(){
    Route::get('/login/github', function () {
        return Socialite::driver('github')->redirect();
    });
    Route::get('/login/callback/github', function () {
        $user = Socialite::driver('github')->user();
        // $user->token
        dd($user);
    });


    Route::get('/login/google', function () {
        return Socialite::driver('google')->redirect();
    });
    Route::get('/login/callback/google', function () {
        $user = Socialite::driver('google')->user();
        // $user->token
        dd($user);
    });
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

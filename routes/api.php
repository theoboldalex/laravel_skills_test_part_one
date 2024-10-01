<?php

use App\Http\Controllers\UserDetailsController;
use App\Http\Controllers\UserSignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users/{user}', UserDetailsController::class)->name('user');

Route::post('/signup', UserSignupController::class)->name('user.signup');

<?php

use App\Http\Controllers\User\UserDeleteController;
use App\Http\Controllers\User\UserDetailsController;
use App\Http\Controllers\User\UserSignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users/{user}', UserDetailsController::class)->name('user');
Route::delete('/users/{user}', UserDeleteController::class)->name('user.delete');

Route::post('/signup', UserSignupController::class)->name('user.signup');

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Chats\ChatsController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Groups\GroupsChatsController;
use PHPUnit\Framework\Attributes\Group;

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

Route::middleware(['auth:sanctum','verified'])->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');

// Email Verification
Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

// Password Reset
Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);

// User Profile
Route::middleware(['auth:sanctum'])->group(function () {
    Route::put('update_name', [AuthController::class, 'update_name'])->middleware('auth:sanctum');
    Route::put('update_email', [AuthController::class, 'update_email'])->middleware('auth:sanctum');
    Route::put('update_profile_picture', [AuthController::class, 'update_profile_picture'])->middleware('auth:sanctum');
});

// User Chats
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('list', action: [ChatsController::class, 'list']);
});

// Group Chats
route::middleware(['auth:sanctum'])->group(function () {
    Route::post('create', [GroupsChatsController::class, 'create']);
    Route::post('delete', [GroupsChatsController::class, 'delete']);
    Route::get('list', [GroupsChatsController::class, 'list']);

});

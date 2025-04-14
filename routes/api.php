<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Chat\ChatController;
use App\Http\Controllers\Group\GroupController;
use App\Http\Controllers\Group\GroupMessageController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\Sidebar\SidebarController;
use App\Http\Controllers\Users\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('login', [AuthController::class, 'login']);
// Route::post('register', [AuthController::class, 'register']);
Route::post('logout/{uuid}', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');

// Email Verification
Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

// Password Reset
Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);

Route::post('/user_register', [UsersController::class, 'register']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-code', [AuthController::class, 'isVerificationCodeValid']);

Route::middleware(['auth:sanctum'])->group(function () {
    // User
    Route::apiResource('group-messages', GroupMessageController::class);
    Route::get('/users', [UsersController::class, 'index']);
    Route::post('users/{uuid}', [UsersController::class, 'update']);
    Route::get('users/{uuid}', [UsersController::class, 'show']);

    Route::apiResource('chats', ChatController::class)->except(['update', 'index'])->parameters(['chat' => 'uuid']);
    Route::apiResource('messages', MessageController::class)->except(['index'])->parameters(['messages' => 'uuid']);
    Route::apiResource('groups', GroupController::class)->except('update', 'index')->parameters(['groups' => 'uuid']);
    Route::post('groups/{uuid}', [GroupController::class, 'update']);

    Route::get('/chat/messages', [MessageController::class, 'show_messages']);
    Route::post('add-user', [GroupController::class, 'addUser']);

    Route::get('sidebar', SidebarController::class);

});

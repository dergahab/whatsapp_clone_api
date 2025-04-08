<?php

use App\Http\Controllers\Chat\ChatController;
	use App\Http\Controllers\Message\MessageController;
	use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Api\EmailVerificationController;

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
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
// Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');

// Email Verification
Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

// Password Reset
Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);


Route::middleware(['auth:sanctum'])->group(function () {
	// User
    Route::get('/users', [UsersController::class, 'index']);
    Route::post('/user_register', [UsersController::class, 'register']);
    Route::put('/update_name', [UsersController::class, 'updateName']);
    Route::put('/update_email', [UsersController::class, 'updateEmail']);
    Route::put('/update_profile_picture', [UsersController::class, 'updateProfilePicture']);


	Route::apiResource('chats', ChatController::class)->except(['update'])->parameters(['chat' => 'uuid']);
	Route::apiResource('messages', MessageController::class)->except(['index'])->parameters(['messages' => 'uuid']);
    Route::get('/chat/messages', [MessageController::class, 'show_messages']);

    Route::post('message',[ChatController::class,'message']);
});






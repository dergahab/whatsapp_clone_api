<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Chats\ChatsController;
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

Route::middleware(['auth:sanctum','verified'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
Route::post('reset-password', [NewPasswordController::class, 'reset']);



Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('list', [ChatsController::class, 'list']);


    // Route::post('create', [ChatsController::class, 'create']);
    // Route::post('delete', [ChatsController::class, 'delete']);

    // Route::post('update', [ChatsController::class, 'update']);
    // Route::post('add-user', [ChatsController::class, 'addUser']);

    // Route::post('remove-user', [ChatsController::class, 'removeUser']);
    // Route::post('send-message', [ChatsController::class, 'sendMessage']);

    // Route::post('get-messages', [ChatsController::class, 'getMessages']);
    // Route::post('get-users', [ChatsController::class, 'getUsers']);
    // Route::post('get-user', [ChatsController::class, 'getUser']);

});

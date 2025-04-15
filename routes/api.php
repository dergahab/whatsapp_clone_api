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


//// Email Verification
//Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:sanctum');
//Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:sanctum');

// Password Reset
//Route::post('forgot-password', [NewPasswordController::class, 'forgotPassword']);
//Route::post('reset-password', [NewPasswordController::class, 'reset']);

//AuthController
Route::post('login', [AuthController::class, 'login']);
Route::post('logout/{uuid}', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// UsersController
Route::post('/user_register', [UsersController::class, 'register']);
// AuthController
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/verify-code', [AuthController::class, 'isVerificationCodeValid']);

Route::middleware(['auth:sanctum'])->group(function () {
    // UsersController
    Route::post('users/{uuid}', [UsersController::class, 'update']);
    Route::get('users/{uuid}', [UsersController::class, 'show']);
    Route::get('/users', [UsersController::class, 'index']);
    //GroupController
    Route::apiResource('groups', GroupController::class)->except('update', 'index')->parameters(['groups' => 'uuid']);
    Route::post('groups/{uuid}', [GroupController::class, 'update']);
    Route::post('add-user', [GroupController::class, 'addUser']);
    //GroupMessageController
    Route::apiResource('group-messages', GroupMessageController::class)->except('index','show', 'update','destroy');
    Route::get('/group/messages', [GroupMessageController::class, 'show_messages']);
    //ChatController
    Route::apiResource('chats', ChatController::class)->except(['update', 'index'])->parameters(['chat' => 'uuid']);
   //MessageController
    Route::apiResource('messages', MessageController::class)->except(['index'])->parameters(['messages' => 'uuid']);
    Route::get('/chat/messages', [MessageController::class, 'show_messages']);
   //SidebarController
    Route::get('sidebar', SidebarController::class);
});

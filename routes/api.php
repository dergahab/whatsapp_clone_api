<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Chats\ChatsController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Api\NewPasswordController;
use App\Http\Controllers\Groups\GroupsChatsController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Groups\GroupMessagingController;
use App\Http\Controllers\Groups\GroupManagementController;

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

// User Profile
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/users', [UsersController::class, 'index']);
    Route::put('/update_name', [UsersController::class, 'updateName']);
    Route::put('/update_email', [UsersController::class, 'updateEmail']);
    Route::put('/update_profile_picture', [UsersController::class, 'updateProfilePicture']);

//    Route::put('update_name', [AuthController::class, 'update_name'])->middleware('auth:sanctum');
//    Route::put('update_email', [AuthController::class, 'update_email'])->middleware('auth:sanctum');
//    Route::put('update_profile_picture', [AuthController::class, 'update_profile_picture'])->middleware('auth:sanctum');
});

// User Chats
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/private/send', [ChatsController::class, 'sendPrivateMessage']);

    Route::post('/private/show', [ChatsController::class, 'getPrivateMessages']);
    Route::post('/private/update', [ChatsController::class, 'updatePrivateMessage']);
    Route::post('/private/delete', [ChatsController::class, 'deletePrivateMessage']);
});

// Group Chats
route::middleware(['auth:sanctum'])->group(function () {
    // Group Chats
    Route::post('/group/create', [GroupManagementController::class, 'createGroup']);
    Route::post('/group/delete', [GroupManagementController::class, 'deleteGroup']);
    Route::post('/group/leave', [GroupManagementController::class, 'leaveGroup']);
    Route::post('/group/add-user', [GroupManagementController::class, 'addUser']);
    Route::post('/group/remove-user', [GroupManagementController::class, 'removeUser']);
    Route::post('/group/update', [GroupManagementController::class, 'updateGroup']);
    Route::get('/group/show', [GroupManagementController::class, 'showGroup']);
    // Group Messages
    Route::post('/group/messages', [GroupMessagingController::class, 'showGroupMessages']);
    Route::post('/group/send', [GroupMessagingController::class, 'sendGroupMessage']);
    Route::post('/group/messages/update', [GroupMessagingController::class, 'updateGroupMessage']);
    Route::post('/group/messages/delete', [GroupMessagingController::class, 'deleteGroupMessage']);
    // Group Users
    Route::get('/groups/list', [GroupsChatsController::class, 'listGroups']);
    Route::post('/group/list-users', [GroupsChatsController::class, 'listUsers']);
    Route::post('/group/list-messages', [GroupsChatsController::class, 'listMessages']);
    Route::post('/group/list-users-messages', [GroupsChatsController::class, 'listUsersMessages']);
});

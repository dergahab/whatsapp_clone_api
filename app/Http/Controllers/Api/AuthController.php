<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $profilePicturePath = null;

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $profilePicturePath = $profilePicture->store('profile_pictures', 'public');
        }

        $user = User::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_picture' => $profilePicturePath,
        ]);

        $count_all = User::count();
        event(new Registered($user));
        $token = $user->createToken('authtoken');

        if ($user) {
            return response()->json(
                [
                    'message' => 'Giriş uğurlu oldu!',
                    'data' => [
                        'Authorization' => [
                            'access_token' => $token->plainTextToken,
                            'token_type' => 'Bearer',
                            'expires_in' => 60 * 24 * 365,
                        ],
                        'user_info' => $user
                    ],
                    'count' => $count_all,
                    'status' => Response::HTTP_CREATED,
                ]
            );
        } else {
            return response()->json(
                [
                    'message' => 'Giriş uğurlu olmadı!',
                    'status' => Response::HTTP_BAD_REQUEST,
                ]
            );
        }
    }

    public function login(LoginRequest $request)
    {      
        $request->authenticate();

        $count_all = User::count();
        $token = $request->user()->createToken('authtoken');

        if ($token) {
            return response()->json(
                [
                    'message' => 'Giriş uğurlu oldu!',
                    'data' => [
                        'Authorization' => [
                            'access_token' => $token->plainTextToken,
                            'token_type' => 'Bearer',
                            'expires_in' => 60 * 24 * 365,
                        ],
                        'user_info' => $request->user(),
                    ],
                    'count' => $count_all,
                    'status' => Response::HTTP_OK,

                ]
            );
        } else {
            return response()->json(
                [
                    'message' => 'Giriş uğurlu olmadı!',
                    'status' => Response::HTTP_BAD_REQUEST,
                ]
            );
        }
    }

    public function logout(Request $request)
    {

        $request->user()->tokens()->delete();

        if ($request->user()->tokens()->delete()) {
            return response()->json(
                [
                    'message' => 'Çıxış uğurlu oldu!',
                    'status' => Response::HTTP_OK,
                ]
            );
        } else {
            return response()->json(
                [
                    'message' => 'Çıxış uğurlu olmadı!',
                    'status' => Response::HTTP_BAD_REQUEST,
                ]
            );
        }
    }
}

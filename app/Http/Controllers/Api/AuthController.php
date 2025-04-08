<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
//        event(new Registered($user));
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
                        'user_info' => $user,
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

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email = strtolower(trim($request->email));
        $verificationCode = rand(100000, 999999);

        $filePath = 'verification_codes/codes.json';
        $existingCodes = [];
        if (Storage::exists($filePath)) {
            $existingCodes = json_decode(Storage::get($filePath), true) ?? [];
        }
        $existingCodes[$email] = $verificationCode;
        Storage::put($filePath, json_encode($existingCodes, JSON_PRETTY_PRINT));

        Log::info('Verification code saved to storage', [
            'email' => $email,
            'code' => $verificationCode
        ]);

        $user = User::where('email', $email)->first();

        Mail::html('<h1>Your verification code is: ' . $verificationCode . '</h1>', function ($message) use ($user) {
            $message->to($user->email)
                ->from('c.mhatzadeh@gmail.com', 'Chat App')
                ->subject('Password Reset Verification Code');
        });

        return response()->json(['message' => 'Verification code sent to your email.']);
    }



    public function resetPassword(ResetPasswordRequest $request)
    {
        $email = strtolower(trim($request->email));
        $storedVerificationCode = $this->getStoredVerificationCode($email);

        if (!$storedVerificationCode) {
            return response()->json(['message' => 'Verification code not found.'], 400);
        }

        if ($storedVerificationCode != trim($request->verification_code)) {
            Log::warning("Verification code mismatch for {$email}", [
                'stored_code' => $storedVerificationCode,
                'provided_code' => $request->verification_code,
            ]);
            return response()->json(['message' => 'Invalid verification code.'], 400);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            Log::error("User not found for {$email}");
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->update(['password' => Hash::make($request->new_password)]);
        $this->removeStoredVerificationCode($email);

        Log::info("Password reset successfully for {$email}");

        return response()->json(['message' => 'Password reset successfully.']);
    }

    protected function getStoredVerificationCode($email)
    {
        $filePath = 'verification_codes/codes.json';

        if (!Storage::exists($filePath)) {
            return null;
        }

        $codes = json_decode(Storage::get($filePath), true);
        return $codes[$email] ?? null;
    }

    protected function removeStoredVerificationCode($email)
    {
        $filePath = 'verification_codes/codes.json';

        if (!Storage::exists($filePath)) {
            return;
        }

        $codes = json_decode(Storage::get($filePath), true);

        if (isset($codes[$email])) {
            unset($codes[$email]);
            Storage::put($filePath, json_encode($codes, JSON_PRETTY_PRINT));
        }
    }
}

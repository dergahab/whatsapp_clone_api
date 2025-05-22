<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckPasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Response;
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
        try {
            $email = trim(strtolower($request->input('email')));
            $password = $request->input('password');
            Log::info('Login email:', ['email' => $email]);
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

            if (! $user) {
                Log::warning('User not found', ['email' => $email]);

                return response()->json([
                    'message' => 'User not found',
                    'status' => Response::HTTP_NOT_FOUND,
                ]);
            }
            if (! Hash::check($password, $user->password)) {
                Log::warning('The password is incorrect', ['email' => $email]);

                return response()->json([
                    'message' => 'The password is incorrect',
                    'status' => Response::HTTP_UNAUTHORIZED,
                ]);
            }
            $token = $user->createToken('authtoken');
            if ($token) {
                return response()->json([
                    'message' => 'Login successful!',
                    'data' => [
                        'Authorization' => [
                            'access_token' => $token->plainTextToken,
                            'token_type' => 'Bearer',
                            'expires_in' => 60 * 24 * 365,
                        ],
                        'user_info' => $user,
                    ],
                    'status' => Response::HTTP_OK,
                ]);
            } else {
                Log::error('Failed to generate token', ['user_id' => $user->id]);

                return response()->json([
                    'message' => 'Failed to generate token',
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Login error: '.$e->getMessage());

            return response()->json([
                'message' => 'An error occurred',
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ]);
        }
    }

    public function logout(LogoutRequest $request)
    {
        $user = User::where('uuid', $request->route('uuid'))->first();

        if ($user) {
            $user->tokens()->delete();

            return response()->json(
                [
                    'message' => 'The speech was successful!',
                    'status' => Response::HTTP_OK,
                ]
            );
        } else {
            return response()->json(
                [
                    'message' => 'User not found!',
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
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
            'code' => $verificationCode,
        ]);

        $user = User::where('email', $email)->first();

        Mail::html('<h1>Your verification code is: '.$verificationCode.'</h1>', function ($message) use ($user) {
            $message->to($user->email)
                ->from('c.mhatzadeh@gmail.com', 'Chat App')
                ->subject('Password Reset Verification Code');
        });

        return rp_response([], message: __('VerificationCodeSentSuccessfully'), status: Response::HTTP_OK);
    }

    protected function isVerificationCodeValid(CheckPasswordRequest $request)
    {
        $email = strtolower(trim($request->email));
        $verificationCode = $request->verification_code;
        $filePath = 'verification_codes/codes.json';
        if (! Storage::exists($filePath)) {
            return rp_response([], message: __('VerificationCodeNotFound'), status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $existingCodes = json_decode(Storage::get($filePath), true) ?? [];
        if (isset($existingCodes[$email]) && $existingCodes[$email] == $verificationCode) {
            return rp_response([], message: __('VerificationSuccessful'), status: Response::HTTP_OK);
        }

        return rp_response([], message: __('InvalidVerificationCode'), status: Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validatedData();
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return rp_response([], message: __('UserNotFound'), status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $user->password = $validated['password'];
        $user->save();
        $this->removeStoredVerificationCode($request->email);

        return rp_response([], message: __('PasswordSuccessfullyReset'), status: Response::HTTP_OK);
    }

    protected function removeStoredVerificationCode($email)
    {
        $filePath = 'verification_codes/codes.json';

        if (! Storage::exists($filePath)) {
            return;
        }
        $codes = json_decode(Storage::get($filePath), true);

        if (isset($codes[$email])) {
            unset($codes[$email]);
            Storage::put($filePath, json_encode($codes, JSON_PRETTY_PRINT));
        }
    }
}

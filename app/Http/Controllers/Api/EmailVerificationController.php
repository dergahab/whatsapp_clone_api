<?php

//
// namespace App\Http\Controllers\Api;
//
// use App\Http\Controllers\Controller;
// use Illuminate\Auth\Events\Verified;
// use Illuminate\Foundation\Auth\EmailVerificationRequest;
// use Illuminate\Http\Request;
// use Illuminate\Http\Response;
//
// class EmailVerificationController extends Controller
// {
//    public function sendVerificationEmail(Request $request)
//    {
//        if ($request->user()->hasVerifiedEmail()) {
//            return [
//                'message' => 'E-poçt artıq təsdiqlənmişdir!',
//                'status' => Response::HTTP_OK,
//            ];
//        }
//
//        $request->user()->sendEmailVerificationNotification();
//
//        return [
//            'message' => 'Doğrulama bağlantısı göndərildi!',
//            'status' => Response::HTTP_OK,
//        ];
//    }
//
//    public function verify(EmailVerificationRequest $request)
//    {
//        if ($request->user()->hasVerifiedEmail()) {
//            return [
//                'message' => 'E-poçt artıq təsdiqlənmişdir!',
//                'status' => Response::HTTP_OK,
//            ];
//        }
//
//        if ($request->user()->markEmailAsVerified()) {
//            event(new Verified($request->user()));
//        }
//
//        return [
//            'message' => 'E-poçt təsdiqləndi!',
//            'status' => Response::HTTP_OK,
//        ];
//    }
// }

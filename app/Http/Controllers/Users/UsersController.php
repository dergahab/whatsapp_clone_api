<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UpdateNameRequest;
use App\Http\Resources\Users\UpdateEmailRequest;
use App\Http\Resources\Users\UpdateProfilePictureRequest;

class UsersController extends Controller
{
    public function update_name(UpdateNameRequest $request)
    {
        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        if ($user->save()) {
            return response()->json(
                [
                    'message' => 'Adınız uğurla yeniləndi!',
                    'status' => Response::HTTP_OK,
                ]
            );
        } else {
            return response()->json(
                [
                    'message' => 'Adınız yenilənmədi!',
                    'status' => Response::HTTP_BAD_REQUEST,
                ]
            );
        }
    }

    public function update_email(UpdateEmailRequest $request)
    {
        $user = $request->user();
        $user->email = $request->email;
        $user->save();

        if ($user->save()) {
            return response()->json(
                [
                    'message' => 'Email uğurla yeniləndi!',
                    'status' => Response::HTTP_OK,
                ]
            );
        } else {
            return response()->json(
                [
                    'message' => 'Email yenilənmədi!',
                    'status' => Response::HTTP_BAD_REQUEST,
                ]
            );
        }
    }

    public function update_profile_picture(UpdateProfilePictureRequest $request)
    {
        $user = $request->user();
        $profilePicturePath = null;

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $profilePicturePath = $profilePicture->store('profile_pictures', 'public');
        }

        $user->profile_picture = $profilePicturePath;
        $user->save();

        if ($user->save()) {
            return response()->json(
                [
                    'message' => 'Profil şəkli uğurla yeniləndi!',
                    'status' => Response::HTTP_OK,
                ]
            );
        } else {
            return response()->json(
                [
                    'message' => 'Profil şəkli yenilənmədi!',
                    'status' => Response::HTTP_BAD_REQUEST,
                ]
            );
        }
    }
}

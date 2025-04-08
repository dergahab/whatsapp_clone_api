<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Models\User;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function __construct(public UserService $service) {}

    /**
     * @LRDparam search nullable|string
     */
    public function index(Request $request)
    {
        try {
            return response()->json([
                'data' => $this->service->index($request),
                'status' => Response::HTTP_OK,
            ], Response::HTTP_OK);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $ex->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateName(Request $request)
    {
        $request->validate([
            'uuid' => 'required|exists:users,uuid',
            'name' => 'required|string|max:255',
        ]);

        $user = User::where('uuid', $request->uuid)->first();
        $user->name = $request->name;
        $user->save();

        return response()->json(['message' => 'Name updated successfully', 'user' => $user]);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'uuid' => 'required|exists:users,uuid',
            'email' => 'required|email|unique:users,email,'.$request->uuid.',uuid',
        ]);

        $user = User::where('uuid', $request->uuid)->first();
        $user->email = $request->email;
        $user->save();

        return response()->json(['message' => 'Email updated successfully', 'user' => $user]);
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'uuid' => 'required|exists:users,uuid',
            'profile_picture' => 'required|url',
        ]);

        $user = User::where('uuid', $request->uuid)->first();
        $user->profile_picture = $request->profile_picture;
        $user->save();

        return response()->json(['message' => 'Profile picture updated successfully', 'user' => $user]);
    }

    public function register(StoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validatedData();
            $chat = $this->service->store($data);

            DB::commit();
            return rp_response($chat, __('DataCreatedSuccessfully'), \Symfony\Component\HttpFoundation\Response::HTTP_CREATED);

        } catch (\Exception $ex) {
            DB::rollBack();
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

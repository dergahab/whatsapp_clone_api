<?php

namespace App\Http\Controllers\Chats;

use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chats\ListRequest;

class ChatsController extends Controller
{
    public function list(ListRequest $request)
    {
        try {
            $data = $request->getUsersData();

            return response()->json([
                'message' => 'Giriş uğurlu oldu!',
                'data' => [
                    'users' => $data['users']
                ],
                'count' => $data['count'],
                'status' => Response::HTTP_OK,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Xəta baş verdi!',
                'error' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

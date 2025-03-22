<?php

namespace App\Http\Controllers\Groups;

use App\Models\User;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupCreateRequest;
use App\Http\Requests\Groups\GroupListRequest;
use App\Http\Requests\Groups\GroupDeleteRequest;

class GroupsChatsController extends Controller
{
    public function create(GroupCreateRequest $request)
    {
        try {
            $data = $request->createGroup();

            return response()->json([
                'message' => 'Qrup yaradıldı!',
                'data' => [
                    'group' => $data
                ],
                'status' => Response::HTTP_CREATED,
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Xəta baş verdi!',
                'error' => $e->getMessage(),
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(GroupDeleteRequest $request)
    {
        try {
            $request->deleteGroup();

            return response()->json([
                'message' => 'Qrup silindi!',
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

    public function list(GroupListRequest $request)
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

<?php

namespace App\Http\Controllers\Groups;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\ChatGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Groups\GroupListRequest;

class GroupsChatsController extends Controller
{

    public function listGroups(Request $request)
    {
        $request->validate([
            'user_uuid' => 'required|uuid|exists:users,uuid',
        ]);

        $user = User::where('uuid', $request->user_uuid)->firstOrFail();

        $groups = GroupUser::where('user_id', $user->id)
            ->with('groups:id,uuid,name')
            ->get()
            ->map(function ($groupUser) {
                return [
                    'group_uuid' => $groupUser->group->uuid ?? null,
                    'group_name' => $groupUser->group->name ?? null,
                    'joined_at' => $groupUser->created_at,
                ];
            });

        return response()->json(
            [
                'user_uuid' => $user->uuid,
                'groups' => $groups,
                'count' => $groups->count(),
                'status' => Response::HTTP_OK
            ],
        );
    }

    public function listUsers(Request $request)
    {
        $request->validate([
            'group_uuid' => 'required|uuid|exists:groups,uuid',
        ]);

        $group = Group::where('uuid', $request->group_uuid)->firstOrFail();

        $users = GroupUser::with('users')
            ->where('group_id', $group->id)
            ->get()
            ->map(function ($gu) {
                return [
                    'uuid' => $gu->user->uuid,
                    'name' => $gu->user->name,
                    'email' => $gu->user->email,
                    'profile_picture' => $gu->user->profile_picture,
                ];
            });

        return response()->json(['users' => $users]);
    }

    public function listMessages(Request $request)
    {
        $request->validate([
            'group_uuid' => 'required|uuid|exists:groups,uuid',
        ]);

        $group = Group::where('uuid', $request->group_uuid)->firstOrFail();

        $groupUserIds = GroupUser::where('group_id', $group->id)->pluck('id');

        $messages = ChatGroup::with(['messages', 'fromUser.user'])
            ->whereIn('from_group_user', $groupUserIds)
            ->orWhereIn('to_group_user', $groupUserIds)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($chat) {
                return [
                    'message' => $chat->message->message ?? null,
                    'from_user' => $chat->fromUser?->user?->name,
                    'to_user' => $chat->toUser?->user?->name,
                    'created_at' => $chat->created_at,
                ];
            });

        return response()->json(['messages' => $messages]);
    }

    public function listUsersMessages(Request $request)
    {
        $request->validate([
            'group_uuid' => 'required|uuid|exists:groups,uuid',
        ]);

        $group = Group::where('uuid', $request->group_uuid)->firstOrFail();
        $groupUserIds = GroupUser::where('group_id', $group->id)->pluck('id');

        $messages = ChatGroup::with(['message', 'fromUser.user'])
            ->whereIn('from_group_user', $groupUserIds)
            ->get()
            ->groupBy(function ($item) {
                return $item->fromUser?->user?->uuid;
            });

        $result = [];

        foreach ($messages as $user_uuid => $msgs) {
            $result[] = [
                'user_uuid' => $user_uuid,
                'user_name' => $msgs->first()?->fromUser?->user?->name,
                'messages' => $msgs->map(function ($m) {
                    return [
                        'message' => $m->message->message,
                        'created_at' => $m->created_at
                    ];
                }),
            ];
        }

        return response()->json(['users_messages' => $result]);
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

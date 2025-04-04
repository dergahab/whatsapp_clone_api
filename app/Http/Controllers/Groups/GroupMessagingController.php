<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Groups\SendGroupMessageRequest;
use App\Models\Chat\Chat;
use App\Models\ChatGroup;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GroupMessagingController extends Controller
{
    public function showGroupMessages(Request $request)
    {
        $request->validate([
            'group_uuid' => 'required|uuid|exists:groups,uuid',
        ]);

        $group = Group::where('uuid', $request->group_uuid)->firstOrFail();

        $groupMessages = ChatGroup::whereHas('toGroupUser', function ($query) use ($group) {
            $query->where('group_id', $group->id);
        })
            ->with([
                'message:id,uuid,message,created_at',
                'fromGroupUser.user:id,uuid,name',
            ])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'group_uuid' => $group->uuid,
            'group_name' => $group->name,
            'messages' => $groupMessages->map(function ($msg) {
                return [
                    'message_uuid' => $msg->message->uuid ?? null,
                    'message' => $msg->message->message ?? null,
                    'from_user' => $msg->fromGroupUser->user->name ?? 'Unknown',
                    'from_user_uuid' => $msg->fromGroupUser->user->uuid ?? null,
                    'sent_at' => $msg->created_at,
                ];
            }, ),
            'count' => $groupMessages->count(),
            'status' => Response::HTTP_OK,
        ]);
    }

    public function sendGroupMessage(SendGroupMessageRequest $request)
    {
        $fromUser = GroupUser::where('uuid', $request->from_group_user_uuid)->firstOrFail();
        $toUser = GroupUser::where('uuid', $request->to_group_user_uuid)->firstOrFail();

        try {
            $chat = Chat::create([
                'uuid' => Str::uuid(),
                'message' => $request->message,
            ]);

            $chatGroup = ChatGroup::create([
                'uuid' => Str::uuid(),
                'message_id' => $chat->id,
                'from_group_user' => $fromUser->id,
                'to_group_user' => $toUser->id,
            ]);

            return response()->json(
                [
                    'message' => 'Message sent to group user',
                    'data' => $chatGroup,
                    'chat' => $chat,
                    'fromUser' => $fromUser,
                    'toUser' => $toUser,
                    'count' => $chatGroup->count(),
                    'status' => Response::HTTP_CREATED,
                ],
            );
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                [
                    'error' => $e->getMessage(),
                    'count' => 0,
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ],
            );
        }
    }

    public function updateGroupMessage(Request $request)
    {
        $request->validate([
            'message_uuid' => 'required|uuid|exists:chat,uuid',
            'message' => 'required|string',
        ]);

        $chat = Chat::where('uuid', $request->message_uuid)->firstOrFail();
        $chat->message = $request->message;
        $chat->save();

        return response()->json([
            'message' => 'Mesaj uğurla yeniləndi',
            'data' => [
                'uuid' => $chat->uuid,
                'message' => $chat->message,
                'updated_at' => $chat->updated_at,
            ],
        ]);
    }

    public function deleteGroupMessage(Request $request)
    {
        $request->validate([
            'message_uuid' => 'required|uuid|exists:chat,uuid',
        ]);

        // Chat tap
        $chat = Chat::where('uuid', $request->message_uuid)->firstOrFail();

        // ChatGroup tapıb soft delete et
        ChatGroup::where('message_id', $chat->id)->delete();

        // Chat-i soft delete et
        $chat->delete();

        return response()->json([
            'message' => 'Qrup mesajı uğurla silindi',
        ]);
    }
}

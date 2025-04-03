<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Chats\ListRequest;
use App\Http\Requests\Chats\SendPrivateMessageRequest;
use App\Models\Chat\Chat;
use App\Models\ChatPrivate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatsController extends Controller
{
    public function sendPrivateMessage(SendPrivateMessageRequest $request)
    {
        $sender = User::where('uuid', $request->from_user_uuid)->firstOrFail();
        $receiver = User::where('uuid', $request->to_user_uuid)->firstOrFail();

        $count_all = ChatPrivate::where('from_user', $sender->id)->orWhere('to_user', $sender->id)->count();
        $count_private = ChatPrivate::where('from_user', $sender->id)->where('to_user', $receiver->id)->orWhere('from_user', $receiver->id)->where('to_user', $sender->id)->count();

        try {
            $chat = Chat::create([
                'uuid' => Str::uuid(),
                'message' => $request->message,
            ]);

            $privateMessage = ChatPrivate::create([
                'uuid' => Str::uuid(),
                'message_id' => $chat->id,
                'from_user' => $sender->id,
                'to_user' => $receiver->id,
            ]);

            return response()->json(
                data: [
                    'message' => 'Message sent successfully',
                    'data' => $privateMessage,
                    'chat' => $chat,
                    'sender' => $sender,
                    'receiver' => $receiver,
                    'count_all' => $count_all,
                    'count_private' => $count_private,
                    "status" => Response::HTTP_CREATED,
                ],
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(
                data: [
                    'message' => 'Message not sent!',
                    'error' => $e->getMessage(),
                    'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                ],
            );
        }
    }

    // public function getPrivateMessages(GetPrivateMessagesRequest $request)
    // {
    //     $sender = User::where('uuid', $request->from_user_uuid)->firstOrFail();
    //     $receiver = User::where('uuid', $request->to_user_uuid)->firstOrFail();
    //     $messages = ChatPrivate::where('from_user', $sender->id)->where('to_user', $receiver->id)->orWhere('from_user', $receiver->id)->where('to_user', $sender->id)->with('message')->orderBy('created_at', 'desc')->limit(10)->get();
    //     return response()->json(
    //         data: [
    //             'message' => 'Messages retrieved successfully',
    //             'data' => $messages,
    //             'sender' => $sender,
    //             'receiver' => $receiver,
    //             "status" => Response::HTTP_OK,
    //         ],
    //     );
    // }

    public function getPrivateMessages(Request $request)
    {
        $request->validate([
            'from_user_uuid' => 'required|uuid|exists:users,uuid',
            'to_user_uuid' => 'required|uuid|exists:users,uuid',
        ]);

        $from = \App\Models\User::where('uuid', $request->from_user_uuid)->firstOrFail();
        $to = \App\Models\User::where('uuid', $request->to_user_uuid)->firstOrFail();

        $messages = ChatPrivate::with('chat')
            ->where(function ($query) use ($from, $to) {
                $query->where('from_user', $from->id)->where('to_user', $to->id);
            })
            ->orWhere(function ($query) use ($from, $to) {
                $query->where('from_user', $to->id)->where('to_user', $from->id);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function updatePrivateMessage(Request $request)
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
            'data' => $chat
        ]);
    }

    public function deletePrivateMessage(Request $request)
    {
        $request->validate([
            'message_uuid' => 'required|uuid|exists:chat,uuid',
        ]);

        $chat = Chat::where('uuid', $request->message_uuid)->firstOrFail();

        ChatPrivate::where('message_id', $chat->id)->delete();

        $chat->delete();

        return response()->json([
            'message' => 'Şəxsi mesaj uğurla silindi'
        ]);
    }

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

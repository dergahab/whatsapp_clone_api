<?php

namespace App\Http\Controllers\Groups;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Groups\GroupCreateRequest;
use App\Http\Requests\Groups\AddUserToGroupRequest;

class GroupManagementController extends Controller
{
    public function createGroup(GroupCreateRequest $request)
    {
        $group = Group::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
        ]);

        return response()->json(
            [
                'message' => 'Group created successfully',
                'data' => $group,
                "count" => $group->count(),
                "status" => Response::HTTP_CREATED
            ],
        );
    }

    public function deleteGroup(Request $request)
    {
        $request->validate([
            'group_uuid' => 'required|uuid|exists:groups,uuid',
        ]);

        $group = Group::where('uuid', $request->group_uuid)->firstOrFail();

        $group->delete();

        return response()->json(['message' => 'Group deleted successfully']);
    }

    public function leaveGroup(Request $request)
    {
        $request->validate([
            'group_uuid' => 'required|uuid|exists:groups,uuid',
            'user_uuid' => 'required|uuid|exists:users,uuid',
        ]);

        $group = Group::where('uuid', $request->group_uuid)->firstOrFail();
        $user = User::where('uuid', $request->user_uuid)->firstOrFail();

        $left = GroupUser::where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->delete();

        if ($left) {
            return response()->json(['message' => 'You left the group']);
        }

        return response()->json(['message' => 'User not in group'], 404);
    }

    public function addUser(AddUserToGroupRequest $request)
    {
        $group_uuid = $request->group_uuid;
        $group = Group::where('uuid', $group_uuid)->firstOrFail();
        $user = User::where('uuid', $request->user_uuid)->firstOrFail();

        $exists = GroupUser::where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Bu istifadəçi artıq qrupdadır.'], 409);
        }

        $groupUser = GroupUser::create([
            'uuid' => Str::uuid(),
            'group_id' => $group->id,
            'user_id' => $user->id,
        ]);

        return response()->json(
            [
                'message' => 'İstifadəçi qrupa əlavə olundu',
                'data' => $groupUser,
                "group" => $group,
                "user" => $user,
                "count" => $groupUser->count(),
                "status" => Response::HTTP_CREATED
            ],
        );
    }

    public function removeUser(Request $request)
    {
        $request->validate([
            'group_uuid' => 'required|uuid|exists:groups,uuid',
            'user_uuid' => 'required|uuid|exists:users,uuid',
        ]);

        $group = Group::where('uuid', $request->group_uuid)->firstOrFail();
        $user = User::where('uuid', $request->user_uuid)->firstOrFail();

        $deleted = GroupUser::where('group_id', $group->id)
            ->where('user_id', $user->id)
            ->delete();

        if ($deleted) {
            return response()->json(['message' => 'User removed from group']);
        }

        return response()->json(['message' => 'User was not in the group'], 404);
    }

    public function updateGroup(Request $request)
    {
        $request->validate([
            'group_uuid' => 'required|uuid|exists:groups,uuid',
            'name' => 'required|string|max:255',
        ]);

        $group = Group::where('uuid', $request->group_uuid)->firstOrFail();
        $group->name = $request->name;
        $group->save();

        return response()->json([
            'message' => 'Qrup adı uğurla yeniləndi',
            'group' => [
                'uuid' => $group->uuid,
                'name' => $group->name,
            ]
        ]);
    }

    public function showGroup()
    {
        $groups = Group::orderBy('created_at', 'desc')->get(['uuid', 'name', 'created_at']);

        return response()->json([
            'groups' => $groups
        ]);
    }


}

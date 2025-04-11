<?php

namespace App\Http\Controllers;

use App\Events\ApiNotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'user_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = [
            'message' => $request->input('message'),
            'user_id' => $request->input('user_id'),
            'timestamp' => now()->toDateTimeString(),
        ];

        event(new ApiNotificationEvent($data));

        return response()->json([
            'status' => 'success',
            'message' => 'Notification sent',
            'data' => $data,
        ], 200);
    }
}

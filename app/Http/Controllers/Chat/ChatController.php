<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Services\Chat\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\Chat\ChatStoreRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;


class ChatController extends Controller
{
    public function __construct(public ChatService $chatService)
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Chat::with('userOne', 'userTwo', "message")->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ChatStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validatedData();
            $chat = $this->chatService->store($data);
            DB::commit();

            return response()->json([
                'data' => $chat,
                'status' => Response::HTTP_OK
            ], Response::HTTP_OK);

        } catch (\Exception $ex) {
            DB::rollBack();

            return response()->json([
                'status' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'message' => $ex->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

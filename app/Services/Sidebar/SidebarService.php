<?php

namespace App\Services\Sidebar;

use App\Http\Requests\Sidebar\SearchRequest;
use App\Services\Chat\ChatService;
use App\Services\Group\GroupService;

class SidebarService
{
	public function __construct(public ChatService $chatService,public GroupService $groupService) {
	}

    public function index(SearchRequest $request, $receiver = null): array
    {
        $searchTerm = $request->input('search');

        $merged = array_merge(
            $this->chatService->index($request, $receiver),
            $this->groupService->index($request, $receiver)
        );

        if ($searchTerm) {
            $merged = array_filter($merged, function ($item) use ($searchTerm) {
                return stripos($item['name'], $searchTerm) !== false;
            });
        }
        usort($merged, function ($a, $b) {
            return strtotime($b['message_time']) <=> strtotime($a['message_time']);
        });

        return array_values($merged);
    }
}

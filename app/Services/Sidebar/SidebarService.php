<?php

namespace App\Services\Sidebar;

use App\Http\Requests\Sidebar\SearchRequest;
use App\Repositories\Sidebar\SidebarRepository;
use App\Services\Chat\ChatService;
use App\Services\Group\GroupService;

class SidebarService
{
    public function __construct(public ChatService $chatService,public GroupService $groupService) {}

    public function index(SearchRequest $request): array
    {

        $merged =  array_merge($this->chatService->index($request), $this->groupService->index($request));
        usort($merged, function ($a, $b) {
            return strtotime($b['message_time']) <=> strtotime($a['message_time']);
        });

        return $merged;
    }
}

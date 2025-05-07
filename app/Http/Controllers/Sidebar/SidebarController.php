<?php

namespace App\Http\Controllers\Sidebar;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sidebar\SearchRequest;
use App\Services\Sidebar\SidebarService;
use Symfony\Component\HttpFoundation\Response;
class SidebarController extends Controller
{
    public function __construct(public SidebarService $service) {}
    /**
     * Display a listing of the resource.
     */
    public function __invoke (SearchRequest $request)
    {
        try {
            $sidebar = $this->service->index($request);
            return rp_response($sidebar);
        } catch (\Exception $ex) {
            return rp_response([], __('FailureProcess'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}

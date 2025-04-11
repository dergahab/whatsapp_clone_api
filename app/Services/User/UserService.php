<?php

namespace App\Services\User;

use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\Base;
use Illuminate\Http\Request;

class UserService extends Base
{
    public function __construct(public UserRepository $repositories) {}

    public function index(Request $request)
    {
        return $this->repositories->index($request?->search);
    }

    public function store(StoreRequest $request)
    {
        $groupdata = $request->validatedData();
        if ($request->file('profile_picture')) {
            $groupdata['profile_picture'] = $this->fileUploadStorage($request->file('profile_picture'), 'user');
        }
        return $this->repositories->store($groupdata);
    }

    public function update(UpdateRequest $request,$uuid)
    {
        $filenames = User::where('uuid', $request->uuid)->pluck('profile_picture');
        $groupdata = $request->validated();

        if ($request->hasFile('profile_picture') && $filenames->isNotEmpty()) {
            $this->fileDeleteStorage($filenames);
            $groupdata['profile_picture'] = $this->fileUploadStorage($request->file('profile_picture'), 'user');
        }

        return $this->repositories->update($groupdata, $uuid);
    }
}

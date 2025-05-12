<?php

namespace App\Services\User;

use App\Http\Requests\User\ShowRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Requests\User\IndexRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\Base;
use App\Http\Requests\User\ListRequest;

class UserService extends Base
{
    public function __construct(public UserRepository $repositories) {}

    public function index(IndexRequest $request)
    {
        return $this->repositories->index($request->validationData());
    }

    public function store(StoreRequest $request)
    {
        $groupdata = $request->validatedData();
        if ($request->file('profile_picture')) {
            $groupdata['profile_picture'] = $this->fileUploadStorage($request->file('profile_picture'), 'user');
        }
        return $this->repositories->store($groupdata);
    }

    public function update(UpdateRequest $request, $uuid)
    {
        $filenames = User::where('uuid', $request->uuid)->pluck('profile_picture');
        $filepath = $filenames[0];
        $groupdata = $request->validated();

        if ($request->hasFile('profile_picture') && $filenames->isNotEmpty()) {
            $this->fileDeleteStorage($filepath);
            $groupdata['profile_picture'] = $this->fileUploadStorage($request->file('profile_picture'), 'user');
        }

        return $this->repositories->update($groupdata, $uuid);
    }

    public function show(ShowRequest $request)
    {
        return $this->repositories->show($request->uuid, $request->page);
    }

    public function list(ListRequest $request)
    {
        return $this->repositories->list($request->search);
    }
}

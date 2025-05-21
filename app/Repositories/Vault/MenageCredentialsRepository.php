<?php

namespace App\Repositories\Vault;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class MenageCredentialsRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new User;
    }

    public function index($page = 1)
    {

        //     ->whereHas('passwords', function ($query) {
        //         $query->orderBy('user_passwords.created_at', 'desc');
        //     })

        $credentials = $this->model
            ->whereHas('passwords')
            ->orderByLastPassword()
            ->with(['passwords:uuid,title'])
            ->paginate(30, ['*'], 'page', $page);


        return [
            'current_page' => $credentials->currentPage(),
            'data' => $credentials->items(),
            'from' => $credentials->firstItem(),
            'last_page' => $credentials->lastPage(),
            'per_page' => $credentials->perPage(),
            'to' => $credentials->lastItem(),
            'total' => $credentials->total(),
        ];
    }

    public function store($request): User
    {
        $this->getByUuid($request->user_id)->passwords()->attach($request->credentials);

        return $this->show($request->user_id);
    }

    public function update($request): User
    {
        // https://stackoverflow.com/questions/34405138/laravel-5-2-pluck-method-returns-array
        //     $ids = collect($request->credentials)
        //         ->pluck('password_id')  // sadəcə ID-ləri al
        //         ->unique()              // təkrarlananları çıxar
        //         ->toArray();            // array formatına çevir
        //     $this->getByUuid($request->user_id)->passwords()->sync($ids);

        // $this->getByUuid($request->user_id)->passwords()->sync($request->credentials);
        $this->getByUuid($request->user_id)
            ->passwords()
            ->sync(array_unique(data_get($request->credentials, '*.password_id')));

        return $this->show($request->user_id);
    }

    public function show(string $uuid): User
    {
        return $this->model->where('uuid', $uuid)->with(['passwords:uuid,title'])->first();
    }

    public function destroy(string $uuid)
    {
        $this->getByUuid($uuid)->passwords()->detach();

        return true;
    }

    public function getByUuid(string $uuid): User
    {
        return $this->model->where('uuid', $uuid)->first();
    }
}

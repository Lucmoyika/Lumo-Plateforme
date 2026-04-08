<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []): Collection;

    public function find(int $id, array $columns = ['*'], array $relations = []): ?Model;

    public function findOrFail(int $id): Model;

    public function create(array $data): Model;

    public function update(int $id, array $data): Model;

    public function delete(int $id): bool;

    public function paginate(int $perPage = 15, array $columns = ['*'], array $relations = []): LengthAwarePaginator;
}

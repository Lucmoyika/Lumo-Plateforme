<?php

namespace App\Modules\Ecommerce\Repositories;

use App\Modules\Ecommerce\Models\Order;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository
{
    public function __construct(Order $model) { parent::__construct($model); }

    public function getByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)->with(['items.product'])->latest()->paginate($perPage);
    }

    public function getByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('status', $status)->with(['user','items'])->latest()->paginate($perPage);
    }
}

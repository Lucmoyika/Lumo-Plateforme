<?php

namespace App\Modules\Logistique\Repositories;

use App\Modules\Logistique\Models\Shipment;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class ShipmentRepository extends BaseRepository
{
    public function __construct(Shipment $model) { parent::__construct($model); }

    public function getByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)->latest()->paginate($perPage);
    }

    public function findByTrackingCode(string $code): ?Shipment
    {
        return $this->model->where('tracking_code', $code)->first();
    }
}

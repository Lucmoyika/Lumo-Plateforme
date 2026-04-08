<?php

namespace App\Modules\Paiement\Repositories;

use App\Modules\Paiement\Models\Payment;
use App\Repositories\BaseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class PaymentRepository extends BaseRepository
{
    public function __construct(Payment $model) { parent::__construct($model); }

    public function getByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->where('user_id', $userId)->latest()->paginate($perPage);
    }

    public function findByTransactionId(string $transactionId): ?Payment
    {
        return $this->model->where('transaction_id', $transactionId)->first();
    }
}

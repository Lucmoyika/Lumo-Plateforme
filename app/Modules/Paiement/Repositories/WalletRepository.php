<?php

namespace App\Modules\Paiement\Repositories;

use App\Modules\Paiement\Models\Wallet;
use App\Modules\Paiement\Models\WalletTransaction;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class WalletRepository extends BaseRepository
{
    public function __construct(Wallet $model) { parent::__construct($model); }

    public function getByUser(int $userId): ?Wallet
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function getOrCreate(int $userId): Wallet
    {
        return $this->model->firstOrCreate(['user_id' => $userId], ['balance' => 0, 'currency' => 'XOF', 'status' => 'active']);
    }

    public function getTransactions(int $walletId, int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        return WalletTransaction::where('wallet_id', $walletId)->latest()->paginate($perPage);
    }
}

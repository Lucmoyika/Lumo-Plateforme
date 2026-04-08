<?php

namespace App\Modules\Paiement\Services;

use App\Modules\Paiement\Models\Wallet;
use App\Modules\Paiement\Models\WalletTransaction;
use App\Modules\Paiement\Repositories\WalletRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class WalletService extends BaseService
{
    public function __construct(protected WalletRepository $walletRepository) { parent::__construct($walletRepository); }

    public function getWallet(int $userId): Wallet
    {
        return $this->walletRepository->getOrCreate($userId);
    }

    public function topUp(int $userId, float $amount, string $description = 'Top-up'): Wallet
    {
        return DB::transaction(function () use ($userId, $amount, $description) {
            $wallet = $this->walletRepository->getOrCreate($userId);
            $wallet->increment('balance', $amount);
            WalletTransaction::create(['wallet_id' => $wallet->id, 'type' => 'credit', 'amount' => $amount, 'description' => $description, 'balance_after' => $wallet->fresh()->balance]);
            return $wallet->fresh();
        });
    }

    public function transfer(int $fromUserId, int $toUserId, float $amount): array
    {
        return DB::transaction(function () use ($fromUserId, $toUserId, $amount) {
            $from = $this->walletRepository->getOrCreate($fromUserId);
            if ($from->balance < $amount) throw new \InvalidArgumentException('Solde insuffisant.');

            $to = $this->walletRepository->getOrCreate($toUserId);
            $from->decrement('balance', $amount);
            $to->increment('balance', $amount);

            WalletTransaction::create(['wallet_id' => $from->id, 'type' => 'debit', 'amount' => $amount, 'description' => "Transfert vers utilisateur #{$toUserId}", 'balance_after' => $from->fresh()->balance]);
            WalletTransaction::create(['wallet_id' => $to->id, 'type' => 'credit', 'amount' => $amount, 'description' => "Reçu de utilisateur #{$fromUserId}", 'balance_after' => $to->fresh()->balance]);

            return ['from' => $from->fresh(), 'to' => $to->fresh()];
        });
    }

    public function getTransactions(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        $wallet = $this->walletRepository->getOrCreate($userId);
        return $this->walletRepository->getTransactions($wallet->id, $perPage);
    }
}

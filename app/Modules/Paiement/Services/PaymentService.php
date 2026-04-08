<?php

namespace App\Modules\Paiement\Services;

use App\Modules\Paiement\Models\Payment;
use App\Modules\Paiement\Repositories\PaymentRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PaymentService extends BaseService
{
    public function __construct(protected PaymentRepository $paymentRepository) { parent::__construct($paymentRepository); }

    public function initiate(int $userId, array $data): Payment
    {
        return $this->paymentRepository->create([
            'user_id'        => $userId,
            'method'         => $data['method'],
            'amount'         => $data['amount'],
            'currency'       => $data['currency'] ?? 'XOF',
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
            'status'         => 'pending',
            'payable_type'   => $data['payable_type'] ?? null,
            'payable_id'     => $data['payable_id'] ?? null,
        ]);
    }

    public function verify(string $transactionId): Payment
    {
        $payment = $this->paymentRepository->findByTransactionId($transactionId);
        if (!$payment) throw new \InvalidArgumentException('Transaction introuvable.');
        // In production, call gateway API here
        $this->paymentRepository->update($payment->id, ['status' => 'completed']);
        return $payment->fresh();
    }

    public function refund(int $paymentId): Payment
    {
        $payment = $this->paymentRepository->findOrFail($paymentId);
        if ($payment->status !== 'completed') throw new \InvalidArgumentException('Seuls les paiements complétés peuvent être remboursés.');
        return $this->paymentRepository->update($paymentId, ['status' => 'refunded']);
    }

    public function getHistory(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->paymentRepository->getByUser($userId, $perPage);
    }
}

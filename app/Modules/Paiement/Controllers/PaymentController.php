<?php

namespace App\Modules\Paiement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Paiement\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function initiate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'method'       => ['required','string','in:mobile_money,card,wallet'],
            'amount'       => ['required','numeric','min:1'],
            'currency'     => ['nullable','string','max:3'],
            'payable_type' => ['nullable','string'],
            'payable_id'   => ['nullable','integer'],
        ]);
        $payment = $this->paymentService->initiate($request->user()->id, $data);
        return $this->successResponse($payment, 'Paiement initié.', 201);
    }

    public function verify(Request $request): JsonResponse
    {
        $request->validate(['transaction_id' => ['required','string']]);
        try {
            $payment = $this->paymentService->verify($request->get('transaction_id'));
            return $this->successResponse($payment, 'Paiement vérifié.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), [], 404);
        }
    }

    public function webhook(Request $request): JsonResponse
    {
        // Process gateway webhook - verify signature in production
        $transactionId = $request->get('transaction_id') ?? $request->get('ref');
        if ($transactionId) {
            try { $this->paymentService->verify($transactionId); } catch (\Throwable) {}
        }
        return $this->successResponse(null, 'Webhook traité.');
    }

    public function getHistory(Request $request): JsonResponse
    {
        $paginator = $this->paymentService->getHistory($request->user()->id, (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Historique des paiements.');
    }

    public function refund(Request $request, int $id): JsonResponse
    {
        try {
            $payment = $this->paymentService->refund($id);
            return $this->successResponse($payment, 'Remboursement effectué.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
    }
}

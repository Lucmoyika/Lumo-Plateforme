<?php

namespace App\Modules\Paiement\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Paiement\Services\WalletService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function __construct(private readonly WalletService $walletService) {}

    public function getWallet(Request $request): JsonResponse
    {
        $wallet = $this->walletService->getWallet($request->user()->id);
        return $this->successResponse($wallet, 'Portefeuille récupéré.');
    }

    public function topUp(Request $request): JsonResponse
    {
        $data = $request->validate(['amount' => ['required','numeric','min:1'], 'description' => ['nullable','string']]);
        $wallet = $this->walletService->topUp($request->user()->id, $data['amount'], $data['description'] ?? 'Recharge');
        return $this->successResponse($wallet, 'Portefeuille rechargé.');
    }

    public function transfer(Request $request): JsonResponse
    {
        $data = $request->validate(['to_user_id' => ['required','exists:users,id'], 'amount' => ['required','numeric','min:1']]);
        try {
            $result = $this->walletService->transfer($request->user()->id, $data['to_user_id'], $data['amount']);
            return $this->successResponse($result, 'Transfert effectué.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
    }

    public function getTransactions(Request $request): JsonResponse
    {
        $paginator = $this->walletService->getTransactions($request->user()->id, (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Transactions récupérées.');
    }
}

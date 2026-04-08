<?php

namespace App\Modules\Ecommerce\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Ecommerce\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService) {}

    public function placeOrder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'items'                => ['required','array','min:1'],
            'items.*.product_id'   => ['required','exists:products,id'],
            'items.*.quantity'     => ['required','integer','min:1'],
            'shipping_address'     => ['nullable','string'],
            'notes'                => ['nullable','string'],
        ]);
        try {
            $order = $this->orderService->placeOrder($request->user()->id, $data);
            return $this->successResponse($order, 'Commande passée.', 201);
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), [], 422);
        }
    }

    public function getMyOrders(Request $request): JsonResponse
    {
        $paginator = $this->orderService->getMyOrders($request->user()->id, (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Mes commandes.');
    }

    public function getOrder(int $id): JsonResponse
    {
        $order = $this->orderService->getById($id, ['items.product','user']);
        if (!$order) return $this->errorResponse('Commande introuvable.', [], 404);
        return $this->successResponse($order, 'Commande récupérée.');
    }

    public function cancelOrder(Request $request, int $id): JsonResponse
    {
        try {
            $order = $this->orderService->cancelOrder($id, $request->user()->id);
            return $this->successResponse($order, 'Commande annulée.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), [], 400);
        }
    }

    public function getByStatus(Request $request): JsonResponse
    {
        $request->validate(['status' => ['required','string','in:pending,processing,shipped,delivered,cancelled']]);
        $paginator = $this->orderService->getByStatus($request->get('status'), (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Commandes récupérées.');
    }
}

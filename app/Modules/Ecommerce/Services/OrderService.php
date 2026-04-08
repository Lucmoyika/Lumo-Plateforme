<?php

namespace App\Modules\Ecommerce\Services;

use App\Modules\Ecommerce\Models\Order;
use App\Modules\Ecommerce\Models\Product;
use App\Modules\Ecommerce\Repositories\OrderRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService extends BaseService
{
    public function __construct(protected OrderRepository $orderRepository) { parent::__construct($orderRepository); }

    public function placeOrder(int $userId, array $data): Order
    {
        return DB::transaction(function () use ($userId, $data) {
            $total = 0;
            $items = [];

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \InvalidArgumentException("Stock insuffisant pour : {$product->name}");
                }

                $product->decrement('stock', $item['quantity']);
                $subtotal = $product->price * $item['quantity'];
                $total   += $subtotal;

                $items[] = [
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal'   => $subtotal,
                ];
            }

            $order = $this->orderRepository->create([
                'user_id'          => $userId,
                'order_number'     => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount'     => $total,
                'status'           => 'pending',
                'shipping_address' => $data['shipping_address'] ?? null,
                'notes'            => $data['notes'] ?? null,
            ]);

            $order->items()->createMany($items);

            return $order->load('items.product');
        });
    }

    public function getMyOrders(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->getByUser($userId, $perPage);
    }

    public function cancelOrder(int $orderId, int $userId): Order
    {
        $order = $this->orderRepository->findOrFail($orderId);

        if ($order->user_id !== $userId) throw new \InvalidArgumentException('Non autorisé.');
        if (!in_array($order->status, ['pending','processing'])) throw new \InvalidArgumentException('Commande non annulable.');

        // Restore stock
        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)->increment('stock', $item->quantity);
        }

        return $this->orderRepository->update($orderId, ['status' => 'cancelled']);
    }

    public function getByStatus(string $status, int $perPage = 15): LengthAwarePaginator
    {
        return $this->orderRepository->getByStatus($status, $perPage);
    }
}

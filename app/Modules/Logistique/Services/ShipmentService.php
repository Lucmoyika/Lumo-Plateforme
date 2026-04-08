<?php

namespace App\Modules\Logistique\Services;

use App\Modules\Logistique\Models\Shipment;
use App\Modules\Logistique\Repositories\ShipmentRepository;
use App\Services\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ShipmentService extends BaseService
{
    public function __construct(protected ShipmentRepository $shipmentRepository) { parent::__construct($shipmentRepository); }

    public function createShipment(int $userId, array $data): Shipment
    {
        return $this->shipmentRepository->create(array_merge($data, [
            'user_id'       => $userId,
            'tracking_code' => 'SHP-' . strtoupper(Str::random(10)),
            'status'        => 'pending',
        ]));
    }

    public function updateStatus(int $id, string $status, ?string $note = null): Shipment
    {
        $data = ['status' => $status];
        if ($note) $data['status_note'] = $note;
        return $this->shipmentRepository->update($id, $data);
    }

    public function track(string $trackingCode): ?Shipment
    {
        return $this->shipmentRepository->findByTrackingCode($trackingCode);
    }

    public function getHistory(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->shipmentRepository->getByUser($userId, $perPage);
    }
}

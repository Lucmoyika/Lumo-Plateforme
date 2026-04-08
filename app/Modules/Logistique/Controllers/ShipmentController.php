<?php

namespace App\Modules\Logistique\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Logistique\Services\ShipmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function __construct(private readonly ShipmentService $shipmentService) {}

    public function createShipment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'order_id'         => ['nullable','exists:orders,id'],
            'recipient_name'   => ['required','string','max:100'],
            'recipient_phone'  => ['required','string','max:20'],
            'recipient_address'=> ['required','string'],
            'origin'           => ['nullable','string'],
            'destination'      => ['required','string'],
            'weight'           => ['nullable','numeric','min:0'],
            'notes'            => ['nullable','string'],
        ]);
        $shipment = $this->shipmentService->createShipment($request->user()->id, $data);
        return $this->successResponse($shipment, 'Expédition créée.', 201);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required','string','in:pending,picked_up,in_transit,out_for_delivery,delivered,failed,returned'],
            'note'   => ['nullable','string'],
        ]);
        $shipment = $this->shipmentService->updateStatus($id, $data['status'], $data['note'] ?? null);
        return $this->successResponse($shipment, 'Statut mis à jour.');
    }

    public function track(Request $request): JsonResponse
    {
        $request->validate(['tracking_code' => ['required','string']]);
        $shipment = $this->shipmentService->track($request->get('tracking_code'));
        if (!$shipment) return $this->errorResponse('Expédition introuvable.', [], 404);
        return $this->successResponse($shipment, 'Expédition trouvée.');
    }

    public function getHistory(Request $request): JsonResponse
    {
        $paginator = $this->shipmentService->getHistory($request->user()->id, (int)$request->get('per_page',15));
        return $this->paginatedResponse($paginator, 'Historique récupéré.');
    }
}

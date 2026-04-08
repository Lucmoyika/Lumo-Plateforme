<?php

namespace App\Modules\Logistique\Models;

use App\Modules\Ecommerce\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'tracking_number',
        'carrier',
        'status',
        'origin',
        'destination',
        'estimated_delivery',
        'actual_delivery',
        'lat',
        'lng',
    ];

    protected function casts(): array
    {
        return [
            'origin'             => 'array',
            'destination'        => 'array',
            'estimated_delivery' => 'datetime',
            'actual_delivery'    => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

<?php

namespace App\Modules\Paiement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'method',
        'provider',
        'transaction_id',
        'amount',
        'currency',
        'status',
        'gateway_response',
        'processed_at',
        'payable_id',
        'payable_type',
    ];

    protected function casts(): array
    {
        return [
            'gateway_response' => 'array',
            'processed_at'     => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

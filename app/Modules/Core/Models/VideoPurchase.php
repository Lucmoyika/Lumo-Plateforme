<?php

namespace App\Modules\Core\Models;

use App\Models\User;
use App\Modules\Paiement\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'video_id',
        'payment_id',
        'purchased_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'purchased_at' => 'datetime',
            'expires_at'   => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}

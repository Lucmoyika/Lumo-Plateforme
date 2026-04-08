<?php

namespace App\Modules\Paiement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'currency',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    // ─── Methods ─────────────────────────────────────────────────────────────

    public function credit(float $amount): WalletTransaction
    {
        $this->increment('balance', $amount);
        $this->refresh();

        return $this->transactions()->create([
            'type'          => 'credit',
            'amount'        => $amount,
            'balance_after' => $this->balance,
        ]);
    }

    public function debit(float $amount): WalletTransaction
    {
        $this->decrement('balance', $amount);
        $this->refresh();

        return $this->transactions()->create([
            'type'          => 'debit',
            'amount'        => $amount,
            'balance_after' => $this->balance,
        ]);
    }

    public function canDebit(float $amount): bool
    {
        return $this->balance >= $amount && $this->status === 'active';
    }
}

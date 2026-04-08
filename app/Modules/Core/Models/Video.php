<?php

namespace App\Modules\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model
{
    protected $fillable = [
        'module',
        'sub_module',
        'title',
        'description',
        'youtube_url',
        'thumbnail',
        'is_premium',
        'price',
        'currency',
        'duration',
        'status',
        'views',
    ];

    protected function casts(): array
    {
        return [
            'is_premium' => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function purchases(): HasMany
    {
        return $this->hasMany(VideoPurchase::class);
    }

    // ─── Methods ─────────────────────────────────────────────────────────────

    public function isAccessibleBy(User $user): bool
    {
        if (! $this->is_premium) {
            return true;
        }

        if ($user->is_premium) {
            return true;
        }

        return $this->purchases()
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->exists();
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}

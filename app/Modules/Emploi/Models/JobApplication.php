<?php

namespace App\Modules\Emploi\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'offer_id',
        'user_id',
        'cover_letter',
        'cv_path',
        'status',
        'notes',
        'applied_at',
    ];

    protected function casts(): array
    {
        return [
            'applied_at' => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function offer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class, 'offer_id');
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

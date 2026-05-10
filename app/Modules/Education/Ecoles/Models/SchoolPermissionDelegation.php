<?php

namespace App\Modules\Education\Ecoles\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class SchoolPermissionDelegation extends Model
{
    protected $fillable = [
        'school_id',
        'user_id',
        'granted_by',
        'role_name',
        'permissions',
        'starts_at',
        'ends_at',
        'notes',
        'revoked_at',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grantor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereNull('revoked_at')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function scopeForSchool(Builder $query, int $schoolId): Builder
    {
        return $query->where('school_id', $schoolId);
    }
}
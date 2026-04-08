<?php

namespace App\Modules\Education\Universites\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model
{
    protected $fillable = [
        'university_id',
        'name',
        'acronym',
        'dean_id',
        'description',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function dean(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dean_id');
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }
}

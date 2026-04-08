<?php

namespace App\Modules\Education\Universites\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Program extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'code',
        'level',
        'duration_years',
        'credits_required',
        'description',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(UniversityStudent::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }
}

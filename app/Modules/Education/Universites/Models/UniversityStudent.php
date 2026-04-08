<?php

namespace App\Modules\Education\Universites\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniversityStudent extends Model
{
    protected $fillable = [
        'user_id',
        'university_id',
        'program_id',
        'student_number',
        'year_level',
        'enrollment_year',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(UniversityGrade::class, 'student_id');
    }

    public function theses(): HasMany
    {
        return $this->hasMany(Thesis::class, 'student_id');
    }
}

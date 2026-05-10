<?php

namespace App\Modules\Education\Ecoles\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'employee_number',
        'subjects',
        'qualification',
        'experience_years',
        'gender',
        'contract_type',
        'role',
        'status',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'subjects' => 'array',
            'archived_at' => 'datetime',
        ];
    }

    /**
     * Scope pour filtrer les enseignants actifs (non archivés)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    /**
     * Scope pour filtrer les enseignants principaux
     */
    public function scopeMainTeachers($query)
    {
        return $query->where('role', 'teacher')->active();
    }

    /**
     * Scope pour filtrer les assistants
     */
    public function scopeAssistants($query)
    {
        return $query->where('role', 'assistant')->active();
    }

    /**
     * Scope pour filtrer les remplaçants
     */
    public function scopeSubstitutes($query)
    {
        return $query->where('role', 'substitute')->active();
    }

    /**
     * Scope pour filtrer par genre
     */
    public function scopeByGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class, 'teacher_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }
}

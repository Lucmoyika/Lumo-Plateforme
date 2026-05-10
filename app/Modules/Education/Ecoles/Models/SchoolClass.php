<?php

namespace App\Modules\Education\Ecoles\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    protected $table = 'school_classes';

    protected $fillable = [
        'school_id',
        'name',
        'level',
        'class_variant',
        'education_submodule',
        'academic_year',
        'teacher_id',
        'max_students',
        'room',
        'status',
        'archived_at',
    ];

    protected function casts(): array
    {
        return [
            'archived_at' => 'datetime',
        ];
    }

    /**
     * Scope pour filtrer les classes actives (non archivées)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    /**
     * Scope pour filtrer par niveau
     */
    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope pour filtrer par année scolaire
     */
    public function scopeByYear($query, string $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Obtenir le nom complet de la classe (ex: "1er A", "2e B")
     */
    public function getFullNameAttribute(): string
    {
        if ($this->class_variant) {
            return "{$this->level} {$this->class_variant}";
        }
        return $this->name;
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'class_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'class_id');
    }
}

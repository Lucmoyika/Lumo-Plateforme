<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaternelleClass extends Model
{
    protected $table = 'school_classes';

    protected $fillable = [
        'school_id',
        'name',
        'level',
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

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    public function scopeByYear($query, string $year)
    {
        return $query->where('academic_year', $year);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Education\Ecoles\Models\School::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(\App\Modules\Education\Ecoles\Models\Student::class, 'class_id');
    }
}

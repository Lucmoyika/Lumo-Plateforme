<?php

namespace App\Modules\Education\Ecoles\SubModules\Primaire\Models;

use Illuminate\Database\Eloquent\Model;

class PrimaireClass extends Model
{
    protected $table = 'school_classes';

    protected $fillable = [
        'school_id',
        'name',
        'level',
        'class_variant',
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

    public function getFullNameAttribute(): string
    {
        if ($this->class_variant) {
            return "{$this->level} {$this->class_variant}";
        }
        return $this->name;
    }
}

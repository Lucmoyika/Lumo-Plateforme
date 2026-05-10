<?php

namespace App\Modules\Education\Ecoles\SubModules\Humanites\Models;

use Illuminate\Database\Eloquent\Model;

class HumanitesClass extends Model
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

    public function getFullNameAttribute(): string
    {
        if ($this->class_variant) {
            return "{$this->level} {$this->class_variant}";
        }
        return $this->name;
    }
}

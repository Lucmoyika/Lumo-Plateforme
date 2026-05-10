<?php

namespace App\Modules\Education\Ecoles\SubModules\Maternelle\Models;

use Illuminate\Database\Eloquent\Model;

class MaternelleTeacher extends Model
{
    protected $table = 'teachers';

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

    public function scopeActive($query)
    {
        return $query->whereNull('archived_at');
    }

    public function scopeMainTeachers($query)
    {
        return $query->where('role', 'teacher')->active();
    }

    public function scopeAssistants($query)
    {
        return $query->where('role', 'assistant')->active();
    }

    public function scopeOnlyFemale($query)
    {
        return $query->where('gender', 'F');
    }
}

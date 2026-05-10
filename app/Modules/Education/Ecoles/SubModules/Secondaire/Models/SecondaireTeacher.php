<?php

namespace App\Modules\Education\Ecoles\SubModules\Secondaire\Models;

use Illuminate\Database\Eloquent\Model;

class SecondaireTeacher extends Model
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
}

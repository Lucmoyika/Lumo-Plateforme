<?php

namespace App\Modules\Education\Universites\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thesis extends Model
{
    protected $fillable = [
        'student_id',
        'title',
        'abstract',
        'supervisor_id',
        'program_id',
        'status',
        'submitted_at',
        'defended_at',
        'grade',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'defended_at'  => 'datetime',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function student(): BelongsTo
    {
        return $this->belongsTo(UniversityStudent::class, 'student_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }
}

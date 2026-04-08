<?php

namespace App\Modules\Education\Ecoles\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject',
        'class_id',
        'academic_year',
        'term',
        'score',
        'max_score',
        'grade_letter',
        'teacher_id',
        'exam_type',
        'notes',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function class_(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    protected function percentage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->max_score > 0
                ? round(($this->score / $this->max_score) * 100, 2)
                : 0,
        );
    }
}

<?php

namespace App\Modules\Education\Universites\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UniversityGrade extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'academic_year',
        'semester',
        'score',
        'max_score',
        'credits_obtained',
        'status',
        'exam_session',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function student(): BelongsTo
    {
        return $this->belongsTo(UniversityStudent::class, 'student_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}

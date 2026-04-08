<?php

namespace App\Modules\Education\Ecoles\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'class_id',
        'subject',
        'teacher_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'color',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function class_(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}

<?php

namespace App\Modules\Education\Universites\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class University extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'name',
        'acronym',
        'address',
        'city',
        'province',
        'phone',
        'email',
        'website',
        'logo',
        'description',
        'rector_id',
        'type',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function rector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rector_id');
    }

    public function faculties(): HasMany
    {
        return $this->hasMany(Faculty::class);
    }

    public function departments(): HasMany
    {
        return $this->hasManyThrough(Department::class, Faculty::class);
    }

    public function programs(): HasMany
    {
        return $this->hasManyThrough(Program::class, Department::class, 'faculty_id', 'department_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(UniversityStudent::class);
    }
}

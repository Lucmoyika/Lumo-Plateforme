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

    public function departments(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            Department::class,
            Faculty::class,
            'university_id', // FK on faculties -> universities
            'faculty_id',    // FK on departments -> faculties
        );
    }

    /**
     * Returns programs belonging to all departments of this university.
     * Three-level deep: University -> Faculty -> Department -> Program.
     */
    public function programs(): \Illuminate\Database\Eloquent\Builder
    {
        return Program::whereIn(
            'department_id',
            $this->departments()->select('departments.id'),
        );
    }

    public function students(): HasMany
    {
        return $this->hasMany(UniversityStudent::class);
    }
}

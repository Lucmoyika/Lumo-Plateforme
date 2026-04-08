<?php

namespace App\Modules\Emploi\Models;

use App\Modules\Entreprises\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOffer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'requirements',
        'type',
        'location',
        'salary_min',
        'salary_max',
        'currency',
        'deadline',
        'slots',
        'status',
        'remote',
    ];

    protected function casts(): array
    {
        return [
            'remote'   => 'boolean',
            'deadline' => 'date',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'offer_id');
    }
}

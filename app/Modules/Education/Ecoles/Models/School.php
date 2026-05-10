<?php

namespace App\Modules\Education\Ecoles\Models;

use Database\Factories\SchoolFactory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class School extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected static function newFactory(): SchoolFactory
    {
        return SchoolFactory::new();
    }

    protected $fillable = [
        'name',
        'level_types',
        'education_submodule',
        'license_plan_code',
        'subscription_status',
        'billing_duration_months',
        'license_price_cdf',
        'trial_ends_at',
        'subscription_starts_at',
        'subscription_ends_at',
        'mobile_access_enabled',
        'address',
        'city',
        'province',
        'phone',
        'email',
        'website',
        'logo',
        'description',
        'director_id',
        'status',
    ];

    /**
     * Déterminer le type de sous-module basé sur les level_types
     * Utilisé pour filtrer les classes et enseignants
     */
    public function getSchoolTypeAttribute(): string
    {
        if ($this->level_types) {
            if (in_array('maternelle', $this->level_types) && !in_array('secondaire', $this->level_types) && !in_array('humanites', $this->level_types)) {
                return 'maternelle';
            }
            if (in_array('primaire', $this->level_types) && !in_array('secondaire', $this->level_types) && !in_array('humanites', $this->level_types)) {
                return 'primaire';
            }
            if (in_array('maternelle', $this->level_types) && in_array('primaire', $this->level_types)) {
                return 'mp'; // Maternelle & Primaire
            }
            if (in_array('primaire', $this->level_types) && in_array('secondaire', $this->level_types)) {
                return 'ps'; // Primaire & Secondaire
            }
            if (in_array('secondaire', $this->level_types)) {
                return 'sh'; // Secondaire & Humanités
            }
            return 'full'; // Tous les niveaux
        }
        return 'unknown';
    }

    /**
     * Filtrer les niveaux par type pour Maternelle (1er, 2e, 3e)
     */
    public function getMaternelleLevelsAttribute(): array
    {
        return ['1er', '2e', '3e'];
    }

    /**
     * Filtrer les niveaux par type pour Primaire (1-6)
     */
    public function getPrimaireLevelsAttribute(): array
    {
        return ['1er', '2e', '3e', '4e', '5e', '6e'];
    }

    protected function casts(): array
    {
        return [
            'level_types' => 'array',
            'trial_ends_at' => 'datetime',
            'subscription_starts_at' => 'datetime',
            'subscription_ends_at' => 'datetime',
            'mobile_access_enabled' => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function director(): BelongsTo
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(SchoolTask::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeByLevel(\Illuminate\Database\Eloquent\Builder $query, string $level): \Illuminate\Database\Eloquent\Builder
    {
        return $query->whereJsonContains('level_types', $level);
    }

    public function hasLevel(string $level): bool
    {
        return in_array($level, $this->level_types ?? []);
    }
}

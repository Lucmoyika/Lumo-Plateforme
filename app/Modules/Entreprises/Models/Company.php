<?php

namespace App\Modules\Entreprises\Models;

use App\Models\User;
use App\Modules\Emploi\Models\JobOffer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Company extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'legal_form',
        'nif',
        'rccm',
        'address',
        'city',
        'province',
        'phone',
        'email',
        'website',
        'logo',
        'description',
        'sector',
        'size',
        'status',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(User::class, 'company_id');
    }

    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }
}

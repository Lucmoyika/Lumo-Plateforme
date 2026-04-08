<?php

namespace App\Modules\Ecommerce\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'seller_id',
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'currency',
        'stock',
        'images',
        'status',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'images'   => 'array',
            'featured' => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    protected function currentPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->discount_price ?? $this->price,
        );
    }
}

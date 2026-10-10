<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'details',
        'price',
        'discount_price',
        'sku',
        'stock',
        'flower_type',
        'color',
        'occasion',
        'status',
        'is_featured',
        'is_best_seller',
        'avg_rating',
        'review_count',
        'sold_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount_price' => 'decimal:2',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'is_best_seller' => 'boolean',
            'avg_rating' => 'decimal:2',
        ];
    }

    // ── Relationships ───────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // ── Accessors ───────────────────────────────────────────

    public function getPrimaryImageUrlAttribute(): ?string
    {
        $primary = $this->primaryImage ?? $this->images->first();

        return $primary?->image_url;
    }

    public function getCurrentPriceAttribute(): float
    {
        return $this->discount_price && $this->discount_price > 0
            ? (float) $this->discount_price
            : (float) $this->price;
    }

    public function getDiscountPercentageAttribute(): ?int
    {
        if (! $this->discount_price || $this->discount_price <= 0 || $this->discount_price >= $this->price) {
            return null;
        }

        return (int) round((1 - $this->discount_price / $this->price) * 100);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->current_price, 0, ',', '.');
    }

    public function getFormattedOriginalPriceAttribute(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }

    // ── Scopes ──────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeBestSeller($query)
    {
        return $query->where('is_best_seller', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByOccasion($query, $occasion)
    {
        return $query->where('occasion', $occasion);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('flower_type', 'like', "%{$search}%");
        });
    }

    public function scopePriceRange($query, $min, $max)
    {
        if ($min !== null) {
            $query->where(function ($q) use ($min) {
                $q->whereNotNull('discount_price')
                    ->where('discount_price', '>=', $min)
                    ->orWhereNull('discount_price')
                    ->where('price', '>=', $min);
            });
        }

        if ($max !== null) {
            $query->where(function ($q) use ($max) {
                $q->whereNotNull('discount_price')
                    ->where('discount_price', '<=', $max)
                    ->orWhereNull('discount_price')
                    ->where('price', '<=', $max);
            });
        }

        return $query;
    }
}

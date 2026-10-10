<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'variation_id',
        'quantity',
        'unit_price',
        'custom_config',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'custom_config' => 'array',
        ];
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function getSubtotalAttribute(): float
    {
        return (float) $this->unit_price * $this->quantity;
    }

    public function getParsedConfigAttribute(): ?array
    {
        if (is_array($this->custom_config)) {
            return $this->custom_config;
        }

        if (is_string($this->custom_config)) {
            return json_decode($this->custom_config, true);
        }

        return null;
    }
}

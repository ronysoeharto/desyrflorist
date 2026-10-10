<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variation_id',
        'product_name',
        'variation_name',
        'quantity',
        'unit_price',
        'subtotal',
        'custom_config',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'custom_config' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'variation_id');
    }

    public function getNameAttribute(): string
    {
        return $this->product_name.($this->variation_name ? ' ('.$this->variation_name.')' : '');
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

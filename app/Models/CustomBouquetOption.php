<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomBouquetOption extends Model
{
    protected $fillable = [
        'type',
        'name',
        'price',
        'image',
        'status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'status' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type)->orderBy('sort_order');
    }
}

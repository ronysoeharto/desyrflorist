<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = [
        'name',
        'areas',
        'delivery_fee',
        'min_order',
        'min_distance_km',
        'max_distance_km',
        'available_slots',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'areas' => 'array',
            'delivery_fee' => 'decimal:2',
            'min_order' => 'decimal:2',
            'min_distance_km' => 'decimal:2',
            'max_distance_km' => 'decimal:2',
            'available_slots' => 'array',
            'status' => 'boolean',
        ];
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Delivery extends Model
{
    protected $fillable = [
        'order_id',
        'courier_id',
        'status',
        'accepted_at',
        'picked_up_at',
        'delivered_at',
        'notes',
        'current_latitude',
        'current_longitude',
        'last_location_updated_at',
    ];

    protected function casts(): array
    {
        return [
            'accepted_at' => 'datetime',
            'picked_up_at' => 'datetime',
            'delivered_at' => 'datetime',
            'last_location_updated_at' => 'datetime',
            'current_latitude' => 'float',
            'current_longitude' => 'float',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function proofs(): HasMany
    {
        return $this->hasMany(DeliveryProof::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Kurir',
            'assigned' => 'Ditugaskan',
            'accepted' => 'Diterima',
            'picked_up' => 'Diambil',
            'on_delivery' => 'Dalam Pengiriman',
            'delivered' => 'Terkirim',
            'failed' => 'Gagal',
            default => ucfirst($this->status),
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',
        'amount',
        'status',
        'proof_image',
        'proof_uploaded_at',
        'rejection_reason',
        'admin_notes',
        'transaction_id',
        'payment_data',
        'paid_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_data' => 'array',
            'proof_uploaded_at' => 'datetime',
            'paid_at' => 'datetime',
            'expired_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isVerifying(): bool
    {
        return $this->status === 'verifying';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getProofUrlAttribute(): ?string
    {
        if (! $this->proof_image) {
            return null;
        }

        return asset('storage/'.$this->proof_image);
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->method) {
            'qris' => 'QRIS',
            'va' => 'Virtual Account',
            'bank_transfer' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            default => ucfirst($this->method),
        };
    }
}

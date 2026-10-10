<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_number',
        'tracking_number',
        'subtotal',
        'discount',
        'delivery_fee',
        'total',
        'status',
        'payment_status',
        'delivery_method',
        'delivery_date',
        'delivery_slot',
        'recipient_name',
        'recipient_phone',
        'delivery_address',
        'delivery_city',
        'delivery_district',
        'delivery_postal_code',
        'delivery_notes',
        'recipient_latitude',
        'recipient_longitude',
        'distance_km',
        'greeting_message',
        'coupon_id',
        'courier_id',
        'paid_at',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'delivery_fee' => 'decimal:2',
            'total' => 'decimal:2',
            'recipient_latitude' => 'float',
            'recipient_longitude' => 'float',
            'distance_km' => 'float',
            'delivery_date' => 'date',
            'paid_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    // ── Relationships ───────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class)->latest();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function courier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'courier_id');
    }

    public function delivery(): HasOne
    {
        return $this->hasOne(Delivery::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ── Accessors ───────────────────────────────────────────

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp '.number_format($this->total, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Pembayaran',
            'confirmed' => 'Dikonfirmasi',
            'processing' => 'Sedang Diproses',
            'ready' => 'Siap Dikirim',
            'assigned' => 'Kurir Ditugaskan',
            'picked_up' => 'Diambil Kurir',
            'on_delivery' => 'Dalam Pengiriman',
            'delivered' => 'Terkirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Menunggu Pembayaran',
            'verifying' => 'Menunggu Verifikasi Admin',
            'paid' => 'Sudah Dibayar',
            'rejected' => 'Pembayaran Ditolak',
            'failed' => 'Gagal',
            'expired' => 'Kedaluwarsa',
            'refunded' => 'Dikembalikan',
            default => ucfirst($this->payment_status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-800 border border-amber-200',
            'confirmed', 'processing' => 'bg-blue-100 text-blue-800 border border-blue-200',
            'ready', 'assigned', 'picked_up' => 'bg-purple-100 text-purple-800 border border-purple-200',
            'on_delivery' => 'bg-indigo-100 text-indigo-800 border border-indigo-200',
            'delivered', 'completed' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
            'cancelled' => 'bg-rose-100 text-rose-800 border border-rose-200',
            default => 'bg-gray-100 text-gray-700 border border-gray-200',
        };
    }

    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'bg-amber-100 text-amber-800 border border-amber-200',
            'verifying' => 'bg-sky-100 text-sky-800 border border-sky-200',
            'paid' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
            'rejected' => 'bg-rose-100 text-rose-800 border border-rose-200',
            'failed', 'expired' => 'bg-red-100 text-red-800 border border-red-200',
            'refunded' => 'bg-purple-100 text-purple-800 border border-purple-200',
            default => 'bg-gray-100 text-gray-700 border border-gray-200',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'confirmed', 'processing' => 'blue',
            'ready', 'assigned', 'picked_up' => 'indigo',
            'on_delivery' => 'purple',
            'delivered', 'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    // ── Helpers ─────────────────────────────────────────────

    public static function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $lastOrder = static::whereDate('created_at', today())
            ->orderByDesc('id')
            ->first();

        $sequence = $lastOrder
            ? (int) substr($lastOrder->order_number, -4) + 1
            : 1;

        return 'DSY-'.$date.'-'.str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public static function generateTrackingNumber(): string
    {
        $date = now()->format('Ymd');
        do {
            $code = 'DF-RESI-'.$date.'-'.strtoupper(Str::random(5));
        } while (static::where('tracking_number', $code)->exists());

        return $code;
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isVerifyingPayment(): bool
    {
        return $this->payment_status === 'verifying';
    }

    public function isPaymentRejected(): bool
    {
        return $this->payment_status === 'rejected';
    }

    public function getGoogleMapsUrlAttribute(): string
    {
        if ($this->recipient_latitude && $this->recipient_longitude) {
            return "https://www.google.com/maps/dir/?api=1&destination={$this->recipient_latitude},{$this->recipient_longitude}";
        }

        return 'https://www.google.com/maps/dir/?api=1&destination='.urlencode($this->delivery_address ?? '');
    }
}

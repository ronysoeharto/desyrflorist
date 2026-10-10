<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class StoreSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('store_settings_all');
        });

        static::deleted(function () {
            Cache::forget('store_settings_all');
        });
    }

    /**
     * Get all store settings as key-value pairs (cached).
     *
     * @return array<string, string>
     */
    public static function getAll(): array
    {
        return Cache::remember('store_settings_all', 3600, function () {
            return static::pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, $default = null): ?string
    {
        $all = static::getAll();

        return $all[$key] ?? $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Generate Google Maps URL pointing to the store location.
     */
    public static function getMapsUrl(?array $settings = null): string
    {
        $settings = $settings ?? static::getAll();
        $lat = $settings['store_latitude'] ?? null;
        $lng = $settings['store_longitude'] ?? null;

        if (! empty($lat) && ! empty($lng)) {
            return "https://www.google.com/maps?q={$lat},{$lng}";
        }

        $address = $settings['store_address'] ?? null;
        if (! empty($address)) {
            return 'https://www.google.com/maps/search/?api=1&query='.urlencode($address);
        }

        return 'https://www.google.com/maps?q=-7.2654,112.7483';
    }
}

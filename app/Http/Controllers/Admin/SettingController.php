<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryZone;
use App\Models\StoreSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Surabaya 31 Districts reference list grouped by region
    public static function surabayaDistricts(): array
    {
        return [
            'Surabaya Pusat' => ['Tegalsari', 'Genteng', 'Bubutan', 'Simokerto'],
            'Surabaya Timur' => ['Gubeng', 'Gunung Anyar', 'Sukolilo', 'Tambaksari', 'Mulyorejo', 'Rungkut', 'Tenggilis Mejoyo'],
            'Surabaya Barat' => ['Benowo', 'Pakal', 'Asemrowo', 'Sukomanunggal', 'Tandes', 'Sambikerep', 'Lakarsantri'],
            'Surabaya Utara' => ['Bulak', 'Kenjeran', 'Semampir', 'Pabean Cantian', 'Krembangan'],
            'Surabaya Selatan' => ['Wonokromo', 'Wonocolo', 'Wiyung', 'Karang Pilang', 'Jambangan', 'Gayungan', 'Dukuh Pakis', 'Sawahan'],
        ];
    }

    public function index()
    {
        return redirect()->route('admin.settings.profile');
    }

    // 1. Profil dan Lokasi Toko
    public function profile()
    {
        $settings = StoreSetting::pluck('value', 'key');

        return view('admin.settings.profile', compact('settings'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:100',
            'store_tagline' => 'nullable|string|max:200',
            'store_city' => 'required|string|max:100',
            'store_district' => 'required|string|max:100',
            'store_province' => 'required|string|max:100',
            'store_timezone' => 'required|string|max:50',
            'store_address' => 'required|string|max:500',
            'store_latitude' => 'required|numeric|between:-90,90',
            'store_longitude' => 'required|numeric|between:-180,180',
            'store_phone' => 'nullable|string|max:30',
            'store_whatsapp' => 'required|string|max:30',
            'store_email' => 'required|email|max:100',
            'store_instagram' => 'nullable|string|max:100',
            'google_maps_api_key' => 'nullable|string|max:200',
        ]);

        foreach ($validated as $key => $value) {
            StoreSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        return back()->with('success', 'Profil dan koordinat lokasi toko Desy Florist berhasil diperbarui.');
    }

    // 1b. Halaman & Konten tentang Kami
    public function about()
    {
        $settings = StoreSetting::pluck('value', 'key');

        return view('admin.settings.about', compact('settings'));
    }

    public function updateAbout(Request $request)
    {
        $validated = $request->validate([
            'about_tagline' => 'nullable|string|max:150',
            'about_title' => 'required|string|max:200',
            'about_subtitle' => 'nullable|string|max:500',
            'about_story_title' => 'nullable|string|max:200',
            'about_story_content' => 'required|string',
            'about_val1_title' => 'nullable|string|max:150',
            'about_val1_desc' => 'nullable|string|max:300',
            'about_val2_title' => 'nullable|string|max:150',
            'about_val2_desc' => 'nullable|string|max:300',
            'about_val3_title' => 'nullable|string|max:150',
            'about_val3_desc' => 'nullable|string|max:300',
            'about_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('about_image')) {
            $path = $request->file('about_image')->store('settings', 'public');
            StoreSetting::updateOrCreate(['key' => 'about_image'], ['value' => $path]);
        }

        unset($validated['about_image']);

        foreach ($validated as $key => $value) {
            StoreSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        return back()->with('success', 'Halaman & Konten Tentang Kami berhasil diperbarui.');
    }

    // 2. Zona Pengiriman & Wilayah Surabaya yang Dilayani
    public function zones()
    {
        $zones = DeliveryZone::orderBy('min_distance_km')->get();
        $districtsByRegion = self::surabayaDistricts();
        $enabledDistricts = json_decode(StoreSetting::get('enabled_districts', '[]'), true) ?: [];
        $maxDistanceKm = StoreSetting::get('max_delivery_distance_km', '25.0');
        $unservicedMessage = StoreSetting::get('unserviced_message', 'Mohon maaf, alamat tujuan Anda saat ini berada di luar area jangkauan operasional pengiriman Desy Florist.');

        return view('admin.settings.zones', compact('zones', 'districtsByRegion', 'enabledDistricts', 'maxDistanceKm', 'unservicedMessage'));
    }

    public function updateZones(Request $request)
    {
        $request->validate([
            'zones' => 'required|array',
            'zones.*.id' => 'required|exists:delivery_zones,id',
            'zones.*.name' => 'required|string|max:150',
            'zones.*.min_distance_km' => 'required|numeric|min:0',
            'zones.*.max_distance_km' => 'required|numeric|min:0',
            'zones.*.delivery_fee' => 'required|numeric|min:0',
            'zones.*.min_order' => 'required|numeric|min:0',
            'max_delivery_distance_km' => 'required|numeric|min:1',
            'unserviced_message' => 'required|string|max:500',
            'enabled_districts' => 'nullable|array',
        ]);

        // Update zones
        foreach ($request->zones as $zoneData) {
            $zone = DeliveryZone::find($zoneData['id']);
            if ($zone) {
                $zone->update([
                    'name' => $zoneData['name'],
                    'min_distance_km' => $zoneData['min_distance_km'],
                    'max_distance_km' => $zoneData['max_distance_km'],
                    'delivery_fee' => $zoneData['delivery_fee'],
                    'min_order' => $zoneData['min_order'],
                    'status' => isset($zoneData['status']),
                ]);
            }
        }

        // Update active Surabaya districts
        $enabledDistricts = $request->input('enabled_districts', []);
        StoreSetting::updateOrCreate(['key' => 'enabled_districts'], ['value' => json_encode(array_values($enabledDistricts))]);
        StoreSetting::updateOrCreate(['key' => 'max_delivery_distance_km'], ['value' => $request->input('max_delivery_distance_km')]);
        StoreSetting::updateOrCreate(['key' => 'unserviced_message'], ['value' => $request->input('unserviced_message')]);

        return back()->with('success', 'Konfigurasi zona pengiriman dan kecamatan Surabaya berhasil disimpan.');
    }

    // 3. Tarif Ongkir
    public function rates()
    {
        $zones = DeliveryZone::orderBy('min_distance_km')->get();
        $freeShippingMin = StoreSetting::get('free_shipping_min', '0');
        $extraFeePerKm = StoreSetting::get('extra_fee_per_km', '3000');

        return view('admin.settings.rates', compact('zones', 'freeShippingMin', 'extraFeePerKm'));
    }

    public function updateRates(Request $request)
    {
        $request->validate([
            'free_shipping_min' => 'required|numeric|min:0',
            'extra_fee_per_km' => 'required|numeric|min:0',
            'zone_fees' => 'nullable|array',
            'zone_fees.*' => 'numeric|min:0',
        ]);

        StoreSetting::updateOrCreate(['key' => 'free_shipping_min'], ['value' => $request->free_shipping_min]);
        StoreSetting::updateOrCreate(['key' => 'extra_fee_per_km'], ['value' => $request->extra_fee_per_km]);

        if ($request->has('zone_fees')) {
            foreach ($request->zone_fees as $zoneId => $fee) {
                DeliveryZone::where('id', $zoneId)->update(['delivery_fee' => $fee]);
            }
        }

        return back()->with('success', 'Tarif ongkos kirim dan kebijakan gratis ongkir berhasil diperbarui.');
    }

    // 4. Jam Operasional
    public function hours()
    {
        $settings = StoreSetting::pluck('value', 'key');

        return view('admin.settings.hours', compact('settings'));
    }

    public function updateHours(Request $request)
    {
        $validated = $request->validate([
            'operating_days' => 'required|string|max:100',
            'operating_hours' => 'required|string|max:100',
            'same_day_cutoff' => 'required|string|max:20',
            'store_is_open' => 'nullable|boolean',
            'preparation_lead_time_hours' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated as $key => $value) {
            StoreSetting::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        }

        // Keep composite store_open_hours in sync for frontend views
        StoreSetting::updateOrCreate(
            ['key' => 'store_open_hours'],
            ['value' => $validated['operating_days'].': '.$validated['operating_hours']]
        );

        return back()->with('success', 'Jam operasional dan aturan order Same Day berhasil disimpan.');
    }

    // 5. Jadwal dan Slot Pengiriman
    public function slots()
    {
        $slotsJson = StoreSetting::get('delivery_slots', '[]');
        $slots = json_decode($slotsJson, true) ?: [];

        return view('admin.settings.slots', compact('slots'));
    }

    public function updateSlots(Request $request)
    {
        $request->validate([
            'slots' => 'required|array',
            'slots.*.name' => 'required|string|max:100',
            'slots.*.time' => 'required|string|max:100',
            'slots.*.quota' => 'required|integer|min:1',
        ]);

        $formattedSlots = [];
        foreach ($request->slots as $index => $slot) {
            $formattedSlots[] = [
                'id' => 'slot-'.($index + 1),
                'name' => $slot['name'],
                'time' => $slot['time'],
                'quota' => (int) $slot['quota'],
                'is_active' => isset($slot['is_active']),
            ];
        }

        StoreSetting::updateOrCreate(
            ['key' => 'delivery_slots'],
            ['value' => json_encode($formattedSlots)]
        );

        return back()->with('success', 'Daftar jadwal dan slot waktu pengiriman berhasil diperbarui.');
    }
}

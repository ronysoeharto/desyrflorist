<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('recipient_latitude', 10, 7)->nullable()->after('delivery_notes');
            $table->decimal('recipient_longitude', 10, 7)->nullable()->after('recipient_latitude');
            $table->decimal('distance_km', 8, 2)->nullable()->after('recipient_longitude');
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->decimal('current_latitude', 10, 7)->nullable()->after('notes');
            $table->decimal('current_longitude', 10, 7)->nullable()->after('current_latitude');
            $table->timestamp('last_location_updated_at')->nullable()->after('current_longitude');
        });

        Schema::table('delivery_zones', function (Blueprint $table) {
            $table->decimal('min_distance_km', 8, 2)->default(0)->after('delivery_fee');
            $table->decimal('max_distance_km', 8, 2)->default(50)->after('min_distance_km');
        });
    }

    public function down(): void
    {
        Schema::table('delivery_zones', function (Blueprint $table) {
            $table->dropColumn(['min_distance_km', 'max_distance_km']);
        });

        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['current_latitude', 'current_longitude', 'last_location_updated_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['recipient_latitude', 'recipient_longitude', 'distance_km']);
        });
    }
};

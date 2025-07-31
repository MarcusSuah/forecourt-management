<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meter_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained('service_stations')->onDelete('cascade');
            $table->date('date');
            $table->foreignId('pump_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('unit_price_id')->constrained('unit_prices')->onDelete('cascade');

            $table->decimal('opening_meter', 12,0)->default(0);
            $table->decimal('closing_meter', 12, 0)->default(0);
            $table->decimal('volume', 12, 2); // = closing - opening
            $table->decimal('rtt', 12, 2)->default(0);

            $table->decimal('unit_price_at_sale', 10, 4); // store snapshot
            $table->decimal('sales_in_gallon', 12, 2); // = volume - rtt
            $table->decimal('sales_turnover', 12, 2); // = sales_in_gallon * unit_price_at_sale

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_collections');
    }
};


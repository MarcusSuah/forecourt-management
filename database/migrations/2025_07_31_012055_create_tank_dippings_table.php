<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tank_dippings', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('station_id')->constrained('service_stations')->onDelete('cascade');
            $table->foreignId('shift_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('tank_id')->constrained('tanks')->onDelete('cascade');
            $table->integer('opening_dips');
            $table->integer('qty_rec')->default(0);
            $table->integer('rtt')->default(0);
            $table->integer('closing_dips');
            $table->integer('tank_sales')->default(0);
            $table->foreignId('meter_collection_id')->nullable()->constrained('meter_collections')->onDelete('cascade');
             $table->foreignId('pump_sales_id')->constrained('meter_collections')->onDelete('cascade');
            $table->integer('pump_sales')->default(0)->nullable();
            $table->integer('variance');
            $table->integer('capacity');
            $table->integer('aval_ullage');
            $table->decimal('sales_percentage', 8, 2);
            $table->decimal('threshold', 10, 2)->comment('Volume threshold for alerts');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['date', 'station_id']);
            $table->index(['station_id', 'tank_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tank_dippings');
    }
};

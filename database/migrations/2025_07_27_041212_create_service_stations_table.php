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
        Schema::create('service_stations', function (Blueprint $table) {
            $table->id();
            $table->string('territory_manager')->nullable();
            $table->foreignId('dealer_id')->constrained()->onDelete('cascade');
            $table->string('logo')->nullable();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('location');
            $table->string('sap_number')->unique();
            $table->time('opening_time');
            $table->time('closing_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_stations');
    }
};

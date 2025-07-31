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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_id')->unique();
            $table->foreignId('station_id')->constrained('service_stations')->onDelete('cascade')->unique();
            $table->string('account_name')->unique();
            $table->string('account_number_usd')->unique();
            $table->string('account_number_local')->unique();
            $table->string('bank_name');
            $table->string('branch');
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }

};





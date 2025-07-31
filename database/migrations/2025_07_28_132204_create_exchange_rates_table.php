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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('base_currency_id')->constrained('currencies')->onDelete('cascade');
            $table->foreignId('target_currency_id')->constrained('currencies')->onDelete('cascade');

            $table->decimal('rate', 10, 2);
            $table->date('date');
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->unique(['base_currency_id', 'target_currency_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};

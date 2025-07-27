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
        Schema::table('dealers', function (Blueprint $table) {
            $table
                ->enum('status', ['Pending', 'Active', 'Suspended', 'Terminated'])
                ->default('Pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dealers', function (Blueprint $table) {
            Schema::dropIfExists('status');
        });
    }
};

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
        Schema::table('amortizacions', function (Blueprint $table) {
            $table->date('fecha_compromiso')->nullable();
            $table->boolean('compromiso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amortizacions', function (Blueprint $table) {
            $table->dropColumn("fecha_compromiso");
            $table->dropColumn("compromiso");
        });
    }
};

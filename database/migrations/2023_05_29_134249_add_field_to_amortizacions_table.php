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
            $table->text('fecha_comprobante')->nullable();
            $table->float('restante')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amortizacions', function (Blueprint $table) {
            $table->dropColumn("fecha_comprobante");
            $table->dropColumn("restante");
        });
    }
};

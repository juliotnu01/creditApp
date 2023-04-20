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
            $table->text('ruta_recibo_de_pago')->nullable();
            $table->text('factura_de_recibo_de_pago')->nullable();
            $table->tinyInteger('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amortizacions', function (Blueprint $table) {
            $table->dropColumn('ruta_recibo_de_pago');
            $table->dropColumn('factura_de_recibo_de_pago');
            $table->dropColumn('status');
        });
    }
};

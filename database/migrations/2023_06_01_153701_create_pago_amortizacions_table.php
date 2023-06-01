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
        Schema::create('pago_amortizacions', function (Blueprint $table) {
            $table->id();
            $table->float('pago_cuota_amortizacion')->nullable();
            $table->float('restante_cuota_amortizacion')->nullable();
            $table->text('comprobante')->nullable();
            $table->foreignId('amortizacion_id')->nullable()->constrained('amortizacions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_amortizacions');
    }
};

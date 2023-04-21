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
        Schema::table('credit_requests', function (Blueprint $table) {
            $table->string('tipo_de_pago')->nullable();
            $table->integer('pagos_mensuales')->nullable();
            $table->integer('numeros_de_periodos')->nullable();
            $table->integer('meses')->nullable();
            $table->float('interes_mensual')->nullable();
            $table->float('pago_total')->nullable();
            $table->float('pago_ola')->nullable();
            $table->float('cuota')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_requests', function (Blueprint $table) {
            $table->dropColumn('tipo_de_pago');
            $table->dropColumn('pagos_mensuales');
            $table->dropColumn('numeros_de_periodos');
            $table->dropColumn('meses');
            $table->dropColumn('interes_mensual');
            $table->dropColumn('pago_total');
            $table->dropColumn('pago_ola');
            $table->dropColumn('cuota');
        });
    }
};

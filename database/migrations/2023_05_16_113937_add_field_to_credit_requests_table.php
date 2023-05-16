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
            $table->float('monto_de_dinero_solicitado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_requests', function (Blueprint $table) {
            $table->dropColumn('monto_de_dinero_solicitado');
        });
    }
};

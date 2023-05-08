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
        Schema::create('documentos_de_banco_de_datos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco_de_datos_id')->nullable()->constrained('banco_de_datos');
            $table->text('documento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos_de_banco_de_datos');
    }
};

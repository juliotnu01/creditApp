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
        Schema::create('compromisos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_compromiso')->nullable();
            $table->text('comentario')->nullable();
            $table->boolean('compromiso')->nullable();
            $table->foreignId('amortizacion_id')->nullable()->constrained('amortizacions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compromisos');
    }
};

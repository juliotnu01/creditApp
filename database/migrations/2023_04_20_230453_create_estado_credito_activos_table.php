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
        Schema::create('estado_credito_activos', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('estado_de_evaluacion')->default(0);
            $table->tinyInteger('estado_propuesta')->default(0);
            $table->tinyInteger('estado_carta_de_aceptacion')->default(0);
            $table->tinyInteger('estado_en_contrato')->default(0);
            $table->tinyInteger('estado_liberado')->default(0);
            $table->tinyInteger('estado_pagado')->default(0);
            $table->foreignId('credit_request_id')->nullable()->constrained('credit_requests');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_credito_activos');
    }
};

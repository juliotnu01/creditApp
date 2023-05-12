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
        Schema::create('status_documentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('documentos_de_banco_de_datos_id')->nullable()->constrained('documentos_de_banco_de_datos');
            $table->boolean('status')->nullable();
            $table->text('comentario')->nullable();
            $table->text('nombre_documento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_documentos');
    }
};

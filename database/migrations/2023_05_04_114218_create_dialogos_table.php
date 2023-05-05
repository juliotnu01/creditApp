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
        Schema::create('dialogos', function (Blueprint $table) {
            $table->id();
            $table->text('dialogo_emisor')->nullable();
            $table->foreignId('dialogo_emisor_id')->nullable()->constrained('users');
            $table->text('link_adjunto_emisor')->nullable();

            $table->foreignId('dialogo_receptor_id')->nullable()->constrained('users');
            $table->text('dialogo_receptor')->nullable();
            $table->text('link_adjunto_receptor')->nullable();
            $table->foreignId('dialogo_padre_id')->nullable()->constrained('dialogos');
            

            $table->foreignId('mensaje_id')->nullable()->constrained('mensajes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dialogos');
    }
};

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
        Schema::create('amortizacions', function (Blueprint $table) {
            $table->id();
            $table->integer('periodo')->nullable();
            $table->float('capital')->nullable();
            $table->float('interes')->nullable();
            $table->float('pago')->nullable();
            $table->date('dias_pago')->nullable();
            $table->text('uui_credit_request')->nullable();
            $table->foreignId('credit_request_id')->nullable()->constrained('credit_requests');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aamortizacions');
    }
};

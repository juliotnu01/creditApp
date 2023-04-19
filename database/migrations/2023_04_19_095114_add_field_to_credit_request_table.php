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
            $table->tinyInteger('status')->nullable()->default(false);
            $table->text('razon_rechazo')->nullable();
            $table->text('uui')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('credit_requests', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('razon_rechazo');
            $table->dropColumn('uui');
        });
    }
};

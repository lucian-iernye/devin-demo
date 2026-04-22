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
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->dateTime('reading_at');
            $table->unsignedBigInteger('kwh_cumulative');
            $table->unsignedBigInteger('kwh_period')->nullable();
            $table->enum('source', ['manual', 'api', 'broker'])->default('manual');
            $table->timestamps();

            $table->index(['contract_id', 'reading_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};

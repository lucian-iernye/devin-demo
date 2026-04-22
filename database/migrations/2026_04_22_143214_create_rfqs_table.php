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
        Schema::create('rfqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buyer_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->unsignedBigInteger('expected_annual_kwh');
            $table->date('desired_start_date');
            $table->unsignedSmallInteger('contract_length_months');
            $table->unsignedTinyInteger('green_min_percentage')->nullable();
            $table->string('region')->nullable();
            $table->enum('status', ['open', 'quoting', 'awarded', 'closed', 'cancelled'])->default('open');
            $table->dateTime('closes_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'closes_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rfqs');
    }
};

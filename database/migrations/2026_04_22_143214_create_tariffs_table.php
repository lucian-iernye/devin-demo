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
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('type', ['fixed', 'variable', 'indexed'])->default('fixed');
            $table->decimal('price_per_kwh', 8, 4);
            $table->char('currency', 3)->default('EUR');
            $table->unsignedTinyInteger('green_percentage')->default(0);
            $table->unsignedSmallInteger('contract_length_months');
            $table->unsignedBigInteger('min_annual_kwh')->nullable();
            $table->string('region')->nullable();
            $table->date('available_from');
            $table->date('available_to')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['active', 'available_from']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};

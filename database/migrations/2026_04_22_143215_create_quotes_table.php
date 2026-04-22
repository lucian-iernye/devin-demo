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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rfq_id')->constrained()->cascadeOnDelete();
            $table->foreignId('broker_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tariff_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('offered_price_per_kwh', 8, 4);
            $table->decimal('commission_rate', 5, 4);
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'submitted', 'accepted', 'rejected', 'withdrawn'])->default('draft');
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->index(['rfq_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotes');
    }
};

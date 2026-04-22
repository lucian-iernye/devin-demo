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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('broker_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tariff_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('price_per_kwh', 8, 4);
            $table->decimal('commission_rate', 5, 4);
            $table->date('starts_on');
            $table->date('ends_on');
            $table->enum('status', ['pending_signature', 'active', 'terminated', 'expired'])->default('pending_signature');
            $table->dateTime('signed_at')->nullable();
            $table->string('terms_pdf_path')->nullable();
            $table->timestamps();

            $table->index(['status', 'starts_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};

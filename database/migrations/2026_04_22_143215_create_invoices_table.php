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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->foreignId('buyer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('broker_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->unsignedBigInteger('consumed_kwh');
            $table->decimal('amount_energy', 12, 2);
            $table->decimal('amount_commission', 12, 2);
            $table->decimal('amount_total', 12, 2);
            $table->char('currency', 3)->default('EUR');
            $table->enum('status', ['draft', 'issued', 'paid', 'overdue', 'void'])->default('draft');
            $table->dateTime('issued_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->timestamps();

            $table->index(['contract_id', 'period_start']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

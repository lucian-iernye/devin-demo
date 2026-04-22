<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'contract_id',
        'buyer_id',
        'broker_id',
        'period_start',
        'period_end',
        'consumed_kwh',
        'amount_energy',
        'amount_commission',
        'amount_total',
        'currency',
        'status',
        'issued_at',
        'paid_at',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'consumed_kwh' => 'integer',
        'amount_energy' => 'decimal:2',
        'amount_commission' => 'decimal:2',
        'amount_total' => 'decimal:2',
        'issued_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }
}

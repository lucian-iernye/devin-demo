<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Quote extends Model
{
    use HasFactory;

    protected $table = 'quotes';

    protected $fillable = [
        'rfq_id',
        'broker_id',
        'tariff_id',
        'offered_price_per_kwh',
        'commission_rate',
        'notes',
        'status',
        'submitted_at',
        'expires_at',
    ];

    protected $casts = [
        'offered_price_per_kwh' => 'decimal:4',
        'commission_rate' => 'decimal:4',
        'submitted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function rfq(): BelongsTo
    {
        return $this->belongsTo(Rfq::class);
    }

    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    public function tariff(): BelongsTo
    {
        return $this->belongsTo(Tariff::class);
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }
}

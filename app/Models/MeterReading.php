<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeterReading extends Model
{
    use HasFactory;

    protected $table = 'meter_readings';

    protected $fillable = [
        'contract_id',
        'reading_at',
        'kwh_cumulative',
        'kwh_period',
        'source',
    ];

    protected $casts = [
        'reading_at' => 'datetime',
        'kwh_cumulative' => 'integer',
        'kwh_period' => 'integer',
    ];

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}

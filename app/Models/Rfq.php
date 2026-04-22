<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rfq extends Model
{
    use HasFactory;

    protected $table = 'rfqs';

    protected $fillable = [
        'buyer_id',
        'title',
        'expected_annual_kwh',
        'desired_start_date',
        'contract_length_months',
        'green_min_percentage',
        'region',
        'status',
        'closes_at',
    ];

    protected $casts = [
        'expected_annual_kwh' => 'integer',
        'desired_start_date' => 'date',
        'contract_length_months' => 'integer',
        'green_min_percentage' => 'integer',
        'closes_at' => 'datetime',
    ];

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Buyer::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}

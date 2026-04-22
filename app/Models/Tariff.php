<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tariff extends Model
{
    use HasFactory;

    protected $table = 'tariffs';

    protected $fillable = [
        'supplier_id',
        'name',
        'type',
        'price_per_kwh',
        'currency',
        'green_percentage',
        'contract_length_months',
        'min_annual_kwh',
        'region',
        'available_from',
        'available_to',
        'active',
    ];

    protected $casts = [
        'price_per_kwh' => 'decimal:4',
        'green_percentage' => 'integer',
        'contract_length_months' => 'integer',
        'min_annual_kwh' => 'integer',
        'available_from' => 'date',
        'available_to' => 'date',
        'active' => 'boolean',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}

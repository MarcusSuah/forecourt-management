<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class MeterCollection extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'station_id', 'product_id', 'pump_id', 'opening_meter', 'closing_meter', 'rtt', 'unit_price_at_sale', 'volume', 'sales_in_gallon', 'sales_turnover'];

    protected $casts = [
        'date' => 'date',
        'opening_meter' => 'decimal:2',
        'closing_meter' => 'decimal:2',
        'rtt' => 'decimal:2',
        'unit_price_at_sale' => 'decimal:4',
        'volume' => 'decimal:2',
        'sales_in_gallon' => 'decimal:2',
        'sales_turnover' => 'decimal:2',
    ];

    public function station(): BelongsTo
    {
        return $this->belongsTo(ServiceStation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function pump(): BelongsTo
    {
        return $this->belongsTo(Pump::class);
    }

    public function unitPrice(): BelongsTo
    {
        return $this->belongsTo(UnitPrice::class, 'product_id', 'product_id');
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
   
}

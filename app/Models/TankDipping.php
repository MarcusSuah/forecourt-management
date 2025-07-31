<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TankDipping extends Model
{
    protected $fillable = [
        'date',
        'station_id',
        'shift_id',
        'product_id',
        'tank_id',
        'opening_dips',
        'qty_rec',
        'rtt',
        'closing_dips',
        'tank_sales',
        'pump_sales_id',
        'meter_collection_id',
        'pump_sales',
        'variance',
        'capacity',
        'aval_ullage',
        'sales_percentage',
        'threshold',
        'status'
    ];

    protected $casts = [
        'date' => 'date',
        'sales_percentage' => 'decimal:2',
        'threshold' => 'decimal:2',
    ];

    // Relationships
    public function station()
    {
        return $this->belongsTo(ServiceStation::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function tank()
    {
        return $this->belongsTo(Tank::class);
    }

    public function pumpSales()
    {
        return $this->belongsTo(MeterCollection::class, 'pump_sales_id');
    }

    public function meterCollection(): BelongsTo
    {
        return $this->belongsTo(MeterCollection::class, 'meter_collection_id');
    }
    // Status Badge Accessor
    public function getStatusBadgeAttribute()
    {
        $cd = $this->closing_dips;
        $capacity = $this->capacity;
        $threshold = $this->threshold;

        if ($cd == $capacity || $cd >= 0.75 * $capacity) {
            return ['class' => 'bg-green-500 text-white', 'label' => 'Full Tank', 'icon' => 'check'];
        } elseif ($cd >= 0.5 * $capacity && $cd > 500) {
            return ['class' => 'bg-blue-500 text-white', 'label' => 'Half Tank', 'icon' => 'information-circle'];
        } elseif ($cd < 0.5 * $capacity && $cd > 500) {
            return ['class' => 'bg-gray-500 text-white', 'label' => 'Medium Storage', 'icon' => 'plus'];
        } elseif ($cd <= 500 && $cd > $threshold) {
            return ['class' => 'bg-yellow-500 text-black', 'label' => 'Low Storage', 'icon' => 'exclamation'];
        } elseif ($cd <= $threshold) {
            return ['class' => 'bg-red-500 text-white', 'label' => 'Out of Stock', 'icon' => 'x'];
        } else {
            return ['class' => 'bg-black text-white', 'label' => 'Unknown', 'icon' => 'help'];
        }

    }

    // Mutators to handle calculations
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($tankDipping) {
            // Calculate tank sales: qty_rec + rtt + opening_dips - closing_dips
            $tankDipping->tank_sales = $tankDipping->qty_rec + $tankDipping->rtt + $tankDipping->opening_dips - $tankDipping->closing_dips;

            // Calculate variance: tank_sales - pump_sales
            $tankDipping->variance = $tankDipping->tank_sales - $tankDipping->pump_sales;

            // Get capacity from tank
            if ($tankDipping->tank) {
                $tankDipping->capacity = $tankDipping->tank->capacity;
            }

            // Calculate available ullage: closing_dips - tank_capacity (this seems reversed, should be capacity - closing_dips)
            $tankDipping->aval_ullage = $tankDipping->capacity - $tankDipping->closing_dips;

            // Calculate sales percentage: aval_ullage / tank_capacity
            if ($tankDipping->capacity > 0) {
                $tankDipping->sales_percentage = ($tankDipping->aval_ullage / $tankDipping->capacity) * 100;
            }
        });
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByStation($query, $stationId)
    {
        return $query->where('station_id', $stationId);
    }

    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }
}

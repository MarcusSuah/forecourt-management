<?php
// app/Models/Tank.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tank extends Model
{
    use HasFactory;

    protected $fillable = ['station_id', 'product_id', 'name', 'capacity', 'status'];

    public function station()
    {
        return $this->belongsTo(ServiceStation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getCapacityInUsGallonsAttribute(): float
    {
        return $this->capacity / 3.78541;
    }

    public function getCapacityInImperialGallonsAttribute(): float
    {
        return $this->capacity / 4.54609;
    }

    public function getUnitLabelAttribute()
{
    return match ($this->displayUnit) {
        'liters' => 'Ltrs',
        'us_gallons' => 'US Gal',
        'imperial_gallons' => 'UK Gal',
    };
}
}

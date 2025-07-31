<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pump extends Model
{
    use HasFactory;

    protected $fillable = ['station_id', 'product_id', 'name', 'status'];

    public function station()
    {
        return $this->belongsTo(ServiceStation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function meterCollections()
    {
        return $this->hasMany(MeterCollection::class);
    }
    public function unitPrices(): HasMany
    {
        return $this->hasMany(UnitPrice::class);
    }
    
}

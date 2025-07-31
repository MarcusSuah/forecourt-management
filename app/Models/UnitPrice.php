<?php

namespace App\Models;
use App\Models\ServiceStation;
use Illuminate\Database\Eloquent\Model;

class UnitPrice extends Model
{
      protected $fillable = ['station_id', 'product_id', 'price', 'date', 'status'];

protected $casts = [
    'date' => 'date',
];

    public static function getCurrentPriceForProduct($productId, $stationId = null)
    {
        $query = self::where('product_id', $productId);

        if ($stationId) {
            $query->where('station_id', $stationId);
        }

        return $query->latest('date')->first();
    }

    public function station()
    {
        return $this->belongsTo(ServiceStation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

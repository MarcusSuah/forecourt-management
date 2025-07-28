<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pump extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'product_id',
        'name',
        'status',
    ];

    public function station()
    {
        return $this->belongsTo(ServiceStation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

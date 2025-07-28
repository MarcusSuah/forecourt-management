<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = ['station_id', 'name', 'start_time', 'end_time'];

    public function station()
    {
        return $this->belongsTo(ServiceStation::class, 'station_id');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Shift extends Model
{
    use HasFactory;

    protected $fillable = ['station_id', 'name', 'start_time', 'end_time'];

    protected $casts = [
    'start_time' => 'datetime:H:i',
    'end_time'   => 'datetime:H:i',
];
    public function station()
    {
        return $this->belongsTo(ServiceStation::class, 'station_id');
    }
    public function meterCollections(): HasMany
    {
        return $this->hasMany(MeterCollection::class);
    }
    public function getTimeRangeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    /**
     * Scope for active shifts
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}

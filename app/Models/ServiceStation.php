<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceStation extends Model
{
    use HasFactory;

    // Table name (optional if naming convention matches)
    protected $table = 'service_stations';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['territory_manager', 'dealer_id', 'logo', 'name', 'email', 'phone', 'location', 'sap_number', 'opening_time', 'closing_time', 'is_active'];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'opening_time' => 'datetime:H:i:s',
        'closing_time' => 'datetime:H:i:s',
    ];

    /**
     * Relationship: ServiceStation belongs to a Dealer.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }
    public function shifts()
    {
        return $this->hasMany(Shift::class, 'station_id');
    }
    public function meterCollections(): HasMany
    {
        return $this->hasMany(MeterCollection::class);
    }
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    public function pumps(): HasMany
    {
        return $this->hasMany(Pump::class);
    }
    /**
     * Accessor for logo URL.
     * Returns full URL or null if no logo.
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }
}

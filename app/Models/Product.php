<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Product extends Model
{
      protected $fillable = [
        'name', 'detail'
    ];

    public function meterCollections(): HasMany
    {
        return $this->hasMany(MeterCollection::class);
    }

    public function unitPrices(): HasMany
    {
        return $this->hasMany(UnitPrice::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bank extends Model
{
    use HasFactory;

    protected $fillable = ['bank_id', 'station_id', 'account_name', 'account_number_usd', 'account_number_local', 'bank_name', 'branch', 'status'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function station()
    {
        return $this->belongsTo(ServiceStation::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
    public static function generateBankId()
    {
        do {
            $bankId = 'BNK-' . strtoupper(uniqid());
        } while (self::where('bank_id', $bankId)->exists());

        return $bankId;
    }
}

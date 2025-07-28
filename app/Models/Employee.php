<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'station_id',
        'designation_id',
        'fname',
        'lname',
        'email',
        'phone',
        'ssn',
        'address',
        'dob',
        'gender',
        'emp_date',
        'image',
    ];

    protected $casts = [
        'dob' => 'date',
        'emp_date' => 'date',
    ];

    public function station()
    {
        return $this->belongsTo(ServiceStation::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
}

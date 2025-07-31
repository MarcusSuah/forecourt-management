<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dealer extends Model
{
    use HasFactory;

    protected $fillable = [
        'fname',
        'lname',
        'email',
        'image',
        'dob',
        'age',
        'gender',
        'phone',
        'address',
        'status'
    ];
       protected $casts = [
        'dob' => 'date',
    ];

}

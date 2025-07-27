<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Login extends Model
{
    protected $fillable = ['user_id', 'logged_in_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

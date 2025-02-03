<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserVerificationToken extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'otp', 'expires_at'];

    protected $casts = ['expires_at' => 'date'];

    public function hasExpired(): bool
    {
        return $this->expires_at->lt(Carbon::now());
    }
}
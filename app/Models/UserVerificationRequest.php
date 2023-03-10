<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserVerificationRequest extends Model
{
    use HasFactory;
    public $table = 'user_verification_requests';
    protected $fillable = [
        'user_id',
    ];
}

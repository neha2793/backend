<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserNFT extends Model
{
    use HasFactory;

    public $table = 'user_nfts';
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'string_price',
        'image',
        'hash_token',
        'tokenID',
        'status',
        'seller',
        'order_count',
    ];

}

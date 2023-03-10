<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBoughtNFT extends Model
{
    use HasFactory;

    public $table = 'User_bought_NFT';
    protected $fillable = [
        'user_id',
        'from_wallet',
        'price',
        'product_id',
        'string_price',
        'hash_token',
        'tokenID',
        'gasLimit',
        'gasPrice',
        'maxFeePerGas',
        'maxPriorityFeePerGas',
    ];
}

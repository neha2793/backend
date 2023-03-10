<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBL_slime_seats extends Model
{
    use HasFactory;

    public $table = 'TBL_slime_seats';
    protected $fillable = [
        'User_ID',
        'name',
        'Description',
        'Price',
        'featured_image',
        'product_id',
        'Date_created',
        'Status',
    ];
}

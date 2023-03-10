<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBLShippingContainer extends Model
{
    use HasFactory;

    public $table = 'TBL_Shipping_container';
    protected $fillable = [
        'Sc_ID',
        'Name',
        'Description',
        'Featured_Image',
        'User_ID',
        'Status',
        'Visit_count',
        'Current_count',
    ];

}

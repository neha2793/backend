<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    public $table = 'TBL_Orders';
    protected $fillable = [
        'User_ID',
        'Shipping_FirstName',
        'Shipping_lastName',
        'Shipping_address1',
        'Shipping_address2',
        'Shipping_city',
        'Shipping_state',
        'Shipping_Zipcode',
        'Order_total',
        'Order_date',
        'Status',
        'country',
    ];

}

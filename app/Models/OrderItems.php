<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    use HasFactory;
    public $table = 'TBL_Order_items';
    protected $fillable = [
        'Order_ID',
        'Product_ID',
        'Quantity',
        'Price',
    ];
}

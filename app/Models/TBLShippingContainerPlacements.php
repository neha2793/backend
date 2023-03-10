<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBLShippingContainerPlacements extends Model
{
    use HasFactory;
    public $table = 'TBL_Shipping_container_placements';
    protected $fillable = [
        'SC_ID',
        'Wall_no',
        'x_co',
        'y_co',
        'Item_ID',
        'Item_type',
        'status',
    ];
}

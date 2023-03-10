<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    use HasFactory;
    public $table = 'TBL_Transactions';
    protected $fillable = [
        'Order_ID',
        'User_ID',
        'T_Amount',
        'Transaction_Token',
        'Transaction_Date',
        'Status',
        'Payment_Method',
    ];
}

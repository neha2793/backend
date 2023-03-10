<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TBL_Slimeseat_Images extends Model
{
    use HasFactory;

    public $table = 'TBL_Slimeseat_Images';
    protected $fillable = [
        'S_ID',
        'Image_path',
        'Remarks',
        'Date_created',
        'Status',
    ];
}

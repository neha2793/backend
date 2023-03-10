<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SCVideos extends Model
{
    use HasFactory;
    public $table = 'sc_videos';
    protected $fillable = [
        'User_ID',
        'name',
        'description',
        'video',
        'redirection_link',
        'type'
    ];
}

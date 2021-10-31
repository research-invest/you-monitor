<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryDataVideo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'video_id',
        'channel_id',
        'views',
        'likes',
        'dis_likes',
    ];
}

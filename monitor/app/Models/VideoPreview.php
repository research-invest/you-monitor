<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoPreview extends Model
{
    use HasFactory;

    const DISK_NAME = 'video-previews';

    protected $guarded = ['id'];

    protected $fillable = [
        'channel_id',
        'video_id',
        'thumbnail_url',
        'hash',
    ];
}

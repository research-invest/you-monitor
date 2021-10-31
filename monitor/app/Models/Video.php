<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    protected $guarded = ['id'];

    protected $fillable = [
        'channel_id',
        'title',
        'description',
        'url',
        'video_id',
        'published_at',
        'thumbnail_url',
        'views',
        'rating_count',
        'rating_count',
        'created_at',
        'status',
        'length_seconds',
    ];


    public function getUrlVideo()
    {
        return 'https://www.youtube.com/watch?v=' . $this->video_id;
    }

    public function getImagePath(): string
    {
        return  $this->channel_id . '/' . $this->video_id . '.jpg';
    }


    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = 1;
    const STATUS_DELETED = 2;

    protected $guarded = ['id'];

    protected $fillable = [
        'channel_id',
        'url',
        'title',
        'description',
        'published_at',
        'count_views',
        'count_subscribers',
        'created_at',
        'status',
    ];


    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}

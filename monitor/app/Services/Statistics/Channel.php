<?php

namespace App\Services\Statistics;

use App\Models\Channel as ModelChannel;

class Channel
{
    private $channelId;

    public function __construct($id = null)
    {
        $this->channelId = $id;
    }

    public function getListChannels(): \Illuminate\Database\Eloquent\Collection|array
    {
        return ModelChannel::query()->orderBy('count_views', 'DESC')->get();
    }

    public function getById()
    {
        return ModelChannel::find($this->channelId);
    }
}

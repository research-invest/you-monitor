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

    public function getListChannels()
    {
        return ModelChannel::all();
    }

    public function getById()
    {
        return ModelChannel::find($this->channelId);
    }
}

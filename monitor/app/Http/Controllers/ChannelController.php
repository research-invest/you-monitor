<?php

namespace App\Http\Controllers;

use App\Services\Statistics\Channel;

class ChannelController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @var $page string|null
     *
     */
    public function index()
    {
        $channel = new Channel();

        return view('channel.index', [
            'channels' => $channel->getListChannels(),
        ]);
    }

    public function show($id)
    {
        $channel = new Channel($id);

        $channelData = $channel->getById();

        return view('channel.show', [
            'channel' => $channelData,
            'videos' => $channelData->videos,
        ]);
    }

}

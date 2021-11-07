<?php

namespace App\Console\Commands;

use App\Helpers\StringHelper;
use App\Models\ChangeLog;
use App\Models\Channel;
use App\Models\HistoryDataChannel;
use App\Models\Video;

/**
 * php artisan get-channel-info:run
 */
class GetChannelInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-channel-info:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $channels = Channel::where('status', Video::STATUS_ACTIVE)
            ->orderBy('id')
            ->get();

        /**
         * @var Channel $channel
         */
        foreach ($channels as $channel) {
            $history = new HistoryDataChannel();

            $data = $this->getAbout($channel->url);

            if ($data === false) {
                $channel->status = Channel::STATUS_DELETED;
                $channel->save();

                ChangeLog::create([
                    'channel_id' => $channel->id,
                    'log' => $channel->id . 'Канал удален',
                ]);

                continue;
            }

            $history->setRawAttributes(
                [
                    'channel_id' => $channel->id,
                    'views' => $data['views'] ?? 0,
                ]);

            $history->save();

            $channel->count_views = $history->views;
            $channel->save();
        }
    }

    protected function getAbout($channelUrl)
    {
        //            //\"channelAboutFullMetadataRenderer\": \{.*\}


        // /\"viewCountText\": \{.*\}/mg
        // /\"country\": \{.*\}/mg
        // /(\"channelMetadataRenderer\": \{.*\})]/mgs

        // /"rssUrl": "(.*)"./g


        $content = $this->getRequest($channelUrl . '/about');

//        preg_match('/"playabilityStatus":{"status":"(.*)",/U', $content, $playabilityStatus);
//        $playabilityStatus = count($playabilityStatus) === 2 ? $playabilityStatus[1] : 'OK';
//
//        if ($playabilityStatus === 'ERROR') {////TEST
//            return false;
//        }

        preg_match('/"viewCountText\":\{(.*)\}/U', $content, $viewCount);
        $viewCount = count($viewCount) === 2 ? StringHelper::toInt(str_replace([' ', ':', '"', '\\'], '', $viewCount[1])) : 0;

        return [
            'views' => $viewCount,
        ];
    }
}

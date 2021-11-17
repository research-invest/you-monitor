<?php

namespace App\Console\Commands;

use App\Helpers\FileHelper;
use App\Models\Channel;
use App\Models\Video;
use App\Models\VideoPreview;
use App\Notifications\NewVideo;
use App\Services\YouTube\RssFeed;
use Illuminate\Support\Facades\Storage;

/**
 * php artisan get-new-video-channels-by-rss:run
 */
class GetNewVideoChannelsByRss extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-new-video-channels-by-rss:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получаем список видео и краткую информацию о канале';

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
        $channels = Channel::where('status', Channel::STATUS_ACTIVE)
            ->orderBy('id')
            ->get();

        /**
         * @var Channel $channel
         */
        foreach ($channels as $channel) {
            $channelId = $isChannelId = $channel->channel_id;

            if (!$channelId) {
                $channelId = $this->getExternalId($channel->url);

                if (!$channelId) {
                    continue;
                }

                $channel->channel_id = $channelId;
            }

            $rssFeed = $this->getRssFeed($channelId);

            $channel->title = $channel->title ?: $rssFeed->channel()->name;
            $channel->published_at = $channel->published_at ?: $rssFeed->channel()->published;

            if ($channel->isDirty()) {
                $channel->save();
            }

            foreach ($rssFeed->channel()->videos as $video) {

                $hasVideo = Video::where('video_id', $video->id)->exists();

                if ($hasVideo) {
                    continue;
                }

                $thumbnail = $this->downloadPreview($channel->channel_id, $video->id);

                $data = Video::create([
                    'video_id' => $video->id,
                    'channel_id' => $channel->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'url' => $video->url,
                    'published_at' => $video->published_at,
                    'thumbnail_url' => $thumbnail,
                    'views' => $video->views,
                    'rating_count' => $video->rating_count
                ]);

                if ($thumbnail) {
                    $thumbnailPath = Storage::disk(VideoPreview::DISK_NAME)->path($thumbnail);
                    VideoPreview::create([
                        'video_id' => $data->id,
                        'channel_id' => $channel->id,
                        'thumbnail_url' => $thumbnail,
                        'hash' => FileHelper::hashFile($thumbnailPath),
                    ]);
                }

                if ($isChannelId) {
                    $data->notify(new NewVideo());
                }
            }

            // https://www.youtube.com/feeds/videos.xml?channel_id=UCA73Wknyv1BB7GY_6GhqDmw
        }
    }


    private function getExternalId($channelUrl)
    {
        $content = $this->getRequest($channelUrl . '/about');

        $contentMini = substr($content, strpos($content, '"externalId"'), 100);
        preg_match('/"externalId":"(.*)",/U', $contentMini, $externalId);

        if (count($externalId) === 2) {
            return $externalId[1];
        }

        // write LOG

        return null;
    }

    private function getRssFeed(string $channelId): RssFeed
    {
        $url = 'https://www.youtube.com/feeds/videos.xml?channel_id=' . $channelId;
        return new RssFeed($url);
    }


}

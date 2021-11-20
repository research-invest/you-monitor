<?php

namespace App\Console\Commands;

use App\Helpers\FileHelper;
use App\Helpers\MathHelper;
use App\Helpers\StringHelper;
use App\Models\ChangeLog;
use App\Models\Channel;
use App\Models\HistoryDataVideo;
use App\Models\Video;
use App\Models\VideoPreview;
use Illuminate\Support\Facades\Storage;

/**
 * php artisan get-video-info:run
 */
class GetVideoInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-video-info:run';

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
        $videos = Video::query()
            ->select('videos.*')
            ->join('channels AS ch', 'videos.channel_id', '=', 'ch.id')
            ->where('videos.status', Video::STATUS_ACTIVE)
            ->where('ch.status', Channel::STATUS_ACTIVE)
            ->orderBy('videos.id', 'DESC')
            ->get();

        /**
         * @var Video $video
         */
        foreach ($videos as $video) {
            $history = new HistoryDataVideo();

            $data = $this->getVideoData($video->getUrlVideo());

            if ($data === false) {
                $video->status = Video::STATUS_DELETED;
                $video->save();

                ChangeLog::create([
                    'video_id' => $video->id,
                    'channel_id' => $video->channel_id,
                    'log' => $video->video_id . ' Удалено',
                ]);

                continue;
            }

            if (!$video->length_seconds && !empty($data['length_seconds'])) {
                $video->length_seconds = $data['length_seconds'];
                $video->save();
            }

            $lastHistory = HistoryDataVideo::query()
                ->select('views')
                ->where('video_id', $video->id)
                ->orderBy('id', 'DESC')
                ->first();

            $views = $data['views'] ?? 0;

            $history->setRawAttributes(
                [
                    'video_id' => $video->id,
                    'channel_id' => $video->channel_id,
                    'views' => $views,
                    'likes' => $data['likes'] ?? 0,
                    'dis_likes' => $data['dis_likes'] ?? 0,
                    'average_rating' => $data['average_rating'] ?? 0,
                    'delta' => MathHelper::getPercentageChange($lastHistory ? $lastHistory->views : 0, $views),
                ]);

            $history->save();

            $preview = VideoPreview::where('video_id', $video->id)->orderBy('id', 'DESC')->first();

            if (!$preview) {
                continue;
            }

            $actualImage = file_get_contents($this->getImageUrlByYtimg($video->video_id)); //lara method -?

            $actualImageHash = FileHelper::hash($actualImage);

            if (!hash_equals($actualImageHash, $preview->hash)) { // new th
                $path = $video->channel->channel_id . '/' . uniqid($video->id . '_') . '.jpg';
                Storage::disk(VideoPreview::DISK_NAME)->put($path, $actualImage);

                $thumbnailPath = Storage::disk(VideoPreview::DISK_NAME)->path($path);

                VideoPreview::create([
                    'video_id' => $video->id,
                    'channel_id' => $video->channel_id,
                    'thumbnail_url' => $path,
                    'hash' => FileHelper::hashFile($thumbnailPath),
                ]);

                ChangeLog::create([
                    'video_id' => $video->id,
                    'channel_id' => $video->channel_id,
                    'log' => $video->video_id . '  поменялась превью',
                ]);

            }


        }
    }

    private function getVideoData(string $urlVideo)
    {
        $content = $this->getRequest($urlVideo);


        //        "playabilityStatus": {
//        "status": "OK",
//        "playableInEmbed": true,
//        "miniplayer": {"miniplayerRenderer": {"playbackMode": "PLAYBACK_MODE_ALLOW"}},
//        "contextParams": "Q0FFU0FnZ0M="
//    },


        preg_match('/"playabilityStatus":{"status":"(.*)",/U', $content, $playabilityStatus);
        $playabilityStatus = count($playabilityStatus) === 2 ? $playabilityStatus[1] : 'OK';

        if ($playabilityStatus === 'ERROR') {
            return false;
        }

        $contentViewCountRating = substr($content,
            strpos($content, '"videoDetails":{'),
            strpos($content, '"playerConfig":{') - strpos($content, '"videoDetails":{'),
        );

        $getLikes = function ($s) {
            preg_match('/({"accessibilityData")?:\"(\d.+)"/U', $s, $likes);
            return count($likes) === 3 ? StringHelper::toInt(str_replace([' ', ':', '"', '\\'], '', $likes[0])) : 0;
        };

        preg_match('/"averageRating":(.*),/U', $contentViewCountRating, $averageRating);
        $averageRating = count($averageRating) === 2 ? StringHelper::toFloat($averageRating[1]) : 0; //4.8610454

        preg_match('/"viewCount":(.*),/U', $contentViewCountRating, $viewCount);
        $viewCount = count($viewCount) === 2 ? StringHelper::toInt($viewCount[1]) : 0;

        preg_match('/"lengthSeconds":(.*),/U', $contentViewCountRating, $lengthSeconds);
        $lengthSeconds = count($lengthSeconds) === 2 ? StringHelper::toInt($lengthSeconds[1]) : 0;//

        $contentDisLike = substr($content,
            strpos($content, '"iconType":"DISLIKE"'), 500);
        $contentLike = substr($content,
            strpos($content, '"iconType":"LIKE"'), 500);

        return [
            'views' => $viewCount,
            'likes' => $getLikes($contentLike),
            'dis_likes' => $getLikes($contentDisLike),
            'average_rating' => $averageRating,
            'length_seconds' => $lengthSeconds,
        ];
    }
}

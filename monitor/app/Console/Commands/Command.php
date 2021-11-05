<?php

namespace App\Console\Commands;

use App\Models\VideoPreview;
use Illuminate\Console\Command as LCommand;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Support\Facades\Log;

class Command extends LCommand
{

    protected $name = 'test';

    protected function getRequest($url): string
    {
        $url = trim($url);

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);

            $statusCode = $response->getStatusCode();

            return $response->getBody()->getContents();
        } catch (\Exception $exception) {
            $message = sprintf('%s url: %s', $exception->getMessage(), $url);
            Log::channel('single')->debug($message);
            return '';
        }
    }

    protected function downloadPreview($channelId, $videoId): string
    {
        try {
            $file = file_get_contents($this->getImageUrlByYtimg($videoId));

            $path = $channelId . '/' . $videoId . '.jpg';
            Storage::disk(VideoPreview::DISK_NAME)->put($path, $file);
            return $path;
        } catch (\Exception $exception) {
            $message = sprintf('%s channelId: %s videoId: %s', $exception->getMessage(), $channelId, $videoId);
            Log::channel('video')->debug($message);
            return '';
        }

    }

    protected function getImageUrlByYtimg($videoId): string
    {
        return sprintf('https://i.ytimg.com/vi/%s/maxresdefault.jpg', $videoId);
    }


}

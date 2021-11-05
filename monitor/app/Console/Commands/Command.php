<?php

namespace App\Console\Commands;

use App\Models\VideoPreview;
use Illuminate\Console\Command as LCommand;
use Illuminate\Support\Facades\Storage;

class Command extends LCommand
{

    protected $name = 'test';

    protected function getRequest($url): string
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', trim($url));

            $statusCode = $response->getStatusCode();

            return $response->getBody()->getContents();
        } catch (\Exception $exception) {
            dd([
                $url,
                $exception->getMessage(),
            ]);
        }
    }

    protected function downloadPreview($channelId, $videoId): string
    {
        $file = file_get_contents($this->getImageUrlByYtimg($videoId));

        $path = $channelId . '/' . $videoId . '.jpg';
        Storage::disk(VideoPreview::DISK_NAME)->put($path, $file);

        return $path;
    }

    protected function getImageUrlByYtimg($videoId): string
    {
        return sprintf('https://i.ytimg.com/vi/%s/maxresdefault.jpg', $videoId);
    }


}

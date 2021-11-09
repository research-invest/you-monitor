<?php

namespace App\Services\Statistics;

use App\Models\Channel;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class Concurrency
{
    private array $channels = [];
    private array $drilldown = [];

    public function __construct()
    {
        $this->getChannelsData();
        $this->getDrilldownData();
    }

    private function getChannelsData()
    {
        $sql = <<<SQL
SELECT c.id, c.title AS channel_title, MAX(v.views) as max_views
FROM channels AS c
INNER JOIN history_data_channels AS h ON c.id = h.channel_id
INNER JOIN videos AS v ON v.channel_id = h.channel_id AND v.status = :video_status
WHERE c.status = :channel_status
GROUP BY c.id, c.title
ORDER BY max_views DESC;
SQL;

        $this->channels = DB::select($sql, [
            ':video_status' => Video::STATUS_ACTIVE,
            ':channel_status' => Channel::STATUS_ACTIVE,
        ]);
    }

    private function getDrilldownData()
    {
        $sql = <<<SQL
SELECT c.id AS channel_id, c.title AS channel_title, v.id AS video_id, v.title AS video_title, MAX(h.views) as max_views
FROM channels AS c
INNER JOIN history_data_videos AS h ON c.id = h.channel_id
INNER JOIN videos AS v ON v.id = h.video_id AND v.status = :video_status
WHERE c.status = :channel_status
GROUP BY c.id, c.title, v.id, v.title
ORDER BY max_views DESC;
SQL;

        $this->drilldown = DB::select($sql, [
            ':video_status' => Video::STATUS_ACTIVE,
            ':channel_status' => Channel::STATUS_ACTIVE,
        ]);
    }

    public function getSeries()
    {
        $dataSeries = [];
        $sumViews = array_sum(array_column($this->channels, 'max_views'));

        foreach ($this->channels as $channel) {
            $dataSeries[] = [
                'name' => $channel->channel_title,
                'y' => round(($channel->max_views / $sumViews) * 100, 2),
                'drilldown' => $channel->id,
            ];
        }

        return [
            [
                'name' => 'Каналы',
                'colorByPoint' => true,
                'data' => $dataSeries,
            ]
        ];
    }

    public function getDrilldown()
    {
        $dataSeries = $sumChannelsViews = $dataVideoSeries = [];

        foreach ($this->drilldown as $data) {
            $sumChannelsViews[$data->channel_id] = empty($sumChannelsViews[$data->channel_id]) ? $data->max_views :
                $sumChannelsViews[$data->channel_id] + $data->max_views;
        }

        foreach ($this->drilldown as $data) {
            if (empty($dataVideoSeries[$data->channel_id]) || count($dataVideoSeries[$data->channel_id]) <= 14) {
                $dataVideoSeries[$data->channel_id][$data->video_id] = [
                    'title' => $data->video_title,
                    'max_views' => $data->max_views,
                    'percent_max_views' => round(($data->max_views / $sumChannelsViews[$data->channel_id]) * 100, 2),
                ];
            }
        }

        foreach ($this->drilldown as $channel) {
            $data = [];
            foreach (array_values($dataVideoSeries[$channel->channel_id]) as $video) {
                $data[] = [
                    'name' => $video['title'],
                    'y' => $video['percent_max_views'],
                ];
            }

            $dataSeries[] = [
                'name' => $channel->video_title,
                'id' => $channel->channel_id,
                'data' => $data
            ];
        }

        return [
            'series' => $dataSeries
        ];

    }
}

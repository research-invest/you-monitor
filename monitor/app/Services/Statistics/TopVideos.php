<?php

namespace App\Services\Statistics;

use App\Models\Channel;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class TopVideos
{

    public function getTop50(): array
    {
        $sql = <<<SQL
SELECT v.id AS video_id, v.title AS video_title, v.published_at AS video_published_at, v.url AS video_url,
       c.url AS channel_url, c.id AS channel_id, c.title AS channel_title, MAX(h.views) as max_views, v.length_seconds,
       MAX(h.average_rating) as average_rating
FROM videos AS v
INNER JOIN history_data_videos AS h ON v.id = h.video_id
INNER JOIN channels AS c ON c.id = v.channel_id AND c.status = :channel_status
WHERE v.status = :video_status
GROUP BY v.id, v.title, c.id, c.title, c.url, v.url, v.published_at, v.length_seconds
ORDER BY max_views DESC
LIMIT 50;
SQL;
        return DB::select($sql, [
            ':video_status' => Video::STATUS_ACTIVE,
            ':channel_status' => Channel::STATUS_ACTIVE,
        ]);
    }

    public function getTop24h(): array
    {
        $sql = <<<SQL
SELECT v.id AS video_id, v.title AS video_title, v.published_at AS video_published_at, v.url AS video_url,
       c.url AS channel_url, c.id AS channel_id, c.title AS channel_title, MAX(h.views) as max_views, v.length_seconds,
       MAX(h.average_rating) as average_rating
FROM videos AS v
INNER JOIN history_data_videos AS h ON v.id = h.video_id
INNER JOIN channels AS c ON c.id = v.channel_id AND c.status = :channel_status
WHERE v.status = :video_status AND v.published_at >= :published_at
GROUP BY v.id, v.title, c.id, c.title, c.url, v.url, v.published_at, v.length_seconds
ORDER BY max_views DESC
LIMIT 50;
SQL;
        return DB::select($sql, [
            ':video_status' => Video::STATUS_ACTIVE,
            ':channel_status' => Channel::STATUS_ACTIVE,
            ':published_at' => date('Y-m-d H:i:s', strtotime('- 24 hours')),
        ]);
    }

    public function getNewVideos(): array
    {
        $sql = <<<SQL
SELECT v.id AS video_id, v.title AS video_title, v.published_at AS video_published_at, v.url AS video_url,
       c.url AS channel_url, c.id AS channel_id, c.title AS channel_title, MAX(h.views) as max_views, v.length_seconds,
       MAX(h.average_rating) as average_rating
FROM videos AS v
INNER JOIN history_data_videos AS h ON v.id = h.video_id
INNER JOIN channels AS c ON c.id = v.channel_id AND c.status = :channel_status
WHERE v.status = :video_status AND v.published_at >= :published_at
GROUP BY v.id, v.title, c.id, c.title, c.url, v.url, v.published_at, v.length_seconds
ORDER BY v.published_at DESC
LIMIT 50;
SQL;
        return DB::select($sql, [
            ':video_status' => Video::STATUS_ACTIVE,
            ':channel_status' => Channel::STATUS_ACTIVE,
            ':published_at' => date('Y-m-d H:i:s', strtotime('- 3 days')),
        ]);
    }
}

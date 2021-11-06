<?php

namespace App\Services\Statistics;

use Illuminate\Support\Facades\DB;

class Top20
{

    public function getData(): array
    {
        $sql = <<<SQL
SELECT v.id AS video_id, v.title AS video_title, v.published_at AS video_published_at, v.url AS video_url,
       c.url AS channel_url, c.title AS channel_title, MAX(h.views) as max_views, v.length_seconds
FROM videos AS v
INNER JOIN history_data_videos AS h ON v.id = h.video_id
INNER JOIN channels AS c ON c.id = v.channel_id
GROUP BY v.id, v.title, c.id, c.title, c.url, v.url, v.published_at, v.length_seconds
ORDER BY max_views DESC
LIMIT 20;
SQL;
        return DB::select($sql);
    }
}

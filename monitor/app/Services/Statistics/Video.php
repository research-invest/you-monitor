<?php

namespace App\Services\Statistics;

use App\Models\Channel;
use App\Models\Video as ModelVideo;
use Illuminate\Support\Facades\DB;

class Video
{

    private int $videoId;
    private array $viewsData;
    private array $likeDislikeDataData;

    public function __construct($id)
    {
        $this->videoId = $id;

        $this->getViewsData();
        $this->getLikeDislikeData();
    }

    public function getLikeDislikeData(): array
    {
        $sql = <<<SQL
SELECT MAX(h.likes) AS max_like, MAX(h.dis_likes) AS max_dislike, date_trunc('hour', h.created_at) AS day_time
FROM history_data_videos AS h
WHERE h.video_id = :video_id
GROUP BY day_time
ORDER BY day_time;
SQL;

        return $this->likeDislikeDataData = DB::select($sql, [
            ':video_id' => $this->videoId,
        ]);
    }
    public function getViewsData(): array
    {
        $sql = <<<SQL
SELECT MAX(h.views) AS max_views, date_trunc('hour', h.created_at) AS day_time
FROM history_data_videos AS h
WHERE h.video_id = :video_id
GROUP BY  day_time
ORDER BY day_time;
SQL;

        return $this->viewsData = DB::select($sql, [
            ':video_id' => $this->videoId,
        ]);
    }

    public function getViews()
    {
    }


    public function getInfo()
    {
        return ModelVideo::find($this->videoId);
    }

    public function getLikeDislikeCategories()
    {
        return array_column($this->likeDislikeDataData, 'day_time');
    }

    public function getLikesSeries()
    {
        return [
            [
                'name' => 'Like',
                'data' => array_column($this->likeDislikeDataData, 'max_like')

            ],
            [
                'name' => 'DisLike',
                'data' => array_column($this->likeDislikeDataData, 'max_dislike')
            ],
        ];

    }

    public function getViewsSeries(): array
    {
        return [
            [
                'name' => 'Просмотры',
                'data' => array_column($this->viewsData, 'max_views'),
            ]
        ];
    }

    public function getViewsCategories(): array
    {
        return array_column($this->viewsData, 'day_time');
    }
}

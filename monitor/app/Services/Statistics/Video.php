<?php

namespace App\Services\Statistics;

use App\Models\Channel;
use App\Models\Video as ModelVideo;
use Illuminate\Support\Facades\DB;

class Video
{

    private int $videoId;
    private array $viewsData;
    private array $likeDislikeData;
    private array $averageRatingData;

    public function __construct($id)
    {
        $this->videoId = $id;

        $this->getViewsData();
        $this->getLikeDislikeData();
        $this->getAverageRatingData();
    }

    public function getAverageRatingData(): array
    {
        $sql = <<<SQL
SELECT AVG(h.average_rating) AS avg_average_rating,  date_trunc('hour', h.created_at) AS day_time
FROM history_data_videos AS h
WHERE h.video_id = :video_id
GROUP BY day_time
ORDER BY day_time;
SQL;

        return $this->averageRatingData = DB::select($sql, [
            ':video_id' => $this->videoId,
        ]);
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

        return $this->likeDislikeData = DB::select($sql, [
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

    public function getInfo()
    {
        return ModelVideo::find($this->videoId);
    }

    public function getLikeDislikeCategories()
    {
        return array_column($this->likeDislikeData, 'day_time');
    }

    public function getLikesSeries()
    {
        return [
            [
                'name' => 'Like',
                'data' => array_column($this->likeDislikeData, 'max_like')

            ],
            [
                'name' => 'DisLike',
                'data' => array_column($this->likeDislikeData, 'max_dislike')
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

    public function getAverageRatingSeries(): array
    {
        return [
            [
                'name' => 'Рейтинг',
                'data' => array_column($this->averageRatingData, 'avg_average_rating'),
            ]
        ];
    }

    public function getAverageRatingCategories(): array
    {
      return array_column($this->averageRatingData, 'day_time');
    }
}

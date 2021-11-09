<?php

namespace App\Services\Statistics;

use App\Models\Channel as ModelChannel;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class Channel
{
    private $channelId;

    public function __construct($id = null)
    {
        $this->channelId = $id;
    }

    public function getListChannels(): \Illuminate\Database\Eloquent\Collection|array
    {
        return ModelChannel::query()->orderBy('count_views', 'DESC')->get();
    }

    public function getById()
    {
        return ModelChannel::find($this->channelId);
    }

    public function getLengthVideosStat(): array
    {
        $sql = <<<SQL
SELECT
       COUNT(v.id) filter (where v.length_seconds < 40) AS Меньше_40,
       COUNT(v.id) filter (where v.length_seconds >= 40 AND v.length_seconds < 50) AS Меньше_50,
       COUNT(v.id) filter (where v.length_seconds >= 50 AND v.length_seconds < 60) AS Меньше_60,
       COUNT(v.id) filter (where v.length_seconds >= 60 AND v.length_seconds < 70) AS Меньше_70,
       COUNT(v.id) filter (where v.length_seconds >= 70 AND v.length_seconds < 80) AS Меньше_80,
       COUNT(v.id) filter (where v.length_seconds >= 80 AND v.length_seconds < 90) AS Меньше_90,
       COUNT(v.id) filter (where v.length_seconds >= 90 AND v.length_seconds < 100) AS Меньше_100,
       COUNT(v.id) filter (where v.length_seconds >= 100 AND v.length_seconds < 110) AS Меньше_110,
       COUNT(v.id) filter (where v.length_seconds >= 110 AND v.length_seconds < 120) AS Меньше_120,
       COUNT(v.id) filter (where v.length_seconds >= 120 AND v.length_seconds < 180) AS Меньше_180,
       COUNT(v.id) filter (where v.length_seconds >= 180) AS Больше_180
FROM videos AS v
WHERE v.status = :video_status;
SQL;

        $data = (array)DB::selectOne($sql, [
            ':video_status' => Video::STATUS_ACTIVE,
        ]);

        $dataSeries = [];
        $sumCount = array_sum($data);
        foreach ($data as $key => $count) {
            $dataSeries[] = [
                'name' => $key,
                'y' => round(($count / $sumCount) * 100, 2),
            ];
        }

        return [[
                'name' => 'Длина видео',
                'colorByPoint' => true,
                'data' => $dataSeries
            ]];
    }
}

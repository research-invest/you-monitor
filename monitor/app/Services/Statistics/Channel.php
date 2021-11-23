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
       COUNT(v.id) filter (where v.length_seconds < 40) AS До_39,
       COUNT(v.id) filter (where v.length_seconds >= 40 AND v.length_seconds < 50) AS От_40_49,
       COUNT(v.id) filter (where v.length_seconds >= 50 AND v.length_seconds < 60) AS От_50_59,
       COUNT(v.id) filter (where v.length_seconds >= 60 AND v.length_seconds < 70) AS От_60_69,
       COUNT(v.id) filter (where v.length_seconds >= 70 AND v.length_seconds < 80) AS От_70_79,
       COUNT(v.id) filter (where v.length_seconds >= 80 AND v.length_seconds < 90) AS От_80_89,
       COUNT(v.id) filter (where v.length_seconds >= 90 AND v.length_seconds < 100) AS От_80_89,
       COUNT(v.id) filter (where v.length_seconds >= 100 AND v.length_seconds < 110) AS От_100_109,
       COUNT(v.id) filter (where v.length_seconds >= 110 AND v.length_seconds < 120) AS От_110_119,
       COUNT(v.id) filter (where v.length_seconds >= 120 AND v.length_seconds < 180) AS От_120_179,
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

    public function getTopLengthVideosStat(): array
    {
        $sql = <<<SQL
SELECT
       COUNT(v.id) filter (where v.length_seconds < 40) AS До_39,
       COUNT(v.id) filter (where v.length_seconds >= 40 AND v.length_seconds < 50) AS От_40_49,
       COUNT(v.id) filter (where v.length_seconds >= 50 AND v.length_seconds < 60) AS От_50_59,
       COUNT(v.id) filter (where v.length_seconds >= 60 AND v.length_seconds < 70) AS От_60_69,
       COUNT(v.id) filter (where v.length_seconds >= 70 AND v.length_seconds < 80) AS От_70_79,
       COUNT(v.id) filter (where v.length_seconds >= 80 AND v.length_seconds < 90) AS От_80_89,
       COUNT(v.id) filter (where v.length_seconds >= 90 AND v.length_seconds < 100) AS От_80_89,
       COUNT(v.id) filter (where v.length_seconds >= 100 AND v.length_seconds < 110) AS От_100_109,
       COUNT(v.id) filter (where v.length_seconds >= 110 AND v.length_seconds < 120) AS От_110_119,
       COUNT(v.id) filter (where v.length_seconds >= 120 AND v.length_seconds < 180) AS От_120_179,
       COUNT(v.id) filter (where v.length_seconds >= 180) AS Больше_180
FROM videos AS v
WHERE v.status = :video_status AND v.channel_id IN (SELECT id FROM channels ORDER BY views DESC LIMIT 3);
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

    private function weekOfMonth($date)
    {
        // estract date parts
        list($y, $m, $d) = explode('-', date('Y-m-d', strtotime($date)));

        // current week, min 1
        $w = 1;

        // for each day since the start of the month
        for ($i = 1; $i < $d; ++$i) {
            // if that day was a sunday and is not the first day of month
            if ($i > 1 && date('w', strtotime("$y-$m-$i")) == 0) {
                // increment current week
                ++$w;
            }
        }

        // now return
        return $w;
    }

    public function getSchedule()
    {
        $sql = <<<SQL
SELECT v.*
FROM videos AS v
WHERE v.channel_id = :channel_id AND v.published_at >= :published_at
ORDER BY v.published_at;
SQL;

        $data = (array)DB::select($sql, [
            ':channel_id' => $this->channelId,
            ':published_at' => date('Y-m-d H:i:s', strtotime('- 4 weeks')),
        ]);

        $result = [];
        foreach ($data as $video) {
            $tzMoscow = new \DateTime($video->published_at, new \DateTimeZone('UTC'));
            $tzMoscow->setTimezone(new \DateTimeZone('Europe/Moscow'));
            $hour = (int)$tzMoscow->format('H');
            $result[$this->weekOfMonth($tzMoscow->format('Y-m-d H:i:s'))]
            [(int)$tzMoscow->format('N')]
            [$hour] = [
                'url_youtube' => $video->url,
                'url' => route('video_show', ['id' => $video->id]),
            ];
        }

        return $result;
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\Statistics\Video;

class VideoController extends Controller
{

    public function show($id)
    {
        $video = new Video($id);

        return view('video.show', [
            'video' => $video->getInfo(),
            'likeDislike' => [
                'series' => $video->getLikesSeries(),
                'categories' => $video->getLikeDislikeCategories(),
            ],
            'views' => [
                'series' => $video->getViewsSeries(),
                'categories' => $video->getViewsCategories(),
            ],
            'averageRating' => [
                'series' => $video->getAverageRatingSeries(),
                'categories' => $video->getAverageRatingCategories(),
            ],
        ]);
    }
}

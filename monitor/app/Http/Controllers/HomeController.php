<?php

namespace App\Http\Controllers;

use App\Services\Statistics\Concurrency;
use App\Services\Statistics\TopVideos;

class HomeController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @var $page string|null
     *
     */
    public function index()
    {
        $concurrency = new Concurrency();
        $top50 = new TopVideos();

        return view('home.index', [
            'concurrency' => [
                'series' => $concurrency->getSeries(),
                'drilldown' => $concurrency->getDrilldown(),
            ],
            'top50' => $top50->getTop50(),
            'top24h' => $top50->getTop24h(),
            'newVideos' => $top50->getNewVideos(),
        ]);
    }
}

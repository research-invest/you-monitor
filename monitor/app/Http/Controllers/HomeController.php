<?php

namespace App\Http\Controllers;

use App\Services\Statistics\Concurrency;
use App\Services\Statistics\Top20;

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
        $top20 = new Top20();

        return view('home.index', [
            'concurrency' => [
                'series' => $concurrency->getSeries(),
                'drilldown' => $concurrency->getDrilldown(),
            ],
            'top20' => $top20->getData()
        ]);
    }
}

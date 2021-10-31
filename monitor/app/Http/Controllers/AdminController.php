<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class AdminController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @var $page null|string
     *
     */
    public function index()
    {
        request()->validate([
            'page' => 'integer|min:1',
        ]);

        $statResponse = $this->client->send('getStatistic', [
            'page' => request('page', 1),
            'per_page' => 5,
        ]);

        $pieStat = $this->client->send('getDistributionPage');

        if (isset($statResponse['error']) || isset($pieStat['error'])) {
            throw new \LogicException($statResponse['error'] ?? $pieStat['error'] ?? '');
        }

        $pieData = [];
        foreach ($pieStat['result'] as $stat) {
            $pieData[] = [
                'name' => $stat['url'],
                'y' => round($stat['percent'], 2),
            ];
        }

        $data = collect($statResponse['result']['rows']);
        $paginator = new LengthAwarePaginator(
            $data,
            $statResponse['result']['total'],
            $statResponse['result']['perPage'],
            $statResponse['result']['page']
        );

        $paginator->setPath(route('admin'));

        Paginator::useBootstrapThree();

        return view('admin', compact('data', 'paginator', 'pieData'));
    }
}

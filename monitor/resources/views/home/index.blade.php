@extends('layouts.main')
@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group me-2">
                <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
            </div>
            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                This week
            </button>
        </div>
    </div>
    <figure class="highcharts-figure">
        <div id="container"></div>
        <p class="highcharts-description">
            Chart showing browser market shares. Clicking on individual columns
            brings up more detailed data. This chart makes use of the drilldown
            feature in Highcharts to easily switch between datasets.
        </p>
    </figure>
    <h2>Популярые видео</h2>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col">Channel</th>
                <th scope="col">Video</th>
                <th scope="col">Views</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($top20 as $top)
                <tr>
                    <td>
                        <a target="_blank" href="{{ $top->channel_url }}">{{ $top->channel_title }}</a>
                    </td>
                    <td>
                        <a target="_blank" href="{{ $top->video_url }}">{{ $top->video_title }}</a>
                    </td>
                    <td>{{ number_format($top->max_views, 0, ',', ' ') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        var concurrencySeries = <?= json_encode($concurrency['series'])?>;
        var concurrencyDrilldown = <?= json_encode($concurrency['drilldown'])?>;

        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Конкурентный анализ'
            },
            subtitle: {
                text: 'Клик по колонке показывает детальный анализ каждого видео'
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: 'Общий процент распределения'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                series: {
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{point.y:.1f}%'
                    }
                }
            },

            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
            },

            series: concurrencySeries,
            drilldown: concurrencyDrilldown
        });
    </script>
@endsection

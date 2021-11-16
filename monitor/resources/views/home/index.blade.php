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
        {{--        <p class="highcharts-description">--}}
        {{--            Chart showing browser market shares. Clicking on individual columns--}}
        {{--            brings up more detailed data. This chart makes use of the drilldown--}}
        {{--            feature in Highcharts to easily switch between datasets.--}}
        {{--        </p>--}}
    </figure>


    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home" aria-selected="true">За 24ч</button>
            <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Топ 50</button>
            <button class="nav-link" id="nav-video-log-tab" data-bs-toggle="tab" data-bs-target="#nav-video-log" type="button" role="tab" aria-controls="nav-video-log" aria-selected="false">Новые видео</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th scope="col">Опубликован</th>
                        <th scope="col">Канал</th>
                        <th scope="col">Видео</th>
                        <th scope="col">Просмотров</th>
                        <th scope="col">Длительность</th>
                        <th scope="col">Рейтинг</th>
                        <th scope="col">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($top24h as $top)
                        <tr data-id=" {{ $top->video_id }}">
                            <td>
                                {{ $top->video_published_at }}
                            </td>
                            <td>
                                <a href="{{ route('channel_show', ['id' => $top->channel_id]) }}">{{ $top->channel_title }}</a>
                            </td>
                            <td>
                                <a href="{{ route('video_show', ['id' => $top->video_id]) }}">{{ $top->video_title }}</a>
                            </td>
                            <td>{{ number_format($top->max_views, 0, ',', ' ') }}</td>
                            <td>{{ gmdate("i:s", $top->length_seconds) }}</td>
                            <td>{{ $top->average_rating}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        Действия
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a target="_blank" class="dropdown-item" href="{{ route('video_show', ['id' => $top->video_id]) }}">Статистика
                                                видео</a></li>
                                        <li><a target="_blank" class="dropdown-item" href="{{ route('channel_show', ['id' => $top->channel_id]) }}">Статистика
                                                канала</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th scope="col">Опубликован</th>
                        <th scope="col">Канал</th>
                        <th scope="col">Видео</th>
                        <th scope="col">Просмотров</th>
                        <th scope="col">Длительность</th>
                        <th scope="col">Рейтинг</th>
                        <th scope="col">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($top50 as $top)
                        <tr data-id=" {{ $top->video_id }}">
                            <td>
                                {{ $top->video_published_at }}
                            </td>
                            <td>
                                <a href="{{ route('channel_show', ['id' => $top->channel_id]) }}">{{ $top->channel_title }}</a>
                            </td>
                            <td>
                                <a href="{{ route('video_show', ['id' => $top->video_id]) }}">{{ $top->video_title }}</a>
                            </td>
                            <td>{{ number_format($top->max_views, 0, ',', ' ') }}</td>
                            <td>{{ gmdate("i:s", $top->length_seconds) }}</td>
                            <td>{{ $top->average_rating}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        Действия
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a target="_blank" class="dropdown-item" href="{{ route('video_show', ['id' => $top->video_id]) }}">Статистика
                                                видео</a></li>
                                        <li><a target="_blank" class="dropdown-item" href="{{ route('channel_show', ['id' => $top->channel_id]) }}">Статистика
                                                канала</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-video-log" role="tabpanel" aria-labelledby="nav-video-log-tab">
            <div class="table-responsive">
                <table class="table table-striped table-sm">
                    <thead>
                    <tr>
                        <th scope="col">Опубликован</th>
                        <th scope="col">Канал</th>
                        <th scope="col">Видео</th>
                        <th scope="col">Просмотров</th>
                        <th scope="col">Длительность</th>
                        <th scope="col">Рейтинг</th>
                        <th scope="col">Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($newVideos as $video)
                        <tr data-id=" {{ $video->video_id }}">
                            <td>
                                {{ $video->video_published_at }}
                            </td>
                            <td>
                                <a href="{{ route('channel_show', ['id' => $video->channel_id]) }}">{{ $video->channel_title }}</a>
                            </td>
                            <td>
                                <a href="{{ route('video_show', ['id' => $video->video_id]) }}">{{ $video->video_title }}</a>
                            </td>
                            <td>{{ number_format($video->max_views, 0, ',', ' ') }}</td>
                            <td>{{ gmdate("i:s", $video->length_seconds) }}</td>
                            <td>{{ $video->average_rating}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                        Действия
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                        <li><a target="_blank" class="dropdown-item" href="{{ route('video_show', ['id' => $video->video_id]) }}">Статистика
                                                видео</a></li>
                                        <li><a target="_blank" class="dropdown-item" href="{{ route('channel_show', ['id' => $video->channel_id]) }}">Статистика
                                                канала</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>




    <script type="text/javascript">
        let concurrencySeries = <?= json_encode($concurrency['series'])?>;
        let concurrencyDrilldown = <?= json_encode($concurrency['drilldown'])?>;

        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Анализ конкурентов'
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
                type: 'category',
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
                // headerFormat: '<span style="font-size:11px">{point.tes}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>',
            },

            series: concurrencySeries,
            drilldown: concurrencyDrilldown
        });
    </script>
@endsection

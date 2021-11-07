@extends('layouts.main')
@section('title', $video->title)
@section('content')
    <div
        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">{{$video->title}}</h1>
        @if(false):
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
        @endif
    </div>

    <h2>Просмотры</h2>
    <figure class="highcharts-figure">
        <div id="container-views"></div>
    </figure>

    <h2>Лайки/дизлайки</h2>
    <figure class="highcharts-figure">
        <div id="container-like-dislike"></div>
    </figure>

    <h2>Средний рейтинг</h2>
    <figure class="highcharts-figure">
        <div id="container-average-rating"></div>
    </figure>

    <div class="card mb-3" style="max-width: 540px;">
        <div class="row g-0">
            <div class="col-md-4">
                <img src="{{$video->getThumbnail()}}" class="img-fluid rounded-start" alt="">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h5 class="card-title">{{$video->title}}</h5>
                    <p class="card-text"></p>
{{--                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>--}}
                    <a target="_blank" href="{{$video->url}}" class="btn btn-sm btn-primary">Открыть видео</a>
                    <a target="_blank" href="{{$video->channel->url}}" class="btn btn-sm btn-primary">Открыть канал</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        let LDSeriesSeries = <?= json_encode($likeDislike['series'])?>;
        let LDSeriesCategories = <?= json_encode($likeDislike['categories'])?>;

        Highcharts.chart('container-like-dislike', {
            chart: {
                type: 'area'
            },
            title: {
                text: 'Like and dislike video'
            },
            xAxis: {
                allowDecimals: false,
                categories: LDSeriesCategories
            },
            yAxis: {
                title: {
                    text: 'Количество'
                },
            },

            tooltip: {
                pointFormat: '{series.name} had stockpiled <b>{point.y:,.0f}</b><br/>warheads in {point.x}'
            },
            // plotOptions: {
            //     area: {
            //         pointStart: 1940,
            //         marker: {
            //             enabled: false,
            //             symbol: 'circle',
            //             radius: 2,
            //             states: {
            //                 hover: {
            //                     enabled: true
            //                 }
            //             }
            //         }
            //     }
            // },
            series: LDSeriesSeries
        });

        let viewsSeries = <?= json_encode($views['series'])?>;
        let viewsCategories = <?= json_encode($views['categories'])?>;

        Highcharts.chart('container-views', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Динамика просмотров'
            },
            xAxis: {
                categories: viewsCategories
            },
            yAxis: {
                title: {
                    text: 'Количество'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: viewsSeries
        });

        let averageRatingSeries = <?= json_encode($averageRating['series'])?>;
        let averageRatingCategories = <?= json_encode($averageRating['categories'])?>;

        Highcharts.chart('container-average-rating', {
            chart: {
                type: 'line'
            },
            title: {
                text: 'Динамика рейтинга'
            },
            xAxis: {
                categories: averageRatingCategories
            },
            yAxis: {
                title: {
                    text: 'Количество'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            series: averageRatingSeries
        });
    </script>
@endsection

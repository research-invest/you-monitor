@extends('layouts.main')
@section('title', 'Общая статистика каналов')
@section('content')

    <h2 class="pb-2 border-bottom">Общая статистика каналов</h2>

    <div class="container">
        <div class="row">
            <div class="col-6">
                <figure class="highcharts-figure">
                    <div id="container-length_videos"></div>
                </figure>
            </div>
            <div class="col-6">
                <figure class="highcharts-figure">
                    <div id="container-length_videos"></div>
                </figure>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        let lengthVideosSeries = <?= json_encode($lengthVideos)?>;
        Highcharts.chart('container-length_videos', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Распределение длительности видео'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: lengthVideosSeries
        });
    </script>
@endsection

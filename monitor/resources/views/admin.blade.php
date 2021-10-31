@extends('layouts.main')

<script src="https://code.highcharts.com/highcharts.js"></script>

@section('content')
    <main class="container">
        <div class="row">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Url</th>
                    <th>Count visit</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                @foreach($data as $row)
                    <tr>
                        <td>{{$row['url']}}</td>
                        <td>{{$row['count']}}</td>
                        <td>{{$row['created_at']}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center">
            {!! $paginator->links('misc.paginator') !!}
        </div>

        <div class="row">
            <figure class="highcharts-figure">
                <div id="container"></div>
            </figure>
        </div>
    </main>

    <script type="text/javascript">
        Highcharts.chart('container', {
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie'
            },
            title: {
                text: 'Distribution of viewed pages'
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
            series: [{
                name: 'Pages',
                colorByPoint: true,
                data: @json($pieData)
            }]
        });
    </script>
@endsection

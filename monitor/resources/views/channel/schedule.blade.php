@extends('layouts.main')
@section('title', 'Расписание выходов роликов<')
@section('content')
    <h2 class="pb-2 border-bottom">Расписание выходов роликов - {{$channel->title}}</h2>
    <a target="_blank" href="{{$channel->url}}" class="btn btn-sm btn-primary">Открыть канал</a>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th></th>
                        <th>Понедельник</th>
                        <th>Вторник</th>
                        <th>Среда</th>
                        <th>Четверг</th>
                        <th>Пятница</th>
                        <th>Суббота</th>
                        <th>Воскресенье</th>
                    </tr>
                    </thead>
                    <tbody>
                    @for ($w =1; $w <= 4; $w++)
                        <tr>
                            <td>
                                {{ $w }}
                            </td>
                            @for ($d =1; $d <= 7; $d++)
                                <td>
                                    @for ($h =1; $h <= 24; $h++)
                                        <?php $class = isset($schedule[$w][$d][$h]) ? 'text-white bg-success' : '' ?>
                                        <span class="{{$class}} p-1 mt-1">
                                            <?php if(isset($schedule[$w][$d][$h])): ?>
                                              <a class="{{$class}}" target="_blank" href="{{ $schedule[$w][$d][$h]['url'] }}">{{ $h }}</a>
                                            <?php else: ?>
                                                {{ $h }}
                                            <?php endif; ?>
                                        </span>
                                    @endfor
                                    <div class="bg-light text-center"><?php echo isset($schedule[$w][$d]) ? count($schedule[$w][$d]) : '0' ?></div>
                                </td>
                            @endfor
                        </tr>
                    @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

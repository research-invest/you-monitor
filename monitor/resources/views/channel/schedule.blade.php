@extends('layouts.main')
@section('title', 'Общая статистика каналов')
@section('content')
    <h2 class="pb-2 border-bottom">Расписание выходов роликов</h2>
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

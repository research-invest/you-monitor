@extends('layouts.main')
@section('title', 'Каналы')
@section('content')
    <h2>Каналы</h2>
    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead>
            <tr>
                <th scope="col">Название</th>
                <th scope="col">Дата создания</th>
                <th scope="col">Просмотров</th>
                <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($channels as $channel)
                @if ($channel->status === 2)
                    @php $rowClass = 'table-danger' @endphp
                @else
                    @php $rowClass = 'table-success' @endphp
                @endif

                <tr class="{{ $rowClass}}" data-id=" {{ $channel->id }}">
                    <td>
                        {{ $channel->title }}<br>
                        <a target="_blank" href="{{$channel->url}}">перейти</a>
                    </td>
                    <td>
                        {{ $channel->published_at }}
                    </td>
                    <td>
                        {{ number_format($channel->count_views, 0, ',', ' ') }}
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                Действия
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <li><a class="dropdown-item" href="{{ route('channel_show', ['id' => $channel->id]) }}">Подробно</a></li>
                            </ul>
                        </div>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

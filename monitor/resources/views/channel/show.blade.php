@extends('layouts.main')
@section('title', 'Каналы')
@section('content')
    <h2>{{$channel->title}}</h2>
    <a target="_blank" href="{{$channel->url}}" class="btn btn-sm btn-primary">Открыть канал</a>

    <h2>Видео</h2>
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Название</th>
                <th scope="col">Опубликовали</th>
                <th scope="col">Длительность</th>
                <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($videos as $video)
                <tr data-id=" {{ $video->id }}">
                    <td>
                        <img src="{{$video->getThumbnail()}}" class="img-fluid rounded-start" alt="">
                    </td>
                    <td>
                        {{ $video->title }}<br>
                        <a target="_blank" href="{{$video->url}}">перейти</a>
                    </td>
                    <td>
                        {{ $video->published_at }}
                    </td>
                    <td>{{ gmdate("i:s", $video->length_seconds) }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                Действия
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                <li><a target="_blank" class="dropdown-item" href="{{ route('video_show', ['id' => $video->video_id]) }}">Статистика
                                        видео</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

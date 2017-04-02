@extends('base')
@section('panel_content')
    @if(isset($tasklist))
        {!! $tasklist->description !!}
        <ul>
            @foreach($tasklist->tasks as $task)
                <li><a href="{{route('show.task',$task->id)}}">Tehtävä {!! $task->description !!}</a> </li>
            @endforeach
        </ul>
    @endif
@stop

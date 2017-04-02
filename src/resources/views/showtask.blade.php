@extends('base')
@section('panel_content')
    @if(isset($task))

        @if (isset($tasklist))
            <a href="{{ route('show.tasklist', $tasklist) }}">&larr; Takaisin tehtävälistaan</a>
        @endif

        <h3>Tehtävä {{$task->id}}</h3>
        <div class="panel panel-default">
            <div class="panel-heading">Kuvaus</div>
            <div class="panel-body">
                {!! $task->description !!}
            </div>
        </div>
    @endif
@stop
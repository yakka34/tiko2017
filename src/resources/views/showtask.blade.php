@extends('base')
@section('panel_content')
    @if(isset($task))

        @if (isset($session))
            <a href="{{ route('session.show.tasklist', $session) }}">&larr; Takaisin tehtävälistaan</a>
            <div>
                <a href="{{ $previous }}">&larr; Edellinen</a>
                <a href="{{ $next }}">&rarr; Seuraava</a>
            </div>

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
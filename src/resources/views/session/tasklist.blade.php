@extends('base')
@section('panel_content')
    @if(isset($tasklist))

        <div>
            <a href="{{ route('home') }}">&larr; Takaisin</a>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Kuvaus</div>
            <div class="panel-body">
                {!! $tasklist->description !!}
            </div>
        </div>

        <h4>Tehtävät</h4>
        <ul>
            @foreach($tasklist->tasks as $task)
                <li><a href="{{route('session.show.task',[$session,$tasklist->id, $task->id])}}">Tehtävä {{ $task->id }}</a> </li>
            @endforeach
        </ul>
        <!-- TODO:Lopeta sessio vain jos tehtävät on suoritettu joka hylätysti tai onnistuneesti -->
        @if(isset($session))
            <a class="btn btn-primary" href="{{route('session.stop',$session)}}">Lopeta sessio</a>
        @endif
    @endif

@stop

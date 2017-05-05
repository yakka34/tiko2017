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

        <div>
            Tämä tehtävälista sisältää {{ count($tasklist->tasks) }} tehtävä{{ count($tasklist->tasks) > 1 ? 'ä' : 'n' }}.
        </div>

        <div>
            <a class="btn btn-primary" href="{{ route('session.start', $tasklist->id) }}">Aloita tehtäväsessio</a>
        </div>
    @endif

@stop

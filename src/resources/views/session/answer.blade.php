@extends('base')
@section('panel_content')
    @if(isset($task))

        @if (isset($session))
            <a href="{{ route('session.show.tasklist', $session) }}">&larr; Takaisin tehtävälistaan</a>
            <div>
                <a href="{{ $next }}">Seuraava tehtävä &rarr; </a>
            </div>
        @endif
        <h3>Tehtävä {{$task->id}}</h3>
        <div class="panel panel-default">
            <div class="panel-heading">Kuvaus</div>
            <div class="panel-body">
                {!! $task->description !!}
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <p>
                    Et saanut tehtävää suoritettua. Alta voit katsoa oikean vastauksen tai jatkaa vain suoraan seuraavaan tehtävään.
                </p>

                <div class="form-group">
                    <label for="query">Oikea vastaus</label>
                    <div><button class="btn btn-success" data-toggle="collapse" data-target="#answer">Näytä vastaus</button></div>
                    <textarea class="form-control collapse" id="answer" rows="5">{{ $task->answer }}</textarea>
                </div>
            </div>
        </div>


        <div>
            <a class="btn btn-primary" href="{{ $next }}">Seuraava tehtävä</a>
        </div>
    @endif
@stop
@extends('base')
@section('panel_content')
    @if(isset($task))

        @if (isset($session))
            <a href="{{ route('session.show.tasklist', $session) }}">&larr; Takaisin tehtävälistaan</a>
            <div>
                <a href="{{ $previous }}">&larr; Edellinen</a>
                <a href="{{ $next }}">Seuraava &rarr; </a>
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
            <form method="post">
                {{csrf_field()}}
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <label for="query">Vastauksesi</label>
                <!--TODO Vue komponentin nimi sama kuin html-elementin, ei vissiin käytetä sitä sitten, SQL kyselyä ei voi ajaa html tagien kera!!!!!!!!!!!! -->
                <!--<textarea name="query" id="query" rows="5"></textarea> -->
                <input class="form-control" type="text" id="query" name="query">
                <input type="submit" class="btn btn-primary form-control">
            </form>
        </div>
    @endif
@stop
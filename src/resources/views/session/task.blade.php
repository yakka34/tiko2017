@extends('base')
@section('panel_content')
    @if(isset($task))

        @if (isset($session))
            <a href="{{ route('session.show.tasklist', $session) }}">&larr; Takaisin tehtävälistaan</a>
            <div>
                <a href="{{ $previous }}">&larr; Edellinen</a>
                |
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
            <div class="panel-body">
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
                    <div class="form-group">
                        <label for="query">Vastauksesi</label>
                        <textarea class="form-control" name="query" id="query" rows="5">
                        </textarea>
                    </div>
                    <input type="submit" class="btn btn-primary form-control" value="Lähetä vastaus">
                </form>
            </div>
        </div>
    @endif
@stop
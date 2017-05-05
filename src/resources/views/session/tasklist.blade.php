@extends('base')
@section('panel_content')
    @if(isset($tasklist))

        {{--<div>
            <a href="{{ route('home') }}">&larr; Takaisin</a>
        </div>--}}

        <div class="panel panel-default">
            <div class="panel-heading">Kuvaus</div>
            <div class="panel-body">
                {!! $tasklist->description !!}
            </div>
        </div>

        @php
            $done_count = 0;
        @endphp

        <div class="panel panel-primary">
            <div class="panel-heading">Tehtävät</div>
            <div class="panel-body">
                <ul>
                    {{-- Listataan tehtävät ja päästetään käyttäjä vain niihin tehtäviin, joita ei ole vielä suoritettu --}}
                    @foreach($tasklist->tasks as $task)
                        @php
                            $sessiontask = App\Sessiontask::where(['session_id' => $session, 'task_id' => $task->id])->first();
                            $done = false;
                            $correct = false;
                            $tries = 0;
                            if ($sessiontask != null) {
                                $correct = $sessiontask->correct;
                                $done = ($sessiontask->finished_at != null);
                                $tries = count(App\Taskattempt::where('sessiontask_id', $sessiontask->id)->get());
                            }
                            if ($done) {
                                $done_count++;
                            }
                        @endphp
                        <li>
                            @if ($tries >= 3 || $done)
                                {{-- Tehtävä on suoritettu oikein tai kaikki yritykset on käytetty --}}
                                <s>Tehtävä {{ $task->id }}</s> ({{ $tries }} yritys{{ $tries > 1 ? 'tä' :'' }}) (<span class="text-{{ $correct ? 'success' : 'danger' }}"><b>{{ $correct ? 'oikein!' : 'väärin!' }}</b></span>)
                            @elseif ($tries > 0)
                                {{-- Tehtävää on yritetty suorittaa --}}
                                <a href="{{route('session.show.task',[$session,$tasklist->id, $task->id])}}">Tehtävä {{ $task->id }}</a> ({{ $tries }} yritys{{ $tries > 1 ? 'tä' :'' }})
                            @else
                                {{-- Tehtävää ei ole yritetty suorittaa --}}
                                <a href="{{route('session.show.task',[$session,$tasklist->id, $task->id])}}">Tehtävä {{ $task->id }}</a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        @if(isset($session))
            <a class="btn btn-primary {{ $done_count == count($tasklist->tasks) ? '' : 'disabled' }}" href="{{route('session.stop',$session)}}">Lopeta sessio</a>
            <div class="help-block">Session voi lopettaa vasta kun kaikki tehtävät on suoritettu (joko oikein tai väärin 3 kertaa)</div>
        @endif
    @endif

@stop

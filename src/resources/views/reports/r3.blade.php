@extends('base')
@section('panel_content')

    <div>
        <p>
            <a href="{{ route('home') }}">&larr; Takaisin</a>
        </p>
    </div>

    {{-- Tehtävälistakohtainen yhteenveto (tehtävänannossa R3) --}}

    <div class="help-block">Ajat formaatissa <em>hh:mm:ss.fff</em></div>

    @forelse ($data as $tasklist)
        <h4>Tehtävälista {{ $tasklist['tasklist_id'] }}</h4>
        <table class="table table-striped table-condensed table-bordered">
            <thead>
                <tr>
                    <th>Tehtävän ID</th>
                    <th>Onnistumisprosentti</th>
                    <th>Keskimääräinen suoritusaika</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($tasklist['tasks'] as $task)
                <tr>
                    <td>{{ $task['task_id'] }}</td>
                    <td>{{ $task['success_per'] }} %</td>
                    <td>{{ $task['average_time'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Ei tehtäviä!</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    @empty
        <p>Ei tehtävälistoja!</p>
    @endforelse

@stop
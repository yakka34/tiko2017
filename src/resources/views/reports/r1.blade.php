@extends('base')
@section('panel_content')

    <div>
        <p>
            <a href="{{ route('home') }}">&larr; Takaisin</a>
        </p>
    </div>

    {{-- Sessioraportti (tehtävänannossa R1) --}}
    <table class="table table-striped table-condensed table-bordered">
        <thead>
            <tr>
                <th>Session ID</th>
                <th>Käyttäjä</th>
                <th>Onnistuneita tehtäviä</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td>{{ $row['session_id'] }}</td>
                    <td>{{ $row['user']->name }}</td>
                    <td>{{ $row['successful_tasks'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Ei suorituksia!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

@stop
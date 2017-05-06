@extends('base')
@section('panel_content')

    <div>
        <p>
            <a href="{{ route('home') }}">&larr; Takaisin</a>
        </p>
    </div>

    {{-- Tehtävälistakohtainen suoritusaikaraportti (tehtävänannossa R2) --}}

    <div class="help-block">Ajat formaatissa <em>hh:mm:ss</em></div>

    <table class="table table-striped table-condensed table-bordered">
        <thead>
            <tr>
                <th>Tehtävälistan ID</th>
                <th>Nopein suoritus</th>
                <th>Hitain suoritus</th>
                <th>Keskimääräinen suoritusaika</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($data as $row)
            <tr>
                <td>{{ $row['tasklist_id'] }}</td>
                <td>{{ $row['fastest'] }}</td>
                <td>{{ $row['slowest'] }}</td>
                <td>{{ $row['average'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Ei suorituksia!</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@stop
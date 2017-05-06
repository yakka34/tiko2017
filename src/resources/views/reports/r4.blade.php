@extends('base')
@section('panel_content')

    <div>
        <p>
            <a href="{{ route('home') }}">&larr; Takaisin</a>
        </p>
    </div>

    {{-- Tehtävälistaus vaikeusjärjestyksessä (tehtävänannossa R4) --}}

    <div class="help-block">Ajat formaatissa <em>hh:mm:ss.fff</em></div>

    <table class="table table-striped table-condensed table-bordered">
        <thead>
            <tr>
                <th>Tehtävän ID</th>
                <th>Keskimääräinen suoritusaika</th>
                <th>Keskimääräisiä yrityksiä oikeaan vastaukseen</th>
                <th>Yrityksiä, jotka eivät tuottaneet ratkaisua</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($data as $row)
            <tr>
                <td>{{ $row['task_id'] }}</td>
                <td>{{ $row['avg_time'] }}</td>
                <td>{{ $row['avg_attempts'] }}</td>
                <td>{{ $row['failure_per'] }} %</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Ei tehtäviä!</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@stop
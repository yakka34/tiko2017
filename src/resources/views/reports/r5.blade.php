@extends('base')
@section('panel_content')

    <div>
        <p>
            <a href="{{ route('home') }}">&larr; Takaisin</a>
        </p>
    </div>

    {{-- Tyyppikohtaiset tilastot (tehtävänannossa R5) --}}

    <div class="help-block">Ajat formaatissa <em>hh:mm:ss.fff</em></div>

    <table class="table table-striped table-condensed table-bordered">
        <thead>
            <tr>
                <th>Tyyppi</th>
                <th>Keskimääräinen suoritusaika</th>
                <th>Vastausyrityksiä keskimäärin</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($data as $type => $row)
            <tr>
                <td>{{ $type }}</td>
                <td>{{ $row['avg_time'] }}</td>
                <td>{{ $row['avg_attempts'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3">Ei tehtäviä!</td>
            </tr>
        @endforelse
        </tbody>
    </table>

@stop
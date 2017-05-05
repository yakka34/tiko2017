@extends('base')

@section('panel_content')

    <h4>Suoritetut sessiot</h4>
    <table class="table table-striped table-bordered table-condensed">
        <thead>
            <tr>
                <th>Tehtävien lkm</th>
                <th>Suorituspvm</th>
                <th>Suoritusaika (mm:ss)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($completed_sessions as $session)
                <tr>
                    <td>{{ $session->tasklist()->first()->tasks()->count() }}</td>
                    <td>{{ $session->finishedAt() }}</td>
                    <td>{{ gmdate('i:s', $session->secondsTookToComplete()) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Ei suoritettuja sessioita!</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div>
        <a href="{{ route('home') }}">&larr; Etusivulle</a>
    </div>

@stop
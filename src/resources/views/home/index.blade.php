@extends('base')

@section('panel_content')

    @if (Auth::check())
        {{-- Käyttäjä on kirjautunut sisään --}}
        Terve, {{Auth::user()->name}}

        {{-- Jos käyttäjä voi suorittaa tehtäviä, listataan tehtävälistat --}}
        @if (Auth::user()->can('solve-task') and isset($tasklists))
            <h3>Tehtävälistat</h3>
            @include('home.tasklist')
        @endif

    @else
        {{-- Käyttäjä ei ole kirjautunut sisään --}}
        <p>
            Et ole kirjautunut sisään.
        </p>
        <p>
            Kirjaudu sisään tai luo tunnus oikeasta yläkulmasta.
        </p>
    @endif

@stop
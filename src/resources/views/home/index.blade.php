@extends('base')

@section('panel_content')

    @if (Auth::check())
        {{-- Käyttäjä on kirjautunut sisään --}}
        <div>Terve, {{Auth::user()->name}}!</div>

        <div>
            <p>
                Tarkista mahdolliset toiminnot oikean yläkulman valikosta.
            </p>
        </div>

        @if (Auth::user()->hasRole('teacher') || Auth::user()->hasRole('admin'))
            <div>
                <p>Tarkastele luomiesi tehtävälistojen suorittajien tietoja <a href="{{ route('admin') }}">täällä</a>.</p>
            </div>
            <div>
                <h3>Raportit</h3>
                <ul>
                    <li><a href="{{ route('report.r1') }}">R1 - Suoritettujen sessioiden tiedot</a></li>
                    <li><a href="{{ route('report.r2') }}">R2 - Tehtävälistakohtainen suoritusaika</a></li>
                </ul>
            </div>
        @endif

        {{-- Jos käyttäjä voi suorittaa tehtäviä, listataan tehtävälistat --}}
        @if (Auth::user()->can('solve-task') and isset($tasklists))
            <div>
                <h3>Tehtävälistat</h3>
                @include('home.tasklist')
            </div>
            <div>
                <h3>Suoritukset</h3>
                <p>Tarkastele suorituksiasi <a href="{{ route('stats') }}">täällä</a>.</p>
            </div>
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
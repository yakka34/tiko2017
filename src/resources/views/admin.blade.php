@extends('base')

@section('panel_content')
    <div>
        <b><a href="{{ route('home') }}">&larr; Takaisin etusivulle</a></b>
    </div>
    <hr />

    @if (Auth::user()->hasRole('teacher'))
        <div class="alert alert-info">
            <strong>Huomaa!</strong> Roolisi on opettaja, joten näet tässä vain ne opiskelijat, jotka ovat tehneet luomiasi tehtävälistoja.
        </div>
    @endif

    <ul>
        @forelse ($users as $user)
            <li>
                @if (Auth::user()->hasRole('admin'))
                    <a href="{{route('show',$user->id)}}">{{$user->name}}</a>
                @elseif (Auth::user()->hasRole('teacher'))
                    {{-- Opettaja ei pääse editointisivulle, mutta voi nähdä niiden oppilaiden tiedot, jotka ovat tehneet hänen tehtävälistojaan --}}
                    {{$user->name}}
                @endif
                <ul>
                    @if(isset($user->studentId))
                        <li>Opnro: {{$user->studentId}}</li>
                    @endif
                    @if(isset($user->major))
                        <li>Pääaine: {{$user->major}}</li>
                    @endif
                    @if(isset($user->email))
                        <li>Sähköposti: {{$user->email}}</li>
                    @endif
                    @if(isset($user->created_at))
                        <li>Pvm: {{$user->created_at}}</li>
                    @endif
                    @if(isset($user->roles))
                        <li>Roolit:</li>
                        <ul>
                            @foreach($user->roles as $role)
                                <li>
                                    {{$role->name}}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </ul>
            </li>
        @empty
            <li>Ei opiskelijoita</li>
        @endforelse
    </ul>
@stop
@extends('base')

@section('panel_content')
    <div>
        <b><a href="{{ route('home') }}">&larr; Takaisin etusivulle</a></b>
    </div>
    <hr />
    <ul>
        @foreach($users as $user)
            <li><a href="{{route('show',$user->id)}}">{{$user->name}}</a>
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
        @endforeach
    </ul>
@stop
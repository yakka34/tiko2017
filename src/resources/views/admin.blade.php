@extends('home')

@section('panel_content')
    <ul>
    @foreach($users as $user)
        <li> <a href="{{route('show',$user->id)}}">{{$user}}</a>
            <ul>
                @foreach($user->roles as $role)
                    <li>
                        {{$role->name}}
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach
    </ul>
@stop
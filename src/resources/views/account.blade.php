@extends('home')

@section('panel_content')
    {{$user}}
    <h1>Roolisi ovat:</h1>
    <ul>
        @foreach($roles as $role)
            <li>{{$role->name}}</li>
        @endforeach
    </ul>
@stop


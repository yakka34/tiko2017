@extends('home')

@section('content')
    <div class="panel-body">
        <h1>Roolisi ovat:</h1>
        <ul>
            @foreach($roles as $role)
                <li>{{$role->name}}</li>
            @endforeach
        </ul>
    </div>
@stop
@extends('home')

@section('panel_content')
    <form>
        <div class="form-group">
            <label for="nameField">Nimi</label>
            @if (Auth::user()->hasRole('admin'))
                <input type="text" id="nameField" class="form-control" value="{{$user->name}}">
            @else
                <input type="text" id="nameField" class="form-control" value="{{$user->name}}" disabled>
            @endif
        </div>
        <div class="form-group">
            <label for="studentIdField">Opiskelijanumero</label>
            @if (Auth::user()->hasRole('admin'))
                <input type="text" id="studentIdField" class="form-control" value="{{$user->opnro}}">
            @else
                <input type="text" id="studentIdField" class="form-control" value="{{$user->opnro}}" disabled>
            @endif
        </div>
        <div class="form-group">
            <label for="majorField">Pääaine</label>
            @if (Auth::user()->hasRole('admin'))
                <input type="text" id="majorField" class="form-control" value="{{$user->major}}">
            @else
                <input type="text" id="majorField" class="form-control" value="{{$user->major}}" disabled>
            @endif
        </div>
        <div class="form-group">
            <label for="emailField">Sähköposti</label>
            <input type="text" id="emailField" class="form-control" value="{{$user->email}}">
        </div>
    </form>
    <h1>Roolisi ovat:</h1>
    <ul>
        @foreach($roles as $role)
            <li>{{$role->name}}</li>
        @endforeach
    </ul>
@stop


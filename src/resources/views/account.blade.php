@extends('home')

@section('panel_content')
    <form action="/account" method="post">
        {{csrf_field()}}

        <div class="form-group {{$errors->has('nameField') ? 'has-error' : ''}}">
            <label for="nameField">Nimi</label>
            @can ('update-info')
                <input type="text" name="name" id="name" class="form-control" value="{{Auth::user()->name}}">
            @else
                <input type="text" id="name" class="form-control" value="{{Auth::user()->name}}" disabled>
            @endcan
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <label for="studentIdField">Opiskelijanumero</label>
            @can ('update-student-info')
                <input type="text" name="studentIdField" id="studentIdField" class="form-control" value="{{Auth::user()->opnro}}">
            @else
                <input type="text" id="studentIdField" class="form-control" value="{{Auth::user()->opnro}}" disabled>
            @endcan
        </div>
        <div class="form-group">
            <label for="majorField">Pääaine</label>
            @can ('update-student-info')
                <input type="text" name="majorField" id="majorField" class="form-control" value="{{Auth::user()->major}}">
            @else
                <input type="text" id="majorField" class="form-control" value="{{Auth::user()->major}}" disabled>
            @endcan
        </div>
        <div class="form-group" {{$errors->has('email') ? 'has-error' : ''}}>
            <label for="email">Sähköposti</label>
            @can ('update-info')
                <input type="text" id="email" name="email" class="form-control" value="{{Auth::user()->email}}">
            @else
                <input type="text" id="email" name="email" class="form-control" value="{{Auth::user()->email}}" disabled>
            @endcan
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
        <button type="submit" class="btn btn-primary">Tallenna</button>
    </form>

    <h3>Roolit ja oikeudet:</h3>
    <ul>
    @foreach (Auth::user()->roles as $role)
        <li>
            {{$role->name}}
            <ul>
                @foreach ($role->permissions as $permission)
                    <li>{{$permission->name}}</li>
                @endforeach
            </ul>
        </li>
    @endforeach
    </ul>

@stop


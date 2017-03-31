@extends('home')

@section('panel_content')

    @if (Auth::user()->hasRole('admin'))
        <div>
            <b><a href="{{ route('admin') }}">&larr; Takaisin hallintaan</a></b>
        </div>
        <hr />
    @endif

    <form action="{{ route('account.id.update', ['id' => $user->id]) }}" method="post">
        {{csrf_field()}}

        <div class="form-group {{$errors->has('nameField') ? 'has-error' : ''}}">
            <label for="nameField">Nimi</label>
            @can ('update-info')
                <input type="text" name="name" id="name" class="form-control" value="{{$user->name}}">
            @else
                <input type="text" id="name" class="form-control" value="{{$user->name}}" disabled>
            @endcan
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
        <div class="form-group">
            <label for="studentIdField">Opiskelijanumero</label>
            @can ('update-student-info')
                <input type="text" name="studentIdField" id="studentIdField" class="form-control" value="{{$user->opnro}}">
            @else
                <input type="text" id="studentIdField" class="form-control" value="{{$user->opnro}}" disabled>
            @endcan
        </div>
        <div class="form-group">
            <label for="majorField">Pääaine</label>
            @can ('update-student-info')
                <input type="text" name="majorField" id="majorField" class="form-control" value="{{$user->major}}">
            @else
                <input type="text" id="majorField" class="form-control" value="{{$user->major}}" disabled>
            @endcan
        </div>
        <div class="form-group" {{$errors->has('email') ? 'has-error' : ''}}>
            <label for="email">Sähköposti</label>
            @can ('update-info')
                <input type="text" id="email" name="email" class="form-control" value="{{$user->email}}">
            @else
                <input type="text" id="email" name="email" class="form-control" value="{{$user->email}}" disabled>
            @endcan
            {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
        </div>
        <button type="submit" class="btn btn-primary">Tallenna</button>

    </form>

    <h3>Roolit ja oikeudet:</h3>
    <ul>
        @foreach ($user->roles as $role)
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
    @if(count($roles) > 0)
        <form class="form-group" action="{{route('add.role',$user->id)}}" method="post">
            {{csrf_field()}}
            <div>
                <label for="roles">Lisää rooli käyttäjälle</label>
                <select name="role" id="roles" class="form-group">
                    @foreach($roles as $role)
                        <option value="{{$role->id}}">{{$role->name}}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tallenna rooli</button>
        </form>
    @endif
@stop
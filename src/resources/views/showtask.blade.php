@extends('base')
@section('panel_content')
    @if(isset($task))
        <h1>Tehtävä {{$task->id}}</h1>
        {!! $task->description !!}
    @endif
@stop
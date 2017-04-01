@extends('base')

@section('panel_content')

    <h3>Omat tehtävälistat</h3>
    <tasklisthandler></tasklisthandler>

    <h3>Omat tehtävät</h3>
    <div>
        {{--@include('missioncontrol.task_listing')--}}
        <tasks></tasks>

        <a href="{{route('create.task')}}" class="btn btn-primary">Luo uusi tehtävä</a>
    </div>
@stop
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">

        @if(session('status'))
            <div class="alert alert-info">
                {{session('status')}}
            </div>
        @endif

        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>{{ $page_name or 'Sivu' }}</h2></div>

                <div class="panel-body">
                    @yield('panel_content')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

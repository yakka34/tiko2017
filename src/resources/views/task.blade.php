@extends('base')
@section('panel_content')
    <script src="//cloud.tinymce.com/stable/tinymce.min.js"></script>
    <script>tinymce.init({ selector:'textarea', plugins:'image',invalid_elements: 'script'});</script>
    <form action="@if(isset($task)){{route('update.task',$task->id)}}@else{{route('save.task')}}@endif" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="description">Tehtävän kuvaus</label>
            <textarea class="form-control" name="description" id="description">
                @if(isset($task->description))
                    {{$task->description}}
                @endif
            </textarea>
        </div>
        <div class="form-group">
            <label for="type">Valitse kyselytyyppi</label>
            <select class="form-control" name="type" id="type">
                <option value="update" @if(isset($task) && $task->type=='update') selected @endif>update</option>
                <option value="select" @if(isset($task) && $task->type=='select') selected @endif>select</option>
                <option value="delete" @if(isset($task) && $task->type=='delete') selected @endif>delete</option>
                <option value="insert" @if(isset($task) && $task->type=='insert') selected @endif>insert</option>
            </select>
        </div>
        <div class="form-group">
            <label for="answer">Esimerkkivastaus</label>
            <input type="text" class="form-control" name="answer" id="answer" @if(isset($task))value="{{$task->answer}}" @endif>
        </div>
        <button type="submit" class="btn btn-primary">Tallenna tehtävä</button>
    </form>
@stop
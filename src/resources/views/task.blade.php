@extends('base')
@section('panel_content')
    <script src="//cloud.tinymce.com/stable/tinymce.min.js"></script>
    <script>tinymce.init({ selector:'textarea', plugins:'image'});</script>
    <form action="{{route('save.task')}}" method="post">
        {{csrf_field()}}
        <div class="form-group">
            <label for="description">Tehtävän kuvaus</label>
            <textarea class="form-control" name="description" id="description"></textarea>
        </div>
        <div class="form-group">
            <label for="type">Valitse kyselytyyppi</label>
            <select class="form-control" name="type" id="type">
                <option value="update">update</option>
                <option value="select">select</option>
                <option value="delete">delete</option>
                <option value="insert">insert</option>
            </select>
        </div>
        <div class="form-group">
            <label for="answer">Esimerkkivastaus</label>
            <input type="text" class="form-control" name="answer" id="answer">
        </div>
        <button type="submit" class="btn btn-primary">Tallenna tehtävä</button>
    </form>
@stop
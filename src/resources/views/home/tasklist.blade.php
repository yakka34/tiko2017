@if (Auth::user()->can('solve-task') and isset($tasklists))
    <h3>Tehtävälistat</h3>
    <ul>
        @forelse ($tasklists as $tasklist)
            <li>Tehtävälista {{$tasklist->id}}, luotu {{$tasklist->created_at}}</li>
        @empty
            <p>Ei tehtävälistoja saatavilla</p>
        @endforelse
    </ul>
@endif
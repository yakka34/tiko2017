<ul>
    @forelse ($tasklists as $tasklist)
                <li><a href="{{route('show.tasklist',$tasklist->id)}}">Tehtävälista {{$tasklist->id}}, luotu {{$tasklist->created_at}}</a></li>
    @empty
        <p>Ei tehtävälistoja</p>
    @endforelse
</ul>